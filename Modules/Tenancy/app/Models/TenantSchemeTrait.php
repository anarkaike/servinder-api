<?php

namespace Modules\Tenancy\App\Models;

use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;
use ModelSchemas\Traits\AddDefaultColumnsTrait;

trait TenantSchemeTrait
{
    use AddDefaultColumnsTrait;
    
    public function getSchema(): array
    {
        $schema = [
            'name' => [
                ESchemaKey::TYPE        => EColumnType::STRING,
                ESchemaKey::NOT_NULL    => TRUE,
                ESchemaKey::LENGTH      => 255,
                ESchemaKey::LABEL       => 'Nome',
                ESchemaKey::DESCRIPTION => 'Name of the user',
            ],
        ];
        
        return $this->addDefaultColumns($schema); // PK & Audit
    }
}
