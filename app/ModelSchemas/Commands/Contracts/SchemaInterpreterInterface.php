<?php

namespace App\ModelSchemas\Commands\Contracts;

/**
 * Interface para interpretadores de esquema
 *
 * Português
 * Esta interface define o contrato para classes que interpretam esquemas de banco de dados.
 *
 * Espanhol
 * Esta interfaz define el contrato para clases que interpretan esquemas de base de datos.
 *
 * Inglês
 * This interface defines the contract for classes that interpret database schema.
 *
 * Principais objetivos:
 * - Fornecer um padrão para interpretar e manipular esquemas de banco de dados.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface SchemaInterpreterInterface
{
    /**
     * Obtém o tipo de coluna a partir de um tipo de dados
     *
     * Português
     * Retorna o tipo de coluna correspondente ao tipo de dados fornecido.
     *
     * Espanhol
     * Devuelve el tipo de columna correspondiente al tipo de datos proporcionado.
     *
     * Inglês
     * Returns the corresponding column type for the given data type.
     *
     * @param string $type O tipo de dados
     *
     * @return string O tipo de coluna
     */
    public function getColumnType(string $type): string;
    
    /**
     * Constrói uma string de definição de coluna a partir de uma definição de esquema
     *
     * Português
     * Constrói uma string de definição de coluna a partir de uma matriz de
     * definições de esquema fornecida.
     *
     * Espanhol
     * Construye una cadena de definición de columna a partir de una matriz de
     * definiciones de esquema proporcionadas.
     *
     * Inglês
     * Builds a column definition string from a provided array of schema definition.
     *
     * @param array $schemaDefinition A matriz de definições de esquema
     *
     * @return string A string de definição de coluna
     */
    public function buildColumnDefinitionString(array $schemaDefinition): string;
}
