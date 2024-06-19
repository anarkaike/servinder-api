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
        $schema = [];
        $this->addPrimaryKeyColumn($schema);
        $position = 1;
        $schema = [
            ...$schema,
            ...[
                'name' => [
                    ESchemaKey::TYPE        => EColumnType::STRING,
                    ESchemaKey::NOT_NULL    => TRUE,
                    ESchemaKey::LENGTH      => 255,
                    ESchemaKey::LABEL       => 'Nome',
                    ESchemaKey::DESCRIPTION => 'Name of the user',
                    ESchemaKey::POSITION    => $position++,
                ],
                'data' => [
                    ESchemaKey::TYPE        => EColumnType::JSON,
                    ESchemaKey::NULLABLE    => TRUE,
                    ESchemaKey::LABEL       => 'Nome',
                    ESchemaKey::DESCRIPTION => 'Name of the user',
                    ESchemaKey::POSITION    => $position++,
                ],
            ],
        ];
        
        $this->addAuditColumns($schema);
        
        return $schema;
    }
}
