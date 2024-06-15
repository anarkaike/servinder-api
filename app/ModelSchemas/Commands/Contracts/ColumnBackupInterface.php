<?php

namespace App\ModelSchemas\Commands\Contracts;

/**
 * Interface para backup de dados de colunas
 *
 * Português
 * Esta interface define o contrato para a realização de backup de dados de colunas em um banco de dados.
 *
 * Español
 * Esta interfaz define el contrato para realizar la copia de seguridad de datos de columnas en una base de datos.
 *
 * English
 * This interface defines the contract for backing up column data in a database.
 *
 * Principais objetivos:
 * - Definir um contrato para a realização de backups de dados de colunas.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface ColumnBackupInterface
{
    /**
     * Realiza o backup de dados de uma coluna em uma tabela
     *
     * Português
     * Este método realiza o backup de dados de uma coluna específica em uma tabela do banco de dados.
     *
     * Español
     * Este método realiza la copia de seguridad de datos de una columna específica en una tabla de la base de datos.
     *
     * English
     * This method performs a backup of data from a specific column in a database table.
     *
     * Principais objetivos:
     * - Realizar backup de dados de uma coluna específica em uma tabela.
     *
     * @param string $tableName Nome da tabela onde a coluna está localizada
     * @param string $column Nome da coluna a ser feito backup
     *
     * @return void
     */
    public function backupColumnData(string $tableName, string $column): void;
}
