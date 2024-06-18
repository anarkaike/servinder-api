<?php

namespace Modules\Events\App\Models;

use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;
use ModelSchemas\Traits\AddDefaultColumnsTrait;

trait EventSchemeTrait
{
    use AddDefaultColumnsTrait;
    
    const SCHEME_SYNC_ACTIVED      = TRUE;
    const SCHEME_SYNC_EDIT_ACTIVED = TRUE;
    
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
