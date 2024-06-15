<?php

namespace App\ModelSchemas\Commands\Contracts;

/**
 * Interface para construtores de definições de esquema
 *
 * Português
 * Esta interface define o contrato para classes que constróem definições de colunas
 * e chaves estrangeiras para esquemas de banco de dados.
 *
 * Espanhol
 * Esta interfaz define el contrato para clases que construyen definiciones de columnas
 * y claves externas para esquemas de base de datos.
 *
 * Inglês
 * This interface defines the contract for classes that build column and foreign key
 * definitions for database schemas.
 *
 * Principais objetivos:
 * - Fornecer um padrão para construir definições de esquema de banco de dados.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface SchemaDefinitionBuilderInterface
{
    /**
     * Constrói uma definição de coluna a partir de uma matriz de definições
     *
     * Português
     * Constrói uma string de definição de coluna a partir de uma matriz de
     * definições fornecida.
     *
     * Espanhol
     * Construye una cadena de definición de columna a partir de una matriz de
     * definiciones proporcionadas.
     *
     * Inglês
     * Builds a column definition string from a provided array of definitions.
     *
     * @param array $definition A matriz de definições de coluna
     *
     * @return string A string de definição de coluna
     */
    public function buildColumnDefinition(array $definition): string;
    
    /**
     * Constrói uma definição de chave estrangeira a partir de uma matriz de definições
     * e de uma coluna específica
     *
     * Português
     * Constrói uma matriz de definição de chave estrangeira a partir de uma matriz de
     * definições fornecida e de uma coluna específica. Retorna null se a chave estrangeira
     * não puder ser construída.
     *
     * Espanhol
     * Construye una matriz de definición de clave externa a partir de una matriz de
     * definiciones proporcionadas y de una columna específica. Devuelve null si la clave externa
     * no puede ser construida.
     *
     * Inglês
     * Builds an array of foreign key definition from a provided array of definitions
     * and a specific column. Returns null if the foreign key cannot be built.
     *
     * @param array $definition A matriz de definições de chave estrangeira
     * @param string $column A coluna específica
     *
     * @return array|null A matriz de definição de chave estrangeira ou null
     */
    public function buildForeignKeyDefinition(array $definition, string $column): ?array;
}
