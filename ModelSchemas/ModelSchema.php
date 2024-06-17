<?php

namespace ModelSchemas;

use ModelSchemas\Enums\ESchemaKey;

class ModelSchema
{
    public array $fields = [];
    
    public function __construct(array $schema)
    {
        foreach ($schema as $field => $attributes) {
            $this->fields[ $field ] = new SchemaField($attributes);
        }
    }
    
    public static function generateMigration(array $schema): string
    {
        $migrations = '';
        foreach ($schema as $column => $attributes) {
            self::appendMigrationLines($migrations, $attributes, $column);
        }
        return $migrations;
    }
    
    private static function appendMigrationLines(string &$migrations, array $attributes, string $column)
    {
        $type = $attributes[ ESchemaKey::TYPE ];
        self::lineTable($migrations, $attributes, $type, $column);
        self::addLineUnique($migrations, $attributes);
        self::lineNullable($migrations, $attributes);
        self::lineDefault($migrations, $attributes);
        self::lineReferences($migrations, $attributes);
        $migrations .= ";\n";
    }
    
    private static function lineTable(string &$migrations, array $attributes, string $type, string $column)
    {
        $parameters = isset($attributes[ ESchemaKey::LENGTH ]) ? ", {$attributes[ESchemaKey::LENGTH]}" : '';
        $migrations .= "            \$table->{$type}('{$column}'{$parameters})";
    }
    
    private static function addLineUnique(string &$migrations, array $attributes)
    {
        if (!empty($attributes[ ESchemaKey::UNIQUE ])) {
            $migrations .= '->unique()';
        }
    }
    
    private static function lineNullable(string &$migrations, array $attributes)
    {
        if (!empty($attributes[ ESchemaKey::NULLABLE ])) {
            $migrations .= '->nullable()';
        }
    }
    
    private static function lineDefault(string &$migrations, array $attributes)
    {
        if (isset($attributes[ ESchemaKey::DEFAULT ])) {
            $migrations .= "->default({$attributes[ESchemaKey::DEFAULT]})";
        }
    }
    
    private static function lineReferences(string &$migrations, array $attributes)
    {
        if (isset($attributes[ ESchemaKey::REFERENCES ])) {
            $references = $attributes[ ESchemaKey::REFERENCES ];
            $migrations .= "->constrained('{$references[ESchemaKey::ON_FK]}', '{$references[ESchemaKey::ON_COLUMN]}')"
                           . "->onDelete('{$references[ESchemaKey::ON_DELETE]}')"
                           . "->onUpdate('{$references[ESchemaKey::ON_UPDATE]}')";
        }
    }
}
