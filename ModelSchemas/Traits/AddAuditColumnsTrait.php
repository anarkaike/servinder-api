<?php

namespace ModelSchemas\Traits;

use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;

trait AddAuditColumnsTrait
{
    
    const CREATED_AT = 'created_at';
    const CREATED_BY = 'created_by';
    const UPDATED_AT = 'updated_at';
    const UPDATED_BY = 'updated_by';
    const DELETED_AT = 'deleted_at';
    const DELETED_BY = 'deleted_by';
    
    private function addAuditColumns(array &$schema, $position = 9999999): void
    {
        $schema = [
            ...$schema,
            ...[
                self::CREATED_AT => [
                    ESchemaKey::TYPE        => EColumnType::TIMESTAMP,
                    ESchemaKey::DESCRIPTION => 'Quando o registro foi criado.',
                    ESchemaKey::POSITION    => $position++,
                ],
                self::CREATED_BY => [
                    ESchemaKey::TYPE        => EColumnType::BIG_INTEGER,
                    ESchemaKey::DESCRIPTION => 'Por quem foi criado.',
                    ESchemaKey::POSITION    => $position++,
                    //                    SchemaKey::ON          => [
                    //                        SchemaKey::ON_FK     => SchemaHelper::table(User::class),
                    //                        SchemaKey::ON_COLUMN => 'id',
                    //                        SchemaKey::ON_DELETE => SchemaValue::ON_NO_ACTON,
                    //                        SchemaKey::ON_UPDATE => SchemaValue::ON_NO_ACTON,
                    //                    ],
                ],
                self::UPDATED_AT => [
                    ESchemaKey::TYPE        => EColumnType::TIMESTAMP,
                    ESchemaKey::DESCRIPTION => 'Quando o registro foi atualizado por ultima vez.',
                    ESchemaKey::POSITION    => $position++,
                ],
                self::UPDATED_BY => [
                    ESchemaKey::TYPE        => EColumnType::BIG_INTEGER,
                    ESchemaKey::DESCRIPTION => 'Por quem o registro foi atualizado por ultima vez.',
                    ESchemaKey::POSITION    => $position++,
                    //                    SchemaKey::ON          => [
                    //                        SchemaKey::ON_FK     => SchemaHelper::table(User::class),
                    //                        SchemaKey::ON_COLUMN => 'id',
                    //                        SchemaKey::ON_DELETE => SchemaValue::ON_NO_ACTON,
                    //                        SchemaKey::ON_UPDATE => SchemaValue::ON_NO_ACTON,
                    //                    ],
                ],
                self::DELETED_AT => [
                    ESchemaKey::TYPE        => EColumnType::TIMESTAMP,
                    ESchemaKey::DESCRIPTION => 'Quando o registro foi excluido.',
                    ESchemaKey::POSITION    => $position++,
                ],
                self::DELETED_BY => [
                    ESchemaKey::TYPE        => EColumnType::BIG_INTEGER,
                    ESchemaKey::DESCRIPTION => 'Por quem o registro foi excluido.',
                    ESchemaKey::POSITION    => $position,
                    //                    SchemaKey::ON          => [
                    //                        SchemaKey::ON_FK     => SchemaHelper::table(User::class),
                    //                        SchemaKey::ON_COLUMN => 'id',
                    //                        SchemaKey::ON_DELETE => SchemaValue::ON_NO_ACTON,
                    //                        SchemaKey::ON_UPDATE => SchemaValue::ON_NO_ACTON,
                    //                    ],
                ],
            ],
        ];
    }
}
