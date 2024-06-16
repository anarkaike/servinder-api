<?php

namespace App\ModelSchemas\Commands\Drivers;

use App\ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use App\ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use App\ModelSchemas\Enums\ESchemaKey;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Gerenciador de Colunas
 */
class ColumnManager implements ColumnManagerInterface
{
    protected SchemaInterpreterInterface $schemaInterpreter;
    
    public function __construct(SchemaInterpreterInterface $schemaInterpreter)
    {
        $this->schemaInterpreter = $schemaInterpreter;
    }
    
    public function updateColumn(Blueprint $table, string $columnName, string $columnDefinition, array $columnSchema): void
    {
        try {
            $this->logColumnUpdateStart($table, $columnName, $columnDefinition);
            $columnType = $columnSchema[ESchemaKey::TYPE];
            
            if ($columnType === EColumnType::INCREMENTS) {
                $this->handleAutoIncrementColumn($table, $columnName);
            } else {
                $this->modifyColumn($table, $columnName, $columnSchema);
            }
        } catch (Exception $exception) {
            $this->logColumnUpdateError($table, $columnName, $exception);
            throw $exception;
        }
    }
    
    public function addColumn(Blueprint $table, string $columnName, string $columnDefinition, array $columnSchema, ?string $afterColumn = null): void
    {
        try {
            $this->logColumnAddStart($table, $columnName, $columnDefinition);
            $columnType = $columnSchema[ESchemaKey::TYPE];
            
            if ($afterColumn) {
                $this->schemaInterpreter->applyColumnType($table, $columnName, $columnType, $columnSchema, $afterColumn);
            } else {
                $this->schemaInterpreter->applyColumnType($table, $columnName, $columnType, $columnSchema);
            }
        } catch (Exception $exception) {
            $this->logColumnAddError($table, $columnName, $exception);
            throw $exception;
        }
    }
    
    private function logColumnUpdateStart(Blueprint $table, string $columnName, string $columnDefinition): void
    {
        Log::info("Updating column: $columnName in table: " . $table->getTable() . " with definition: $columnDefinition");
    }
    
    private function logColumnAddStart(Blueprint $table, string $columnName, string $columnDefinition): void
    {
        Log::info("Adding column: $columnName to table: " . $table->getTable() . " with definition: $columnDefinition");
    }
    
    private function logColumnUpdateError(Blueprint $table, string $columnName, Exception $exception): void
    {
        Log::error("Error updating column: $columnName in table: " . $table->getTable() . ' - ' . $exception->getMessage());
    }
    
    private function logColumnAddError(Blueprint $table, string $columnName, Exception $exception): void
    {
        Log::error("Error adding column: $columnName to table: " . $table->getTable() . ' - ' . $exception->getMessage());
    }
    
    private function handleAutoIncrementColumn(Blueprint $table, string $columnName): void
    {
        $existingPrimaryKeys = DB::select("SHOW KEYS FROM {$table->getTable()} WHERE Key_name = 'PRIMARY'");
        if (count($existingPrimaryKeys) === 0) {
            DB::statement("ALTER TABLE {$table->getTable()} MODIFY COLUMN $columnName INT AUTO_INCREMENT PRIMARY KEY");
        } else {
            DB::statement("ALTER TABLE {$table->getTable()} MODIFY COLUMN $columnName INT AUTO_INCREMENT");
        }
    }
    
    private function modifyColumn(Blueprint $table, string $columnName, array $columnSchema): void
    {
        $columnDefinitionString = $this->schemaInterpreter->buildColumnDefinitionString($columnSchema);
        DB::statement("ALTER TABLE {$table->getTable()} MODIFY COLUMN $columnName $columnDefinitionString");
    }
}
