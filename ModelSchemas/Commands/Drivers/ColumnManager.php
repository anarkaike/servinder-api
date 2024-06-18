<?php

namespace ModelSchemas\Commands\Drivers;

use App\Helpers\Logger;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ModelSchemas\Commands\Builders\SchemaDefinitionBuilder;
use ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;
use function config;
use function count;
use function dd;
use function str_replace;

class ColumnManager implements ColumnManagerInterface
{
    protected SchemaInterpreterInterface $schemaInterpreter;
    private Logger                       $logger;
    
    public function __construct(SchemaInterpreterInterface $schemaInterpreter, Logger $logger)
    {
        $this->schemaInterpreter = $schemaInterpreter;
        $this->logger = $logger;
    }
    
    public function updateColumn(Blueprint $table, string $columnName, string $columnDefinition, array $columnSchema): void
    {
        try {
            $this->logColumnUpdateStart($table, $columnName, $columnDefinition);
            $columnType = $columnSchema[ ESchemaKey::TYPE ];
            
            if ($columnType === EColumnType::INCREMENTS) {
                $this->handleAutoIncrementColumn($table, $columnName);
            }
            else {
                $this->modifyColumn($table, $columnName, $columnSchema);
            }
        } catch ( Exception $exception ) {
            $this->logColumnUpdateError($table, $columnName, $exception);
            throw $exception;
        }
    }
    
    public function addColumn(Blueprint $table, string $columnName, string $columnDefinition, array $columnSchema, ?string $afterColumn = NULL): void
    {
        try {
            $this->logColumnAddStart($table, $columnName, $columnDefinition);
            $columnType = $columnSchema[ ESchemaKey::TYPE ];
            
            if ($afterColumn) {
                $this->schemaInterpreter->applyColumnType($table, $columnName, $columnType, $columnSchema, $afterColumn);
            }
            else {
                $this->schemaInterpreter->applyColumnType($table, $columnName, $columnType, $columnSchema);
            }
        } catch ( Exception $exception ) {
            $this->logColumnAddError($table, $columnName, $exception);
            throw $exception;
        }
    }
    
