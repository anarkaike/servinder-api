<?php

namespace ModelSchemas\Commands\Updaters;

use App\Helpers\Logger;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ModelSchemas\Commands\Builders\SchemaDefinitionBuilder;
use ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use ModelSchemas\Commands\Contracts\SchemaUpdaterInterface;
use ModelSchemas\Commands\Contracts\VersionManagerInterface;
use ModelSchemas\Enums\ESchemaKey;
use ReflectionClass;

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
    
    
    public function updateDatabaseSchema(string $modelClass, $recursive = FALSE): void
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
            
            if (!Schema::hasTable($tableName)) {
                $this->logger->log("Table $tableName does not exist. Creating table.");
                $this->createTablesWithoutFKs([$tableName => $schema]);
            }
            
            $existingColumns = $this->getExistingColumns($tableName);
            $this->backupAndDropOldColumns($tableName, $schema, $existingColumns);
            $this->addNewColumns($tableName, $schema, $existingColumns);
            
            // Adicionar chaves estrangeiras após criar todas as tabelas
            $this->columnManager->addForeignKeysToTables([$tableName => $schema]);
            
            // Ensure columns are in the correct order as per the schema
            $this->ensureCorrectColumnOrder($tableName, $schema);
        } catch ( Exception $e ) {
            $this->logger->error("Error updating database schema for model: $modelClass - " . $e->getMessage());
            throw $e;
        }
    }
    
    
    protected function ensureCorrectColumnOrder(string $tableName, array $schema): void
    {
        $this->logger->log("Ensuring correct column order for table: $tableName");
        
        $orderedColumns = array_keys($schema);
        $columnPositions = [];
        
        // Extrair posições das colunas a partir do esquema
        foreach ($orderedColumns as $column) {
            if (isset($schema[ $column ][ ESchemaKey::POSITION ])) {
                $columnPositions[ $column ] = $schema[ $column ][ ESchemaKey::POSITION ];
            }
        }
        
        // Ordenar colunas pela posição em ordem crescente
        asort($columnPositions);
        
        // Mover a coluna `id` para ser a primeira coluna
        if (isset($columnPositions['id'])) {
            unset($columnPositions['id']);
            $columnPositions = ['id' => 0] + $columnPositions;
        }
        
        // Inicializar SchemaDefinitionBuilder
        $schemaDefinitionBuilder = new SchemaDefinitionBuilder();
        
        // Gerar comandos SQL para alterar a ordem das colunas
        $previousColumn = NULL;
        foreach ($columnPositions as $column => $position) {
            if ($column !== 'id') {
                $definitionString = $schemaDefinitionBuilder->buildColumnDefinition($schema[ $column ]);
                $alterColumnSQL = "ALTER TABLE `$tableName` MODIFY `$column` $definitionString";
                if ($previousColumn) {
                    $alterColumnSQL .= " AFTER `$previousColumn`";
                }
                else {
                    $alterColumnSQL .= ' FIRST';
                }
                
                DB::statement($alterColumnSQL);
                $previousColumn = $column;
            }
        }
    }
    
    private function createTablesWithoutFKs(array $schemas): void
    {
        foreach ($schemas as $tableName => $schema) {
            Schema::create(
                $tableName,
                function (Blueprint $table) use ($schema)
                {
                    $definitionBuilder = new SchemaDefinitionBuilder();
                    foreach ($schema as $column => $definition) {
                        if (isset($definition[ ESchemaKey::ON ])) {
                            continue; // Ignorar colunas com FKs
                        }
                        $definitionString = $definitionBuilder->buildColumnDefinition($definition);
                        $this->columnManager->addColumn($table, $column, $definitionString, $definition);
                    }
                },
            );
        }
    }
    
    private function shouldUpdateSchema(string $modelClass, array $traits): bool
    {
        if (defined("$modelClass::SCHEME_SYNC_ACTIVED") && !$modelClass::SCHEME_SYNC_ACTIVED) {
            $this->logger->log("Sync is disabled for model: $modelClass");
            return FALSE;
        }

//        if (
//            method_exists($modelClass, 'getParentClass') &&
//            ($parentClass = $modelClass::getParentClass()) &&
//            $parentClass->getName() === 'App\\Models\\User'
//        ) {
//            return FALSE;
//        }
        
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
            $this->ensureTableExists($tableName);
            
            $this->logger->log("Fetching existing columns for table: $tableName");
            $columns = DB::select("SHOW COLUMNS FROM $tableName");
            if (empty($columns)) {
                throw new Exception("No columns found for table: $tableName");
            }
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
                // Backup column data
                $this->columnBackup->backupColumnData($tableName, $column);
                
                // Check if the column is used in any foreign keys
                $foreignKeys = DB::select(
                    'SELECT constraint_name, table_name FROM information_schema.key_column_usage
                 WHERE referenced_table_name = ? AND referenced_column_name = ? AND constraint_schema = ?',
                    [$tableName, $column, config('database.connections.mysql.database')],
                );
                
                // Drop foreign keys from other tables that reference this column
                foreach ($foreignKeys as $foreignKey) {
                    Schema::table(
                        $foreignKey->table_name,
                        function (Blueprint $table) use ($foreignKey)
                        {
                            $table->dropForeign([$foreignKey->constraint_name]);
                        },
                    );
                }
                
                // Check if the column is a foreign key in the current table
                if ($this->columnManager->hasForeignKey($tableName, $column)) {
                    $this->columnManager->dropForeignKey($tableName, $column);
                }
                
                // Drop the column
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
    
    private function addNewColumns(string $tableName, array $schema, array $existingColumns): void
    {
        try {
            $this->logger->log("Adding new columns for table: $tableName");
            
            foreach ($schema as $column => $definition) {
                if (!in_array($column, $existingColumns)) {
                    $definitionString = (new SchemaDefinitionBuilder())->buildColumnDefinition($definition);
                    $afterColumn = $definition[ ESchemaKey::AFTER ] ?? NULL;
                    
                    Schema::table(
                        $tableName,
                        function (Blueprint $table) use ($column, $definitionString, $definition, $afterColumn)
                        {
                            $this->columnManager->addColumn($table, $column, $definitionString, $definition, $afterColumn);
                        },
                    );
                }
            }
            
            // Adicionar chaves estrangeiras após criar todas as colunas
            $this->columnManager->addForeignKeysToTables([$tableName => $schema]);
        } catch ( Exception $e ) {
            $this->logger->error("Error adding new columns for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function ensureTableExists(string $tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            $this->logger->log("Table $tableName does not exist. Creating table.");
            Schema::create(
                $tableName,
                function (Blueprint $table)
                {
                    $table->increments('id');
                },
            );
        }
    }
    
    private function findModelByTableName(string $tableName): ?string
    {
        $modelPaths = [
            app_path('Models'),
            base_path('Modules/*/app/Models'),
        ];
        
        foreach ($modelPaths as $path) {
            foreach (glob($path . '/*.php') as $file) {
                $modelClass = $this->getClassFromFile($file);
                if ($modelClass && Str::plural(Str::snake(class_basename($modelClass))) === $tableName) {
                    return $modelClass;
                }
            }
        }
        
        return NULL;
    }
    
    private function getClassFromFile(string $filePath): ?string
    {
        $fileParts = explode('/', $filePath);
        $model = str_replace('.php', '', $fileParts[ count($fileParts) - 1 ]);
        
        return $this->getNamespaceFromFile($filePath) . '\\' . $model;
    }
    
    private function getNamespaceFromFile($filePath)
    {
        $fileContent = file_get_contents($filePath);
        $namespaceRegex = '/namespace\s+([\w\\\]+);/';
        preg_match($namespaceRegex, $fileContent, $matches);
        return $matches[1] ?? NULL;
    }
}
