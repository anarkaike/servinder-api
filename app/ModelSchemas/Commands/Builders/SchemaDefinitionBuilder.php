<?php

namespace App\ModelSchemas\Commands\Builders;

use App\ModelSchemas\Commands\Contracts\SchemaDefinitionBuilderInterface;
use App\ModelSchemas\Enums\ESchemaKey;

/**
 * Classe responsável por construir definições de esquema de banco de dados.
 *
 * Português
 * Esta classe fornece métodos para construir definições de colunas e chaves estrangeiras
 * de esquemas de banco de dados, a partir de arrays de definições.
 *
 * Espanhol
 * Esta clase proporciona métodos para construir definiciones de columnas y claves foráneas
 * de esquemas de base de datos, a partir de arrays de definiciones.
 *
 * Inglês
 * This class provides methods to build column and foreign key definitions
 * of database schema from arrays of definitions.
 *
 * Principais objetivos:
 * - Fornecer uma maneira fácil e consistente de construir definições de esquema de banco de dados.
 * - Suportar diferentes tipos de colunas e chaves estrangeiras.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
class SchemaDefinitionBuilder implements SchemaDefinitionBuilderInterface
{
    /**
     * Constrói uma definição de coluna a partir de um array de definições.
     *
     * Português
     * Constrói uma string que representa a definição de coluna de acordo com as
     * definições fornecidas.
     *
     * Espanhol
     * Construye una cadena que representa la definición de columna de acuerdo con las
     * definiciones proporcionadas.
     *
     * Inglês
     * Builds a string that represents the column definition according to the
     * provided definitions.
     *
     * @param array $definition Array de definições de coluna.
     *
     * @return string Definição de coluna.
     */
    public function buildColumnDefinition(array $definition): string
    {
        $columnParts = [];
        
        $this->addColumnType($columnParts, $definition);
        $this->addColumnLength($columnParts, $definition);
        $this->addColumnNullability($columnParts, $definition);
        $this->addColumnUniqueness($columnParts, $definition);
        
        return implode(' ', $columnParts);
    }
    
    /**
     * Constrói uma definição de chave estrangeira a partir de um array de definições.
     *
     * Português
     * Constrói um array que representa a definição de chave estrangeira de acordo com as
     * definições fornecidas. Retorna NULL se a chave estrangeira não estiver definida.
     *
     * Espanhol
     * Construye un array que representa la definición de clave foránea de acuerdo con las
     * definiciones proporcionadas. Devuelve NULL si la clave foránea no está definida.
     *
     * Inglês
     * Builds an array that represents the foreign key definition according to the
     * provided definitions. Returns NULL if the foreign key is not defined.
     *
     * @param array  $definition Array de definições de chave estrangeira.
     * @param string $column     Nome da coluna.
     *
     * @return array|null Definição de chave estrangeira ou NULL se não definida.
     */
    public function buildForeignKeyDefinition(array $definition, string $column): ?array
    {
        if (!isset($definition[ ESchemaKey::ON ])) {
            return NULL;
        }
        
        return [
            'table'      => $definition[ ESchemaKey::ON ]['table'],
            'references' => $definition[ ESchemaKey::ON ]['references'],
            'on_delete'  => strtoupper($definition[ ESchemaKey::ON ]['on_delete'] ?? 'RESTRICT'),
        ];
    }
    
    /**
     * Mapeia um tipo de coluna para seu equivalente no banco de dados.
     *
     * Português
     * Mapeia um tipo de coluna para seu equivalente no banco de dados, caso não seja
     * encontrado um mapeamento específico, retorna o tipo original.
     *
     * Espanhol
     * Mapea un tipo de columna para su equivalente en la base de datos, en caso de que no se
     * encuentre un mapeo específico, devuelve el tipo original.
     *
     * Inglês
     * Maps a column type to its equivalent in the database, if no specific mapping is found,
     * returns the original type.
     *
     * @param string $type Tipo de coluna.
     *
     * @return string Tipo de coluna mapeado.
     */
    protected function mapColumnType(string $type): string
    {
        $typeMapping = [
            'unsignedBigInteger' => 'BIGINT UNSIGNED',
            // Adicione outros mapeamentos de tipo conforme necessário
        ];
        
        return $typeMapping[ $type ] ?? $type;
    }
    
    // Métodos privados para adicionar partes à definição de coluna
    
    private function addColumnType(array &$parts, array $definition): void
    {
        if (array_key_exists(ESchemaKey::TYPE, $definition)) {
            $parts[] = $this->mapColumnType(type: $definition[ ESchemaKey::TYPE ]);
        }
    }
    
    private function addColumnLength(array &$parts, array $definition): void
    {
        if (array_key_exists(ESchemaKey::LENGTH, $definition)) {
            $parts[] = "({$definition[ESchemaKey::LENGTH]})";
        }
    }
    
    private function addColumnNullability(array &$parts, array $definition): void
    {
        $parts[] = array_key_exists(ESchemaKey::NOT_NULL, $definition) && $definition[ ESchemaKey::NOT_NULL ] === TRUE
            ? 'NOT NULL'
            : 'NULL';
    }
    
    private function addColumnUniqueness(array &$parts, array $definition): void
    {
        if (array_key_exists(ESchemaKey::UNIQUE, $definition) && $definition[ ESchemaKey::UNIQUE ] === TRUE) {
            $parts[] = 'UNIQUE';
        }
    }
}
