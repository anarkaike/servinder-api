<?php

namespace App\ModelSchemas\Commands\Updaters;

use App\ModelSchemas\Commands\Builders\SchemaDefinitionBuilder;
use App\ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use App\ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use App\ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use App\ModelSchemas\Commands\Contracts\SchemaUpdaterInterface;
use App\ModelSchemas\Commands\Contracts\VersionManagerInterface;
use App\ModelSchemas\Enums\ESchemaKey;
use App\Helpers\Logger;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ReflectionClass;
use function defined;

class SchemaSynchronizer implements SchemaUpdaterInterface
{
    private ColumnBackupInterface      $columnBackup;
    private ColumnManagerInterface     $columnManager;
    private VersionManagerInterface    $versionManager;
    private SchemaInterpreterInterface $schemaInterpreter;
    private Logger                     $logger;
    
    public function __construct(
        ColumnBackupInterface      $columnBackup,
        ColumnManagerInterface     $columnManager,
        VersionManagerInterface    $versionManager,
        SchemaInterpreterInterface $schemaInterpreter,
        Logger                     $logger,
    )
    {
        $this->columnBackup = $columnBackup;
        $this->columnManager = $columnManager;
        $this->versionManager = $versionManager;
        $this->schemaInterpreter = $schemaInterpreter;
        $this->logger = $logger;
    }
    
