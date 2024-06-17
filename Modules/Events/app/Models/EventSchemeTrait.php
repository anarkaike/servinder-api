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
        return [
            'id'          => [
                ESchemaKey::TYPE           => EColumnType::BIG_INTEGER,
                ESchemaKey::PRIMARY_KEY    => TRUE,
                ESchemaKey::NOT_NULL       => TRUE,
                ESchemaKey::AUTO_INCREMENT => TRUE,
                ESchemaKey::PRIMARY_KEY    => TRUE,
                ESchemaKey::POSITION       => 1,
            ],
            'name'        => [
                ESchemaKey::TYPE     => EColumnType::STRING,
                ESchemaKey::NOT_NULL => TRUE,
                ESchemaKey::LENGTH   => 255,
                ESchemaKey::POSITION => 2,
            ],
            'description' => [
                ESchemaKey::TYPE     => EColumnType::STRING,
                ESchemaKey::NOT_NULL => TRUE,
                ESchemaKey::LENGTH   => 255,
                ESchemaKey::POSITION => 2,
            ],
        ];
    }
}
