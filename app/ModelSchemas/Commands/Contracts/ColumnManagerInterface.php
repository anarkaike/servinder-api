<?php

namespace App\ModelSchemas\Commands\Contracts;

use Illuminate\Database\Schema\Blueprint;

/**
 * Interface para gerenciamento de colunas de banco de dados
 *
 * Português
 * Esta interface define o contrato para a atualização e adição de colunas em esquemas de banco de dados.
 *
 * Español
 * Esta interfaz define el contrato para la actualización y adición de columnas en esquemas de base de datos.
 *
 * English
 * This interface defines the contract for updating and adding columns to database schemas.
 *
 * Principais objetivos:
 * - Definir um contrato para gerenciar colunas de banco de dados.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface ColumnManagerInterface
{
    /**
     * Atualiza uma coluna em um esquema de banco de dados
     *
     * Português
     * Este método atualiza a definição de uma coluna específica em um esquema de banco de dados.
     *
     * Español
     * Este método actualiza la definición de una columna específica en un esquema de base de datos.
     *
     * English
     * This method updates the definition of a specific column in a database schema.
     *
     * Principais objetivos:
     * - Atualizar a definição de uma coluna em um esquema de banco de dados.
     *
     * @param Blueprint $table O esquema de banco de dados onde a coluna será atualizada
     * @param string $column O nome da coluna a ser atualizada
     * @param string $definition A nova definição da coluna
     * @param array $schemaDefinition As configurações adicionais do esquema de banco de dados
     *
     * @return void
     */
    public function updateColumn(Blueprint $table, string $column, string $definition, array $schemaDefinition): void;
    
    /**
     * Adiciona uma nova coluna em um esquema de banco de dados
     *
     * Português
     * Este método adiciona uma nova coluna ao esquema de banco de dados com a definição especificada.
     *
     * Español
     * Este método añade una nueva columna al esquema de base de datos con la definición especificada.
     *
     * English
     * This method adds a new column to the database schema with the specified definition.
     *
     * Principais objetivos:
     * - Adicionar uma nova coluna ao esquema de banco de dados.
     *
     * @param Blueprint $table O esquema de banco de dados onde a coluna será adicionada
     * @param string $column O nome da nova coluna
     * @param string $definition A definição da nova coluna
     * @param array $schemaDefinition As configurações adicionais do esquema de banco de dados
     *
     * @return void
     */
    public function addColumn(Blueprint $table, string $column, string $definition, array $schemaDefinition): void;
}
