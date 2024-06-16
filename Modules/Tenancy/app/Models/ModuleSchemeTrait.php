<?php

namespace Modules\Tenancy\App\Models;

use App\ModelSchemas\Enums\EColumnType;
use App\ModelSchemas\Enums\ESchemaKey;
use App\ModelSchemas\Traits\AddDefaultColumnsTrait;

trait ModuleSchemeTrait
{
    use AddDefaultColumnsTrait;
    
    const ID   = 'id';
    const NAME = 'name';
    
    public function getSchema(): array
    {
        $schema = [
            'name' => [
                ESchemaKey::TYPE        => EColumnType::STRING,
                ESchemaKey::NOT_NULL    => TRUE,
                ESchemaKey::LENGTH      => 200,
                ESchemaKey::LABEL       => 'Nome',
                ESchemaKey::DESCRIPTION => 'Name of the user',
                ESchemaKey::AFTER       => self::ID,
                ESchemaKey::POSITION    => 0,
            ],
        ];
        
        return $this->addDefaultColumns($schema); // PK & Audit
    }
}
