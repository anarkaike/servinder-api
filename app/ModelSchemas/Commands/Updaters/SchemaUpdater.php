<?php

namespace App\ModelSchemas\Commands\Updaters;

use App\ModelSchemas\Commands\Builders\SchemaDefinitionBuilder;
use App\ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use App\ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use App\ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use App\ModelSchemas\Commands\Contracts\SchemaUpdaterInterface;
use App\ModelSchemas\Commands\Contracts\VersionManagerInterface;
use App\ModelSchemas\Enums\ESchemaKey;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;


/**
 * Classe responsável por atualizar o esquema de banco de dados de um modelo.
 *
 * Português
 * Atualiza o esquema de banco de dados de um modelo, incluindo adição, atualização e remoção de colunas,
 * além de verificar e aplicar restrições de chave estrangeira.
 *
 * Espanhol
 * Actualiza el esquema de base de datos de un modelo, incluyendo la adición, actualización y eliminación de columnas,
 * además de verificar y aplicar restricciones de clave foránea.
 *
 * Inglês
 * Updates the database schema of a model, including adding, updating, and removing columns,
 * as well as checking and applying foreign key constraints.
 *
 * Objetivos principais
 * - Gerenciar a atualização do esquema de banco de dados de um modelo.
 * - Adicionar, atualizar e remover colunas de acordo com o esquema definido.
 * - Verificar e aplicar restrições de chave estrangeira.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
class SchemaUpdater implements SchemaUpdaterInterface
{
    public function __construct(
        private ColumnBackupInterface      $columnBackup,
        private ColumnManagerInterface     $columnManager,
        private VersionManagerInterface    $versionManager,
        private SchemaInterpreterInterface $schemaInterpreter,
    )
    {
    }
    
    public function updateDatabaseSchema(string $modelClass): void
    {
        try {
            Log::info("Starting database schema update for model: $modelClass");
            $reflection = new ReflectionClass($modelClass);
            $traits = $reflection->getTraitNames();
            
            if (!$this->shouldUpdateSchema($modelClass, $traits)) {
                return;
            }
            
            $schema = (new $modelClass)->getSchema();
            if (empty($schema)) {
                Log::warning("Schema is empty for model: $modelClass");
                return;
            }
            
            $tableName = Str::plural(Str::snake(class_basename($modelClass)));
            $existingColumns = $this->getExistingColumns($tableName);
            
            $this->backupAndDropOldColumns($tableName, $schema, $existingColumns);
            $this->updateTableSchema($tableName, $schema, $existingColumns, $modelClass);
            
            Log::info("Database schema update completed for model: $modelClass");
        } catch ( Exception $e ) {
            Log::error("Error updating database schema for model: $modelClass - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function shouldUpdateSchema(string $modelClass, array $traits): bool
    {
        if (defined("$modelClass::SCHEME_SYNC_ACTIVED") && !$modelClass::SCHEME_SYNC_ACTIVED) {
            Log::info("Sync is disabled for model: $modelClass");
            return FALSE;
        }
        
        $reflection = new ReflectionClass($modelClass);
        if ($reflection->getParentClass() && $reflection->getParentClass()->getName() === 'App\\Models\\User') {
            return FALSE;
        }
        
        return $this->usesUserSchemeTrait($traits);
    }
    
    private function usesUserSchemeTrait(array $traits): bool
    {
        foreach ($traits as $trait) {
            if (Str::endsWith($trait, 'UserSchemeTrait')) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    private function getExistingColumns(string $tableName): array
    {
        try {
            Log::info("Fetching existing columns for table: $tableName");
            $columns = DB::select("SHOW COLUMNS FROM $tableName");
            return array_map(
                static function ($column)
                {
                    return $column->Field;
                },
                $columns,
            );
        } catch ( Exception $e ) {
            Log::error("Error fetching existing columns for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function backupAndDropOldColumns(string $tableName, array $schema, array $existingColumns): void
    {
        try {
            Log::info("Backing up and dropping old columns for table: $tableName");
            $schemaColumns = array_keys($schema);
            $columnsToDrop = array_diff($existingColumns, $schemaColumns);
            
            foreach ($columnsToDrop as $column) {
                $this->columnBackup->backupColumnData($tableName, $column);
                Schema::table(
                    $tableName,
                    function (Blueprint $table) use ($column)
                    {
                        $table->dropColumn($column);
                    },
                );
            }
        } catch ( Exception $e ) {
            Log::error("Error backing up and dropping old columns for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function updateTableSchema(string $tableName, array $schema, array $existingColumns, string $modelClass): void
    {
        try {
            Log::info("Updating table schema for table: $tableName");
            $definitionBuilder = new SchemaDefinitionBuilder();
            
            $editColumnsEnabled = defined("$modelClass::SCHEME_SYNC_EDIT_ACTIVED") && $modelClass::SCHEME_SYNC_EDIT_ACTIVED;
            
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($schema, $definitionBuilder, $existingColumns, $tableName, $editColumnsEnabled)
                {
                    uasort(
                        $schema,
                        static function ($item1, $item2)
                        {
                            return ($item1[ ESchemaKey::POSITION ] ?? 0) <=> ($item2[ ESchemaKey::POSITION ] ?? 0);
                        },
                    );
                    
                    foreach ($schema as $column => $definition) {
                        $definitionString = $definitionBuilder->buildColumnDefinition($definition);
                        $foreignKey = $definitionBuilder->buildForeignKeyDefinition($definition, $column);
                        
                        if (in_array($column, $existingColumns)) {
                            if ($editColumnsEnabled && !$this->shouldSkipColumnUpdate($tableName, $column, $definition)) {
                                $this->columnManager->updateColumn($table, $column, $definitionString, $definition);
                            }
                        }
                        else {
                            $this->columnManager->addColumn($table, $column, $definitionString, $definition);
                        }
                        
                        if ($foreignKey) {
                            try {
                                $this->addForeignKey($table, $column, $foreignKey);
                            } catch ( QueryException $e ) {
                                Log::error("Failed to add foreign key on table $tableName for column $column: " . $e->getMessage());
                                $this->handleForeignKeyException($e, $tableName, $column, $foreignKey);
                            }
                        }
                    }
                },
            );
        } catch ( Exception $e ) {
            Log::error("Error updating table schema for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function shouldSkipColumnUpdate(string $tableName, string $column, array $definition): bool
    {
        try {
            if (!$this->isSyncPausedColumn($definition)) {
                return TRUE;
            }
            
            $existingDefinition = DB::select("SHOW COLUMNS FROM $tableName LIKE '$column'");
            if (empty($existingDefinition)) {
                return FALSE;
            }
            
            $existingDefinition = $existingDefinition[0];
            $newType = $this->schemaInterpreter->getColumnType($definition[ ESchemaKey::TYPE ]);
            
            $currentVersion = $this->versionManager->getCurrentVersion($tableName, $column);
            $schemaVersion = $definition[ ESchemaKey::VERSIONED ] ?? 1;
            
            if ($currentVersion === NULL || $currentVersion < $schemaVersion) {
                $this->versionManager->updateVersion($tableName, $column, $schemaVersion);
                return FALSE;
            }
            
            return $existingDefinition->Type === $newType;
        } catch ( Exception $e ) {
            Log::error("Error checking if column update should be skipped for table: $tableName, column: $column - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function foreignKeyCanBeAdded(string $referencedTable, string $referencedColumn, string $currentTable, string $currentColumn): bool
    {
        // Verificar se a tabela referenciada e a coluna existem
        if (!Schema::hasTable($referencedTable) || !Schema::hasColumn($referencedTable, $referencedColumn)) {
            Log::error("Referenced table `$referencedTable` or column `$referencedColumn` does not exist.");
            return false;
        }
        
        // Verificar se a tabela atual e a coluna existem
        if (!Schema::hasTable($currentTable) || !Schema::hasColumn($currentTable, $currentColumn)) {
            Log::error("Current table `$currentTable` or column `$currentColumn` does not exist.");
            return false;
        }
        
        // Verificar compatibilidade dos tipos de dados
        $referencedColumnData = DB::select("SHOW COLUMNS FROM $referencedTable LIKE '$referencedColumn'");
        $currentColumnData = DB::select("SHOW COLUMNS FROM $currentTable LIKE '$currentColumn'");
        
        if (empty($referencedColumnData) || empty($currentColumnData)) {
            Log::error("Column data for referenced column `$referencedColumn` or current column `$currentColumn` could not be fetched.");
            return false;
        }
        
        $referencedColumnType = $referencedColumnData[0]->Type;
        $currentColumnType = $currentColumnData[0]->Type;
        
        Log::info("Referenced column type: $referencedColumnType, Current column type: $currentColumnType");
        
        if ($referencedColumnType !== $currentColumnType) {
            Log::error("Incompatible data types between referenced column `$referencedColumn` ($referencedColumnType) and column `$currentColumn` ($currentColumnType).");
            return false;
        }
        
        return true;
    }
    
    
    private function addForeignKey(Blueprint $table, string $column, array $foreignKey): void
    {
        try {
            if (!$this->foreignKeyCanBeAdded($foreignKey['table'], $foreignKey['references'], $table->getTable(), $column)) {
                throw new Exception("Cannot add foreign key on column `$column`. Referenced table or column does not exist or has incompatible type.");
            }
            
            Log::info("Adding foreign key on column: $column referencing table: {$foreignKey['table']} ({$foreignKey['references']}) with on delete action: {$foreignKey['on_delete']}");
            
            // Adicionar valor padrão para "on_update" se não estiver definido
            $foreignKey['on_update'] = $foreignKey['on_update'] ?? 'NO ACTION';
            
            $foreignKeyName = $table->getTable() . '_' . $column . '_foreign';
            
            // Verificar se a chave estrangeira já existe
            $foreignKeys = DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$table->getTable()}' AND COLUMN_NAME = '{$column}'",
            );
            
            foreach ($foreignKeys as $key) {
                if ($key->CONSTRAINT_NAME === $foreignKeyName) {
                    Log::info("Foreign key already exists on column: $column. Dropping the existing foreign key: $foreignKeyName.");
                    Schema::table(
                        $table->getTable(),
                        function (Blueprint $table) use ($foreignKeyName)
                        {
                            $table->dropForeign($foreignKeyName);
                        },
                    );
                }
            }
            
            $table->foreign($column)
                  ->references($foreignKey['references'])
                  ->on($foreignKey['table'])
                  ->onDelete($foreignKey['on_delete'])
                  ->onUpdate($foreignKey['on_update']);
        } catch ( Exception $exception ) {
            Log::error("Error adding foreign key on column: $column - " . $exception->getMessage());
            throw $exception;
        }
    }
    
    
    private function handleForeignKeyException(QueryException $e, string $tableName, string $column, array $foreignKey): void
    {
        $errorMessage = "Error adding foreign key on table `$tableName` for column `$column` referencing table `{$foreignKey['table']}` (`{$foreignKey['references']}`) with on delete action `{$foreignKey['on_delete']}`. ";
        $errorMessage .= "Original error: {$e->getMessage()}";
        Log::error($errorMessage);
        throw new Exception($errorMessage, intval($e->getCode()), $e);
    }
    
    private function isSyncPausedColumn(array $definition): bool
    {
        return isset($definition[ ESchemaKey::SYNC_PAUSED ]) && $definition[ ESchemaKey::SYNC_PAUSED ];
    }
}
