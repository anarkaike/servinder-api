<?php

namespace Modules\AccessControl\App\Models;

use App\ModelSchemas\Enums\EColumnType;
use App\ModelSchemas\Enums\ESchemaKey;
use App\ModelSchemas\Traits\AddDefaultColumnsTrait;

trait RoleSchemeTrait
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
