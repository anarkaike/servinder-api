<?php

declare(strict_types=1);

namespace ModelSchemas\Commands\Builders;

use ModelSchemas\Commands\Contracts\SchemaDefinitionBuilderInterface;
use ModelSchemas\Enums\ESchemaKey;
use function implode;
use function str_contains;

class SchemaDefinitionBuilder implements SchemaDefinitionBuilderInterface
{
    public function buildColumnDefinition(array $definition): string
    {
        $columnParts = [];
        
        $this->addColumnType($columnParts, $definition);
        $this->addColumnLength($columnParts, $definition);
        $this->addColumnPrecisionAndScale($columnParts, $definition);
        $this->addColumnNullability($columnParts, $definition);
        $this->addColumnUniqueness($columnParts, $definition);
        $this->addColumnDefault($columnParts, $definition);
        
        // Adicionar AUTO_INCREMENT se estiver definido
        if (isset($definition[ ESchemaKey::AUTO_INCREMENT ]) && $definition[ ESchemaKey::AUTO_INCREMENT ]) {
            $columnParts[] = 'AUTO_INCREMENT';
        }
        
        return implode(' ', $columnParts);
    }
    
    public function buildForeignKeyDefinition(array $definition, string $column): ?array
    {
        if (!isset($definition[ ESchemaKey::ON ])) {
            return NULL;
        }
        
        return [
            'foreign_table' => $definition[ ESchemaKey::ON ]['table'],
            'references'    => $definition[ ESchemaKey::ON ]['references'],
            'on'            => $column,
            'onDelete'      => strtoupper($definition[ ESchemaKey::ON ]['on_delete'] ?? 'RESTRICT'),
        ];
    }
    
    
    protected function mapColumnType(string $type, ?int $length = NULL): string
    {
        $typeMapping = [
            'increments'           => 'INT AUTO_INCREMENT PRIMARY KEY',
            'bigIncrements'        => 'BIGINT AUTO_INCREMENT PRIMARY KEY',
            'smallIncrements'      => 'SMALLINT AUTO_INCREMENT PRIMARY KEY',
            'tinyIncrements'       => 'TINYINT AUTO_INCREMENT PRIMARY KEY',
            'integer'              => 'INT',
            'bigInteger'           => 'BIGINT',
            'smallInteger'         => 'SMALLINT',
            'tinyInteger'          => 'TINYINT',
            'unsignedInteger'      => 'INT UNSIGNED',
            'unsignedBigInteger'   => 'BIGINT UNSIGNED',
            'unsignedSmallInteger' => 'SMALLINT UNSIGNED',
            'unsignedTinyInteger'  => 'TINYINT UNSIGNED',
            'char'                 => 'CHAR',
            'string'               => 'VARCHAR',
            'text'                 => 'TEXT',
            'mediumText'           => 'MEDIUMTEXT',
            'longText'             => 'LONGTEXT',
            'binary'               => 'BLOB',
            'boolean'              => 'TINYINT(1)',
            'date'                 => 'DATE',
            'datetime'             => 'DATETIME',
            'datetimeTz'           => 'DATETIME',
            'time'                 => 'TIME',
            'timeTz'               => 'TIME',
            'timestamp'            => 'TIMESTAMP',
            'timestampTz'          => 'TIMESTAMP',
            'year'                 => 'YEAR',
            'json'                 => 'JSON',
            'jsonb'                => 'JSON',
            'uuid'                 => 'CHAR(36)',
            'ipAddress'            => 'VARCHAR(45)',
            'macAddress'           => 'VARCHAR(17)',
            'geometry'             => 'GEOMETRY',
            'point'                => 'POINT',
            'linestring'           => 'LINESTRING',
            'polygon'              => 'POLYGON',
            'multipoint'           => 'MULTIPOINT',
            'multilinestring'      => 'MULTILINESTRING',
            'multipolygon'         => 'MULTIPOLYGON',
            'geometrycollection'   => 'GEOMETRYCOLLECTION',
            'enum'                 => 'ENUM',
            'set'                  => 'SET',
            'double'               => 'DOUBLE',
            'float'                => 'FLOAT',
            'decimal'              => 'DECIMAL',
            'unsignedDecimal'      => 'DECIMAL UNSIGNED',
            'rememberToken'        => 'VARCHAR(100)',
            // Adicione outros mapeamentos de tipo conforme necessário
        ];
        
        $mappedType = $typeMapping[ $type ] ?? $type;
        if ($length !== NULL && !str_contains($mappedType, '(') && in_array($type, ['char', 'string', 'varchar'])) {
            $mappedType .= "($length)";
        }
        
        return $mappedType;
    }
    
    private function addColumnType(array &$parts, array $definition): void
    {
        if (array_key_exists(ESchemaKey::TYPE, $definition)) {
            $length = array_key_exists(ESchemaKey::LENGTH, $definition) ? $definition[ ESchemaKey::LENGTH ] : NULL;
            $parts[] = $this->mapColumnType($definition[ ESchemaKey::TYPE ], $length);
        }
    }
    
    private function addColumnLength(array &$parts, array $definition): void
    {
        if (!str_contains(implode('', $parts), '(') && array_key_exists(ESchemaKey::LENGTH, $definition)) {
            $parts[] = "({$definition[ESchemaKey::LENGTH]})";
        }
    }
    
    private function addColumnPrecisionAndScale(array &$parts, array $definition): void
    {
        if (array_key_exists(ESchemaKey::PRECISION, $definition) && array_key_exists(ESchemaKey::SCALE, $definition)) {
            $parts[] = "({$definition[ESchemaKey::PRECISION]}, {$definition[ESchemaKey::SCALE]})";
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
    
    private function addColumnDefault(array &$parts, array $definition): void
    {
        if (array_key_exists(ESchemaKey::DEFAULT, $definition)) {
            $parts[] = "DEFAULT '{$definition[ESchemaKey::DEFAULT]}'";
        }
    }
}
