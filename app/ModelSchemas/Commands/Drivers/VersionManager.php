<?php

namespace App\ModelSchemas\Commands\Drivers;

use App\ModelSchemas\Commands\Contracts\VersionManagerInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Gerenciador de Versões para Colunas de Tabelas
 *
 * Español:
 * Este componente es responsable de administrar y persistir las versiones de columnas de tablas en la base de datos.
 * Implementa la interfaz VersionManagerInterface y proporciona métodos para obtener y actualizar las versiones.
 *
 * Inglês:
 * This component is responsible for managing and persisting the versions of table columns in the database.
 * It implements the VersionManagerInterface and provides methods to retrieve and update versions.
 *
 * Main Objectives:
 * - Provide a reliable and efficient way to manage table column versions.
 * - Ensure data integrity and consistency.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
class VersionManager implements VersionManagerInterface
{
    /**
     * Obtém a versão atual de uma coluna em uma tabela.
     *
     * Español:
     * Este método recupera la versión actual de una columna en una tabla de la base de datos.
     *
     * Inglês:
     * This method retrieves the current version of a column in a table from the database.
     *
     * @param string $tableName Nome da tabela.
     * @param string $columnName Nome da coluna.
     * @return int|null A versão atual da coluna ou NULL se não encontrada.
     * @throws Exception Em caso de falha ao recuperar a versão.
     */
    public function getCurrentVersion(string $tableName, string $columnName): ?int
    {
        try {
            return $this->fetchVersion(tableName: $tableName, columnName: $columnName);
        } catch (Exception $exception) {
            $this->logError(action: 'fetching', tableName: $tableName, columnName: $columnName, exception: $exception);
            throw $exception;
        }
    }

    /**
     * Atualiza a versão de uma coluna em uma tabela.
     *
     * Español:
     * Este método actualiza la versión de una columna en una tabla de la base de datos.
     *
     * Inglês:
     * This method updates the version of a column in a table from the database.
     *
     * @param string $tableName Nome da tabela.
     * @param string $columnName Nome da coluna.
     * @param int $version Nova versão da coluna.
     * @throws Exception Em caso de falha ao atualizar a versão.
     */
    public function updateVersion(string $tableName, string $columnName, int $version): void
    {
        try {
            $this->persistVersion(tableName: $tableName, columnName: $columnName, version: $version);
        } catch (Exception $exception) {
            $this->logError(action: 'updating', tableName: $tableName, columnName: $columnName, exception: $exception);
            throw $exception;
        }
    }

    /**
     * Recupera a versão de uma coluna em uma tabela.
     *
     * @param string $tableName Nome da tabela.
     * @param string $columnName Nome da coluna.
     * @return int|null A versão atual da coluna ou NULL se não encontrada.
     * @throws Exception Em caso de falha ao recuperar a versão.
     */
    private function fetchVersion(string $tableName, string $columnName): ?int
    {
        $version = DB::table('tables_infos')
                     ->where('table_name', $tableName)
                     ->where('column_name', $columnName)
                     ->where('type', 'infos')
                     ->value('data->version');

        return $version ? (int) $version : NULL;
    }

    /**
     * Persiste a versão de uma coluna em uma tabela.
     *
     * @param string $tableName Nome da tabela.
     * @param string $columnName Nome da coluna.
     * @param int $version Nova versão da coluna.
     * @throws Exception Em caso de falha ao persistir a versão.
     */
    private function persistVersion(string $tableName, string $columnName, int $version): void
    {
        DB::table('tables_infos')->updateOrInsert(
            ['table_name' => $tableName, 'column_name' => $columnName, 'type' => 'infos'],
            ['data' => json_encode(['version' => $version]), 'updated_at' => now()],
        );
    }

    /**
     * Registra um erro no log.
     *
     * @param string $action Ação que gerou o erro (fetching ou updating).
     * @param string $tableName Nome da tabela.
     * @param string $columnName Nome da coluna.
     * @param Exception $exception Exceção que gerou o erro.
     */
    private function logError(string $action, string $tableName, string $columnName, Exception $exception): void
    {
        Log::error("Error {$action} version for column: {$columnName} in table: {$tableName} - " . $exception->getMessage());
    }
}