    public function addForeignKeysToTables(array $schemas): void
    {
        foreach ($schemas as $tableName => $schema) {
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($schema, $tableName)
                {
                    $definitionBuilder = new SchemaDefinitionBuilder();
                    foreach ($schema as $column => $definition) {
                        if (!isset($definition[ESchemaKey::ON])) {
                            continue; // Ignorar colunas sem FKs
                        }
                        
                        $foreignKey = $definitionBuilder->buildForeignKeyDefinition($definition, $column);
                        $existingForeignKey = $this->getExistingForeignKey($tableName, $column);
                        
                        if (!$existingForeignKey && $this->foreignKeyCanBeAdded(
                                $foreignKey['foreign_table'],
                                $foreignKey['references'],
                                $tableName,
                                $column
                            )) {
                            $this->addForeignKey($table, $column, $foreignKey);
                        } else {
                            $this->logger->log("Foreign key on column $column in table $tableName cannot be added or already exists. Skipping addition.");
                        }
                    }
                },
            );
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
            
            $referencedColumnType = DB::selectOne("SHOW FIELDS FROM $referencedTable WHERE Field = ?", [$referencedColumn]);
            $currentColumnType = DB::selectOne("SHOW FIELDS FROM $currentTable WHERE Field = ?", [$currentColumn]);
            
            if ($referencedColumnType->Type !== $currentColumnType->Type) {
                $this->logger->log(
                    "Column type mismatch: $referencedTable.$referencedColumn is of type {$referencedColumnType->Type}, but $currentTable.$currentColumn is of type {$currentColumnType->Type}",
                );
                return FALSE;
            }
            
            return TRUE;
        } catch ( Exception $e ) {
            $this->logger->error("Error checking if foreign key can be added between tables $currentTable and $referencedTable - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function addForeignKey(Blueprint $table, string $column, array $foreignKey): void
    {
        try {
            // Check if the foreign key already exists
            $existingForeignKey = $this->getExistingForeignKey($table->getTable(), $column);
            if ($existingForeignKey) {
                $this->logger->log("Foreign key on column $column in table {$table->getTable()} already exists. Skipping addition.");
                return;
            }
//            if ($table->getTable() === 'users' && $column === 'parent_id') {
//                dd($existingForeignKey);
//            }
            
            // Add the new foreign key
            $table->foreign($column)
                  ->references($foreignKey['references'])
                  ->on($foreignKey['foreign_table'])
                  ->onUpdate($foreignKey['onUpdate'] ?? 'NO ACTION')
                  ->onDelete($foreignKey['onDelete']);
        } catch ( Exception $exception ) {
            $this->logger->error("Error adding foreign key on column: $column - " . $exception->getMessage());
            throw $exception;
        }
    }
    
    private function getExistingForeignKey(string $table, string $column): ?string
    {
        $foreignKeys = DB::select(
            'SELECT constraint_name FROM information_schema.key_column_usage
         WHERE table_name = ? AND column_name = ? AND constraint_schema = ?',
            [$table, $column, config('database.connections.mysql.database')],
        );
        return $foreignKeys[0]->constraint_name ?? NULL;
    }
    
    public function dropForeignKey(string $tableName, string $columnName): void
    {
        $foreignKey = DB::selectOne(
            'SELECT constraint_name FROM information_schema.key_column_usage WHERE table_name = ? AND column_name = ? AND constraint_schema = ?',
            [$tableName, $columnName, config('database.connections.mysql.database')],
        );
        
        if ($foreignKey) {
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($foreignKey, $tableName, $columnName)
                {
                    if ($foreignKey->constraint_name === 'PRIMARY') {
                        // Avoid dropping primary key if it's the only auto-increment column
                        if ($this->isAutoIncrementColumn($tableName, $columnName)) {
                            return;
                        }
                        $table->dropPrimary([$columnName]);
                    }
                    else {
                        $table->dropForeign([$columnName]);
                    }
                },
            );
        }
    }
    
    private function isAutoIncrementColumn(string $tableName, string $columnName): bool
    {
        $column = DB::selectOne(
            'SHOW COLUMNS FROM ' . $tableName . ' WHERE Field = ?',
            [$columnName],
        );
        return strpos($column->Extra, 'auto_increment') !== FALSE;
    }
    
    public function hasForeignKey(string $tableName, string $columnName): bool
    {
        $foreignKeys = DB::select(
            'SELECT constraint_name FROM information_schema.key_column_usage WHERE table_name = ? AND column_name = ? AND constraint_schema = ?',
            [$tableName, $columnName, config('database.connections.mysql.database')],
        );
        return count($foreignKeys) > 0;
    }
    
    protected function logColumnUpdateStart(Blueprint $table, string $columnName, string $columnDefinition): void
    {
        $this->logger->log("Updating column: $columnName in table: " . $table->getTable() . " with definition: $columnDefinition");
    }
    
    protected function logColumnAddStart(Blueprint $table, string $columnName, string $columnDefinition): void
    {
        $this->logger->log("Adding column: $columnName to table: " . $table->getTable() . " with definition: $columnDefinition");
    }
    
    protected function logColumnUpdateError(Blueprint $table, string $columnName, Exception $exception): void
    {
        $this->logger->error("Error updating column: $columnName in table: " . $table->getTable() . ' - ' . $exception->getMessage());
    }
    
    protected function logColumnAddError(Blueprint $table, string $columnName, Exception $exception): void
    {
        $this->logger->error("Error adding column: $columnName to table: " . $table->getTable() . ' - ' . $exception->getMessage());
    }
    
    protected function handleAutoIncrementColumn(Blueprint $table, string $columnName): void
    {
        $existingPrimaryKeys = DB::select("SHOW KEYS FROM {$table->getTable()} WHERE Key_name = 'PRIMARY'");
        if (count($existingPrimaryKeys) === 0) {
            DB::statement("ALTER TABLE {$table->getTable()} MODIFY COLUMN $columnName INT AUTO_INCREMENT PRIMARY KEY");
        }
        else {
            DB::statement("ALTER TABLE {$table->getTable()} MODIFY COLUMN $columnName INT AUTO_INCREMENT");
        }
    }
    
    protected function modifyColumn(Blueprint $table, string $columnName, array $columnSchema): void
    {
        $columnDefinitionString = $this->schemaInterpreter->buildColumnDefinitionString($columnSchema);
        DB::statement("ALTER TABLE {$table->getTable()} MODIFY COLUMN $columnName $columnDefinitionString");
    }
    
    public function restoreColumnDataIfNeeded(string $tableName, string $columnName): void
    {
        try {
            $backupData = DB::table('tables_infos')
                            ->where('table_name', $tableName)
                            ->where('column_name', $columnName)
                            ->where('type', 'backup')
                            ->value('data');
            
            if ($backupData) {
                $backupDataArray = json_decode($backupData, TRUE);
                if (is_array($backupDataArray)) {
                    foreach ($backupDataArray as $id => $value) {
                        DB::table($tableName)->where('id', $id)->update([$columnName => $value]);
                    }
                    $this->logger->log("Restored data for column: $columnName in table: $tableName from backup.");
                }
                else {
                    $this->logger->log("Backup data for column: $columnName in table: $tableName is not a valid JSON array.");
                }
            }
            else {
                $this->logger->log("No backup data found for column: $columnName in table: $tableName.");
            }
        } catch ( Exception $e ) {
            $this->logger->error("Error restoring data for column: $columnName in table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function getExistingColumns(string $tableName): array
    {
        $columns = DB::select("SHOW COLUMNS FROM $tableName");
        return array_map(
            static function ($column)
            {
                return $column->Field;
            },
            $columns,
        );
    }
}
