<?php

namespace ModelSchemas;

use ModelSchemas\Enums\ESchemaKey;

class SchemaField
{
    
    public $type;
    public $length;
    public $unique;
    public $nullable;
    public $not_null;
    public $label;
    public $description;
    
    public function __construct(array $attributes)
    {
        $this->type = $attributes[ ESchemaKey::TYPE ] ?? NULL;
        $this->length = $attributes[ ESchemaKey::LENGTH ] ?? NULL;
        $this->unique = $attributes[ ESchemaKey::UNIQUE ] ?? FALSE;
        $this->nullable = $attributes[ ESchemaKey::NULLABLE ] ?? FALSE;
        $this->not_null = $attributes[ ESchemaKey::NOT_NULL ] ?? FALSE;
        $this->label = $attributes[ ESchemaKey::LABEL ] ?? '';
        $this->description = $attributes[ ESchemaKey::DESCRIPTION ] ?? '';
    }
}
