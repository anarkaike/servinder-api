<?php

namespace App\ModelSchemas\Commands\Drivers;

use App\ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use App\ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use App\ModelSchemas\Enums\EColumnType;
use App\ModelSchemas\Enums\ESchemaKey;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Gerenciador de Colunas
 *
 * Português
 * Classe responsável por lidar com a atualização e adição de colunas em tabelas de banco de dados.
 * Implementa a interface ColumnManagerInterface para fornecer métodos específicos para essas operações.
 *
 * Espanhol
 * Clase encargada de manejar la actualización y adición de columnas en tablas de base de datos.
 * Implementa la interfaz ColumnManagerInterface para proporcionar métodos específicos para estas operaciones.
 *
 * Inglês
 * Class responsible for handling column updates and additions in database tables.
 * Implements the ColumnManagerInterface to provide specific methods for these operations.
 *
 * Principais objetivos:
 * - Fornecer uma interface unificada para lidar com a atualização e adição de colunas em tabelas de banco de dados.
 * - Implementar a lógica necessária para lidar com diferentes tipos de colunas e suas características específicas.
 * - Manter a consistência e a integridade dos dados durante as operações de atualização e adição de colunas.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
class ColumnManager implements ColumnManagerInterface
{
    protected SchemaInterpreterInterface $schemaInterpreter;
    
    public function __construct(SchemaInterpreterInterface $schemaInterpreter)
    {
        $this->schemaInterpreter = $schemaInterpreter;
    }
    
    /**
     * Atualiza uma coluna existente em uma tabela.
     *
     * Português
     * Atualiza a definição de uma coluna existente em uma tabela de banco de dados.
     *
     * Espanhol
     * Actualiza la definición de una columna existente en una tabla de base de datos.
     *
     * Inglês
     * Updates the definition of an existing column in a database table.
     *
     * @param Blueprint $table
     * @param string $columnName
     * @param string $columnDefinition
     * @param array $columnSchema
     * @throws Exception
     */
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
    
    /**
     * Adiciona uma nova coluna a uma tabela.
     *
     * Português
     * Adiciona uma nova coluna a uma tabela de banco de dados com base em uma definição de esquema.
     *
     * Espanhol
     * Añade una nueva columna a una tabla de base de datos basada en una definición de esquema.
     *
     * Inglês
     * Adds a new column to a database table based on a schema definition.
     *
     * @param Blueprint $table
     * @param string $columnName
     * @param string $columnDefinition
     * @param array $columnSchema
     * @throws Exception
     */
    public function addColumn(Blueprint $table, string $columnName, string $columnDefinition, array $columnSchema): void
    {
        try {
            $this->logColumnAddStart($table, $columnName, $columnDefinition);
            $columnType = $columnSchema[ESchemaKey::TYPE];
            $this->schemaInterpreter->applyColumnType($table, $columnName, $columnType, $columnSchema);
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
