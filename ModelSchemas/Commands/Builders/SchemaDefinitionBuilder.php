<?php

namespace ModelSchemas\Commands\Builders;

use ModelSchemas\Commands\Contracts\SchemaDefinitionBuilderInterface;
use ModelSchemas\Enums\ESchemaKey;

/**
 * Classe responsável por construir definições de esquema de banco de dados.
 */
class SchemaDefinitionBuilder implements SchemaDefinitionBuilderInterface
{
    /**
     * Constrói uma definição de coluna a partir de um array de definições.
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
