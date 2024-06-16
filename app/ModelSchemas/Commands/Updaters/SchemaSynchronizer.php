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
use function defined;

class SchemaSynchronizer implements SchemaUpdaterInterface
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
                Log::info("Skipping schema update for model: $modelClass");
                return;
            }
            
            $modelInstance = new $modelClass;
            if (!method_exists($modelInstance, 'getSchema')) {
                Log::warning("Method getSchema does not exist for model: $modelClass");
                return;
            }
            
            $schema = $modelInstance->getSchema();
            if (empty($schema)) {
                Log::warning("Schema is empty for model: $modelClass");
                return;
            }
            
            $tableName = Str::plural(Str::snake(class_basename($modelClass)));
            Log::info("Determined table name: $tableName for model: $modelClass");
            
            $existingColumns = $this->getExistingColumns($tableName);
            $this->verifyAndAdjustColumnOrder($modelClass, $tableName, $schema, $existingColumns);
            $this->updateTableSchema($tableName, $schema, $modelClass);
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
            // Check if the table already exists
            if (!Schema::hasTable($tableName)) {
                Log::warning("Table $tableName does not exist");
                return [];
            }
            
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
    
    private function updateTableSchema(string $tableName, array $schema, string $modelClass): void
    {
        try {
            Log::info("Updating table schema for table: $tableName");
            
            // Check if the table already exists
            if (Schema::hasTable($tableName)) {
                Log::info("Table $tableName already exists. Skipping creation.");
                return;
            }
            
            $definitionBuilder = new SchemaDefinitionBuilder();
            
            // Fetch existing columns
            $existingColumns = $this->getExistingColumns($tableName);
            
            // Backup the existing data
            $backupTableName = $tableName . '_backup_' . Str::random(5);
            Schema::rename($tableName, $backupTableName);
            
            // Start a transaction
            DB::beginTransaction();
            
            // Create a new table with the updated schema
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
                        
                        if (in_array($column, $existingColumns)) {
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
            
            // Add foreign keys after creating the columns
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
                                Log::error("Failed to add foreign key on table $tableName for column $column: " . $e->getMessage());
                                $this->handleForeignKeyException($e, $tableName, $column, $foreignKey);
                            }
                        }
                    }
                },
            );
            
            // Commit the transaction
            DB::commit();
            
            // Drop the backup table
            Schema::drop($backupTableName);
        } catch ( Exception $e ) {
            // Rollback the transaction in case of any errors
            DB::rollBack();
            
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
    
    private function verifyAndAdjustColumnOrder(string $modelClass, string $tableName, array $schema, array $existingColumns)
    {
        if (!defined("$modelClass::ALLOW_RECREATE_TABLE_IN_ORDER") || !$modelClass::ALLOW_RECREATE_TABLE_IN_ORDER) {
            Log::info("Recreating table in order is not allowed. Skipping column order adjustment for table: $tableName");
            return;
        }
        
        Log::info("Verifying and adjusting column order for table: $tableName");
        
        $schemaColumns = array_keys($schema);
        
        if ($existingColumns === $schemaColumns) {
            Log::info("Column order is correct for table: $tableName");
            return;
        }
        
        Log::info("Column order is incorrect for table: $tableName. Adjusting...");
        
        // Create a temporary table with a unique name
        $tempTableName = $tableName . '_temp_' . Str::random(5);
        
        // Copy data from the original table to the temporary table
        Schema::create(
            $tempTableName,
            function (Blueprint $table) use ($schema)
            {
                foreach ($schema as $column => $definition) {
                    $this->schemaInterpreter->applyColumnType($table, $column, $definition[ ESchemaKey::TYPE ], $definition);
                }
            },
        );
        
        // Check if the original table exists before attempting to drop it
        if (Schema::hasTable($tableName)) {
            // Rename the original table to a backup name
            $backupTableName = $tableName . '_backup_' . Str::random(5);
            Schema::rename($tableName, $backupTableName);
            
            // Rename the temporary table to the original table name
            Schema::rename($tempTableName, $tableName);
            
            // Drop the backup table
            Schema::drop($backupTableName);
        }
        else {
            // If the original table doesn't exist, just rename the temp table to the original name
            Schema::rename($tempTableName, $tableName);
        }
    }
    
    
    private function foreignKeyCanBeAdded(string $referencedTable, string $referencedColumn, string $currentTable, string $currentColumn): bool
    {
        try {
            // Check if the referenced table exists
            if (!Schema::hasTable($referencedTable)) {
                Log::warning("Referenced table $referencedTable does not exist");
                return FALSE;
            }
            
            // Check if the referenced column exists in the referenced table
            $referencedColumns = $this->getExistingColumns($referencedTable);
            if (!in_array($referencedColumn, $referencedColumns)) {
                Log::warning("Referenced column $referencedColumn does not exist in table $referencedTable");
                return FALSE;
            }
            
            // Check if the current column exists in the current table
            $currentColumns = $this->getExistingColumns($currentTable);
            if (!in_array($currentColumn, $currentColumns)) {
                Log::warning("Current column $currentColumn does not exist in table $currentTable");
                return FALSE;
            }
            
            return TRUE;
        } catch ( Exception $e ) {
            Log::error("Error checking if foreign key can be added between tables $currentTable and $referencedTable - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function getExistingForeignKeys(string $tableName): array
    {
        try {
            // Fetch existing foreign keys
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
            Log::error("Error fetching existing foreign keys for table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function addForeignKey(Blueprint $table, string $column, array $foreignKey): void
    {
        try {
            Log::info("Attempting to add foreign key on column: $column referencing table: {$foreignKey['table']} ({$foreignKey['references']})");
            
            if (!$this->foreignKeyCanBeAdded($foreignKey['table'], $foreignKey['references'], $table->getTable(), $column)) {
                throw new Exception("Cannot add foreign key on column `$column`. Referenced table or column does not exist or has incompatible type.");
            }
            
            Log::info("Adding foreign key on column: $column referencing table: {$foreignKey['table']} ({$foreignKey['references']}) with on delete action: {$foreignKey['on_delete']}");
            
            $foreignKey['on_update'] = $foreignKey['on_update'] ?? 'NO ACTION';
            $foreignKeyName = $table->getTable() . '_' . $column . '_foreign';
            
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
