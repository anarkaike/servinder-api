<?php

namespace App\ModelSchemas\Commands\Contracts;

/**
 * Interface para gerenciadores de versões
 *
 * Português
 * Esta interface define o contrato para classes que gerenciam versões de tabelas.
 *
 * Espanhol
 * Esta interfaz define el contrato para clases que gestionan versiones de tablas.
 *
 * Inglês
 * This interface defines the contract for classes that manage table versions.
 *
 * Principais objetivos:
 * - Fornecer um padrão para gerenciar versões de tabelas.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface VersionManagerInterface
{
    /**
     * Obtém a versão atual de uma tabela a partir de uma coluna específica
     *
     * Português
     * Retorna a versão atual correspondente à tabela e coluna fornecidas.
     * Retorna null se a versão não puder ser obtida.
     *
     * Espanhol
     * Devuelve la versión actual correspondiente a la tabla y columna proporcionadas.
     * Devuelve null si la versión no puede ser obtenida.
     *
     * Inglês
     * Returns the current version corresponding to the provided table and column.
     * Returns null if the version cannot be obtained.
     *
     * @param string $tableName O nome da tabela
     * @param string $column    O nome da coluna
     *
     * @return int|null A versão atual ou null
     */
    public function getCurrentVersion(string $tableName, string $column): ?int;
    
    /**
     * Atualiza a versão de uma tabela a partir de uma coluna específica e de uma versão específica
     *
     * Português
     * Atualiza a versão correspondente à tabela, coluna e versão fornecidas.
     *
     * Espanhol
     * Actualiza la versión correspondiente a la tabla, columna y versión proporcionadas.
     *
     * Inglês
     * Updates the corresponding version for the provided table, column, and version.
     *
     * @param string $tableName O nome da tabela
     * @param string $column    O nome da coluna
     * @param int    $version   A versão
     *
     * @return void
     */
    public function updateVersion(string $tableName, string $column, int $version): void;
}
