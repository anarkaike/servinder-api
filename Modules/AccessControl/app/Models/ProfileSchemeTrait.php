<?php

namespace Modules\AccessControl\App\Models;

use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;
use ModelSchemas\Traits\AddDefaultColumnsTrait;

trait ProfileSchemeTrait
{
    use AddDefaultColumnsTrait;
    
    public function getSchema(): array
    {
        $schema = [];
        $this->addPrimaryKeyColumn($schema);
        $this->addTenantIdColumns($schema);
        $position = 2;
        $schema = [...$schema, ...[
            'name' => [
                ESchemaKey::TYPE        => EColumnType::STRING,
                ESchemaKey::NOT_NULL    => TRUE,
                ESchemaKey::LENGTH      => 255,
                ESchemaKey::LABEL       => 'Nome',
                ESchemaKey::DESCRIPTION => 'Name of the user',
                ESchemaKey::POSITION    => $position++,
            ],
        ]];
        
        $this->addAuditColumns($schema);
        
        return $schema;
    }
}
