<?php

namespace App\ModelSchemas\Commands\Drivers;

use App\ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Classe de Backup de Colunas
 *
 * Português
 * Esta classe é responsável por realizar backups de dados de colunas em tabelas.
 * Ela implementa a interface ColumnBackupInterface e fornece um método para fazer backup dos dados de uma coluna específica.
 *
 * Espanhol
 * Esta clase es responsable de realizar copias de seguridad de datos de columnas en tablas.
 * Implementa la interfaz ColumnBackupInterface y proporciona un método para hacer copia de seguridad de los datos de una columna específica.
 *
 * Inglês
 * This class is responsible for backing up column data in tables.
 * It implements the ColumnBackupInterface and provides a method to backup the data of a specific column.
 *
 * Principais objetivos:
 * - Realizar backups de dados de colunas em tabelas para preservar a integridade e disponibilidade dos dados.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
class ColumnBackup implements ColumnBackupInterface
{
    /**
     * Realiza backup de dados de uma coluna em uma tabela
     *
     * Português
     * Este método realiza um backup dos dados de uma coluna específica em uma tabela.
     * Ele captura os dados da coluna, verifica a existência da tabela 'tables_infos',
     * cria a tabela se ela não existir, e armazena os dados de backup.
     *
     * Espanhol
     * Este método realiza una copia de seguridad de los datos de una columna específica en una tabla.
     * Captura los datos de la columna, verifica la existencia de la tabla 'tables_infos',
     * crea la tabla si no existe, y almacena los datos de copia de seguridad.
     *
     * Inglês
     * This method performs a backup of the data of a specific column in a table.
     * It captures the column data, checks the existence of the 'tables_infos' table,
     * creates the table if it does not exist, and stores the backup data.
     *
     * @param string $tableName Nome da tabela
     * @param string $column    Nome da coluna
     *
     * @throws Exception Em caso de falha durante o backup
     */
    public function backupColumnData(string $tableName, string $column): void
    {
        try {
            $this->logBackupStart(tableName: $tableName, column: $column);
            $columnData = $this->fetchColumnData(tableName: $tableName, column: $column);
            $this->ensureTablesInfosTableExists();
            $this->storeBackupData(tableName: $tableName, column: $column, data: $columnData);
        } catch ( Exception $exception ) {
            $this->logBackupError(tableName: $tableName, column: $column, exception: $exception);
            throw $exception;
        }
    }
    
    /**
     * Registra no log o início do backup de uma coluna
     *
     * Português
     * Este método registra no log a informação de início do backup de dados de uma coluna específica em uma tabela.
     *
     * Espanhol
     * Este método registra en el registro la información de inicio de la copia de seguridad de datos de una columna específica en una tabla.
     *
     * Inglês
     * This method logs the information of the start of the backup of data from a specific column in a table.
     *
     * @param string $tableName Nome da tabela
     * @param string $column    Nome da coluna
     */
    private function logBackupStart(string $tableName, string $column): void
    {
        Log::info("Realizando backup de dados da coluna: $column na tabela: $tableName");
    }
    
    /**
     * Captura os dados de uma coluna em uma tabela
     *
     * Português
     * Este método captura os dados de uma coluna específica em uma tabela e os retorna como uma string JSON.
     *
     * Espanhol
     * Este método captura los datos de una columna específica en una tabla y los devuelve como una cadena JSON.
     *
     * Inglês
     * This method captures the data of a specific column in a table and returns it as a JSON string.
     *
     * @param string $tableName Nome da tabela
     * @param string $column    Nome da coluna
     *
     * @return string Dados da coluna em formato JSON
     */
    private function fetchColumnData(string $tableName, string $column): string
    {
        return DB::table($tableName)->pluck($column, 'id')->toJson();
    }
    
    /**
     * Verifica e cria a tabela 'tables_infos' se ela não existir
     *
     * Português
     * Este método verifica se a tabela 'tables_infos' existe na base de dados.
     * Se a tabela não existir, ela é criada usando o método createTablesInfosTable().
     *
     * Espanhol
     * Este método verifica si la tabla 'tables_infos' existe en la base de datos.
     * Si la tabla no existe, se crea usando el método createTablesInfosTable().
     *
     * Inglês
     * This method checks if the 'tables_infos' table exists in the database.
     * If the table does not exist, it is created using the createTablesInfosTable() method.
     */
    private function ensureTablesInfosTableExists(): void
    {
        if (!Schema::hasTable('tables_infos')) {
            $this->createTablesInfosTable();
        }
    }
    
    /**
     * Cria a tabela 'tables_infos'
     *
     * Português
     * Este método cria a tabela 'tables_infos' na base de dados com as colunas necessárias.
     *
     * Espanhol
     * Este método crea la tabla 'tables_infos' en la base de datos con las columnas necesarias.
     *
     * Inglês
     * This method creates the 'tables_infos' table in the database with the necessary columns.
     */
    private function createTablesInfosTable(): void
    {
        if (!Schema::hasTable('tables_infos')) {
            Schema::create(
                'tables_infos',
                function (Blueprint $table)
                {
                    $table->bigIncrements('id');
                    $table->string('table_name');
                    $table->string('column_name')->nullable();
                    $table->enum('type', ['backup', 'infos', 'others']);
                    $table->json('data');
                    $table->timestamps();
                },
            );
        }
    }
    
    /**
     * Armazena os dados de backup em 'tables_infos'
     *
     * Português
     * Este método armazena os dados de backup de uma coluna específica em uma tabela na tabela 'tables_infos'.
     *
     * Espanhol
     * Este método almacena los datos de copia de seguridad de una columna específica en una tabla en la tabla 'tables_infos'.
     *
     * Inglês
     * This method stores the backup data of a specific column in a table in the 'tables_infos' table.
     *
     * @param string $tableName Nome da tabela
     * @param string $column    Nome da coluna
     * @param string $data      Dados de backup em formato JSON
     */
    private function storeBackupData(string $tableName, string $column, string $data): void
    {
        DB::table('tables_infos')->insert(
            [
                'table_name'  => $tableName,
                'column_name' => $column,
                'type'        => 'backup',
                'data'        => $data,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        );
    }
    
    /**
     * Registra no log um erro durante o backup de uma coluna
     *
     * Português
     * Este método registra no log a informação de um erro que ocorreu durante o backup de dados de uma coluna específica em uma tabela.
     *
     * Espanhol
     * Este método registra en el registro la información de un error que se produjo durante la copia de seguridad de datos de una columna específica en una tabla.
     *
     * Inglês
     * This method logs the information of an error that occurred during the backup of data from a specific column in a table.
     *
     * @param string    $tableName Nome da tabela
     * @param string    $column    Nome da coluna
     * @param Exception $exception Exceção que ocorreu durante o backup
     */
    private function logBackupError(string $tableName, string $column, Exception $exception): void
    {
        Log::error("Erro ao realizar backup de dados da coluna: $column na tabela: $tableName - " . $exception->getMessage());
    }
}
