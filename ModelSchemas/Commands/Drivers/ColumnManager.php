<?php

namespace ModelSchemas\Commands\Drivers;

use App\Helpers\Logger;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;

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
            
            // Restore column data if there's a backup
            $this->restoreColumnDataIfNeeded($table->getTable(), $columnName);
        } catch ( Exception $exception ) {
            $this->logColumnAddError($table, $columnName, $exception);
            throw $exception;
        }
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
                $backupData = json_decode($backupData, TRUE);
                foreach ($backupData as $id => $value) {
                    DB::table($tableName)->where('id', $id)->update([$columnName => $value]);
                }
                $this->logger->log("Restored data for column: $columnName in table: $tableName from backup.");
            }
            else {
                $this->logger->log("No backup data found for column: $columnName in table: $tableName.");
            }
        } catch ( Exception $e ) {
            $this->logger->error("Error restoring data for column: $columnName in table: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
}