    public function updateDatabaseSchema(string $modelClass): void
    {
        try {
            $this->logger->log("Starting database schema update for model: $modelClass");
            $reflection = new ReflectionClass($modelClass);
            $traits = $reflection->getTraitNames();
            
            if (!$this->shouldUpdateSchema($modelClass, $traits)) {
                $this->logger->log("Skipping schema update for model: $modelClass");
                return;
            }
            
            $modelInstance = new $modelClass;
            if (!method_exists($modelInstance, 'getSchema')) {
                $this->logger->log("Method getSchema does not exist for model: $modelClass");
                return;
            }
            
            $schema = $modelInstance->getSchema();
            if (empty($schema)) {
                $this->logger->log("Schema is empty for model: $modelClass");
                return;
            }
            
            $tableName = Str::plural(Str::snake(class_basename($modelClass)));
            $this->logger->log("Determined table name: $tableName for model: $modelClass");
            
            $existingColumns = $this->getExistingColumns($tableName);
            $this->verifyAndAdjustColumnOrder($modelClass, $tableName, $schema, $existingColumns);
            $this->updateTableSchema($tableName, $schema, $modelClass);
        } catch ( Exception $e ) {
            $this->logger->error("Error updating database schema for model: $modelClass - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function shouldUpdateSchema(string $modelClass, array $traits): bool
    {
        if (defined("$modelClass::SCHEME_SYNC_ACTIVED") && !$modelClass::SCHEME_SYNC_ACTIVED) {
            $this->logger->log("Sync is disabled for model: $modelClass");
            return FALSE;
        }
        
        if (method_exists($modelClass, 'getParentClass') && $modelClass::getParentClass()->getName() === 'App\\Models\\User') {
            return FALSE;
        }
        
        return $this->usesSchemeTrait($traits);
    }
    
    private function usesSchemeTrait(array $traits): bool
    {
        foreach ($traits as $trait) {
            if (Str::endsWith($trait, 'SchemeTrait')) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    private function getExistingColumns(string $tableName): array
    {
        try {
            if (!Schema::hasTable($tableName)) {
                $this->logger->log("Table $tableName does not exist");
                return [];
            }
            
            $this->logger->log("Fetching existing columns for table: $tableName");
            $columns = DB::select("SHOW COLUMNS FROM $tableName");
            return array_map(
                static function ($column)
                {
                    return $column->Field;
                },
                $columns,
            );
        } catch ( Exception $e ) {
            $this->logger->error("Error fetching existing columns for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function backupAndDropOldColumns(string $tableName, array $schema, array $existingColumns): void
    {
        try {
            $this->logger->log("Backing up and dropping old columns for table: $tableName");
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
            $this->logger->error("Error backing up and dropping old columns for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function updateTableSchema(string $tableName, array $schema, string $modelClass): void
    {
        try {
            $this->logger->log("Updating table schema for table: $tableName");
            
            if (Schema::hasTable($tableName)) {
                $this->logger->log("Table $tableName already exists. Skipping creation.");
                return;
            }
            
            $definitionBuilder = new SchemaDefinitionBuilder();
            
            $existingColumns = $this->getExistingColumns($tableName);
            
            $backupTableName = $tableName . '_backup_' . Str::random(5);
            Schema::rename($tableName, $backupTableName);
            
            DB::beginTransaction();
            
            Schema::create(
                $tableName,
                function (Blueprint $table) use ($tableName, $existingColumns, $schema, $definitionBuilder)
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
                        
                        if ($definition[ ESchemaKey::PRIMARY_KEY ] ?? FALSE) {
                            $table->primary($column);
                        }
                        
                        if ($column === 'id') {
                            $table->increments('id');
                            continue;
                        }
                        
                        if ($definition[ ESchemaKey::AUTO_INCREMENT ] ?? FALSE) {
                            $table->increments($column);
                            continue;
                        }
                        
                        if (in_array($column, $existingColumns)) {
                            $existingDefinition = DB::selectOne("SHOW COLUMNS FROM $tableName LIKE '$column'");
                            $isPrimaryKey = $existingDefinition->Key === 'PRI';
                            $isAutoIncrement = Str::contains($existingDefinition->Extra, 'auto_increment');
                            
                            if (!$isPrimaryKey || !$isAutoIncrement) {
                                $this->logger->error("Column $column in table $tableName is not a primary key or auto increment. Updating schema.");
                                
                                Schema::table(
                                    $tableName,
                                    function (Blueprint $table) use ($column)
                                    {
                                        $table->dropColumn($column);
                                        $table->increments($column);
                                    },
                                );
                            }
                            
                            if ($this->shouldSkipColumnUpdate($tableName, $column, $definition)) {
                                continue;
                            }
                            
                            $this->columnManager->updateColumn($table, $column, $definitionString, $definition);
                        }
                        else {
                            $afterColumn = $definition[ ESchemaKey::AFTER ] ?? NULL;
                            $this->columnManager->addColumn($table, $column, $definitionString, $definition, $afterColumn);
                        }
                    }
                },
            );
            
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($schema, $definitionBuilder, $tableName)
                {
                    foreach ($schema as $column => $definition) {
                        $foreignKey = $definitionBuilder->buildForeignKeyDefinition($definition, $column);
                        if ($foreignKey) {
                            try {
                                $this->addForeignKey($table, $column, $foreignKey);
                            } catch ( QueryException $e ) {
                                $this->logger->error("Failed to add foreign key on table $tableName for column $column: " . $e->getMessage());
                                $this->handleForeignKeyException($e, $tableName, $column, $foreignKey);
                            }
                        }
                    }
                },
            );
            
            DB::commit();
            Schema::drop($backupTableName);
        } catch ( Exception $e ) {
            DB::rollBack();
            $this->logger->error("Error updating table schema for table: $tableName - " . $e->getMessage());
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
            $this->logger->error("Error checking if column update should be skipped for table: $tableName, column: $column - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function verifyAndAdjustColumnOrder(string $modelClass, string $tableName, array $schema, array $existingColumns)
    {
        if (!defined("$modelClass::ALLOW_RECREATE_TABLE_IN_ORDER") || !$modelClass::ALLOW_RECREATE_TABLE_IN_ORDER) {
            $this->logger->log("Recreating table in order is not allowed. Skipping column order adjustment for table: $tableName");
            return;
        }
        
        $this->logger->log("Verifying and adjusting column order for table: $tableName");
        
        $schemaColumns = array_keys($schema);
        
        if ($existingColumns === $schemaColumns) {
            $this->logger->log("Column order is correct for table: $tableName");
            return;
        }
        
        $this->logger->log("Column order is incorrect for table: $tableName. Adjusting...");
        
        $tempTableName = $tableName . '_temp_' . Str::random(5);
        
        Schema::create(
            $tempTableName,
            function (Blueprint $table) use ($schema)
            {
                foreach ($schema as $column => $definition) {
                    $this->schemaInterpreter->applyColumnType($table, $column, $definition[ ESchemaKey::TYPE ], $definition);
                }
            },
        );
        
        if (Schema::hasTable($tableName)) {
            $backupTableName = $tableName . '_backup_' . Str::random(5);
            Schema::rename($tableName, $backupTableName);
            Schema::rename($tempTableName, $tableName);
            Schema::drop($backupTableName);
        }
        else {
            Schema::rename($tempTableName, $tableName);
        }
    }
    
    private function foreignKeyCanBeAdded(string $referencedTable, string $referencedColumn, string $currentTable, string $currentColumn): bool
    {
        try {
            if (!Schema::hasTable($referencedTable)) {
                $this->logger->log("Referenced table $referencedTable does not exist");
                return FALSE;
            }
            
            $referencedColumns = $this->getExistingColumns($referencedTable);
            if (!in_array($referencedColumn, $referencedColumns)) {
                $this->logger->log("Referenced column $referencedColumn does not exist in table $referencedTable");
                return FALSE;
            }
            
            $currentColumns = $this->getExistingColumns($currentTable);
            if (!in_array($currentColumn, $currentColumns)) {
                $this->logger->log("Current column $currentColumn does not exist in table $currentTable");
                return FALSE;
            }
            
            return TRUE;
        } catch ( Exception $e ) {
            $this->logger->error("Error checking if foreign key can be added between tables $currentTable and $referencedTable - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function getExistingForeignKeys(string $tableName): array
    {
        try {
            $foreignKeys = DB::select(
                "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = '$tableName' AND CONSTRAINT_SCHEMA = '" . config('database.connections.mysql.database') . "'",
            );
            
            return array_map(
                static function ($foreignKey)
                {
                    return [
                        'REFERENCED_TABLE_NAME'  => $foreignKey->REFERENCED_TABLE_NAME,
                        'REFERENCED_COLUMN_NAME' => $foreignKey->REFERENCED_COLUMN_NAME,
                    ];
                },
                $foreignKeys,
            );
        } catch ( Exception $e ) {
            $this->logger->error("Error fetching existing foreign keys for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function addForeignKey(Blueprint $table, string $column, array $foreignKey): void
    {
        try {
            $this->logger->log("Attempting to add foreign key on column: $column referencing table: {$foreignKey['table']} ({$foreignKey['references']})");
            
            if (!$this->foreignKeyCanBeAdded($foreignKey['table'], $foreignKey['references'], $table->getTable(), $column)) {
                throw new Exception("Cannot add foreign key on column `$column`. Referenced table or column does not exist or has incompatible type.");
            }
            
            $this->logger->log("Adding foreign key on column: $column referencing table: {$foreignKey['table']} ({$foreignKey['references']}) with on delete action: {$foreignKey['on_delete']}");
            
            $foreignKey['on_update'] = $foreignKey['on_update'] ?? 'NO ACTION';
            $foreignKeyName = $table->getTable() . '_' . $column . '_foreign';
            
            $foreignKeys = DB::select(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '{$table->getTable()}' AND COLUMN_NAME = '{$column}'",
            );
            
            foreach ($foreignKeys as $key) {
                if ($key->CONSTRAINT_NAME === $foreignKeyName) {
                    $this->logger->log("Foreign key already exists on column: $column. Dropping the existing foreign key: $foreignKeyName.");
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
            $this->logger->error("Error adding foreign key on column: $column - " . $exception->getMessage());
            throw $exception;
        }
    }
    
    private function handleForeignKeyException(QueryException $e, string $tableName, string $column, array $foreignKey): void
    {
        $errorMessage = "Error adding foreign key on table `$tableName` for column `$column` referencing table `{$foreignKey['table']}` (`{$foreignKey['references']}`) with on delete action `{$foreignKey['on_delete']}`. ";
        $errorMessage .= "Original error: {$e->getMessage()}";
        $this->logger->error($errorMessage);
        throw new Exception($errorMessage, intval($e->getCode()), $e);
    }
    
    private function isSyncPausedColumn(array $definition): bool
    {
        return isset($definition[ ESchemaKey::SYNC_PAUSED ]) && $definition[ ESchemaKey::SYNC_PAUSED ];
    }
}
