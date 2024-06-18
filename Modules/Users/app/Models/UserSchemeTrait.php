<?php

namespace Modules\Users\app\Models;

use Illuminate\Support\Str;
use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;
use ModelSchemas\Enums\ESchemaValue;
use ModelSchemas\Helpers\SchemaHelper;
use ModelSchemas\Traits\AddDefaultColumnsTrait;
use Modules\Events\app\Models\Event;

trait UserSchemeTrait
{
    use AddDefaultColumnsTrait;
    
    const NAME              = 'name';
    const NAME2             = 'name2';
    const PARENT_ID         = 'parent_id';
    const EMAIL             = 'email';
    const EMAIL_VERIFIED_AT = 'email_verified_at';
    const REMEMBER_TOKEN    = 'remember_token';
    const PASSWORD          = 'password';
    
    public function getSchema(): array
    {
        $schema = [];
        $this->addPrimaryKeyColumn($schema);
        $this->addTenantIdColumns($schema);
        $position = 2;
        $schema = [
            ...$schema,
            ...[
                self::NAME              => [
                    ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                    ESchemaKey::TYPE             => EColumnType::STRING,
                    ESchemaKey::NOT_NULL         => TRUE,
                    ESchemaKey::LENGTH           => 255,
                    ESchemaKey::LABEL            => 'Nome',
                    ESchemaKey::DESCRIPTION      => 'Name of the user',
                    ESchemaKey::POSITION         => $position++,
                ],
                self::EMAIL             => [
                    ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                    ESchemaKey::TYPE             => EColumnType::STRING,
                    ESchemaKey::LENGTH           => 255,
                    ESchemaKey::UNIQUE           => TRUE,
                    ESchemaKey::DESCRIPTION      => 'Email address of the user',
                    ESchemaKey::POSITION         => $position++,//
                ],
                self::EMAIL_VERIFIED_AT => [
                    ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                    ESchemaKey::TYPE             => EColumnType::TIMESTAMP,
                    ESchemaKey::NULLABLE         => TRUE,
                    ESchemaKey::DESCRIPTION      => 'Email verification timestamp',
                    ESchemaKey::POSITION         => $position++,
                ],
                self::PARENT_ID         => [
                    ESchemaKey::TYPE     => EColumnType::UNSIGNED_INTEGER,
                    ESchemaKey::NULLABLE => TRUE,
                    ESchemaKey::POSITION => 4,
                    ESchemaKey::ON       => [
                        ESchemaKey::ON_FK     => SchemaHelper::table(Event::class),
                        ESchemaKey::ON_COLUMN => 'id',
                        ESchemaKey::ON_DELETE => ESchemaValue::ON_NO_ACTION,
                        ESchemaKey::ON_UPDATE => ESchemaValue::ON_NO_ACTION,
                    ],
                ],
                self::PASSWORD          => [
                    ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                    ESchemaKey::TYPE             => EColumnType::STRING,
                    ESchemaKey::LENGTH           => 255,
                    ESchemaKey::DESCRIPTION      => 'User password',
                    ESchemaKey::POSITION         => $position++,
                ],
                self::REMEMBER_TOKEN    => [
                    ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                    ESchemaKey::TYPE             => EColumnType::REMEMBER_TOKEN,
                    ESchemaKey::DESCRIPTION      => 'Token to remember the user',
                    ESchemaKey::POSITION         => $position++,
                    ESchemaKey::LENGTH           => 100,
                ],
                self::CREATED_AT        => [
                    ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                    ESchemaKey::TYPE             => EColumnType::TIMESTAMP,
                    ESchemaKey::DESCRIPTION      => 'Quando o registro foi criado.',
                    ESchemaKey::POSITION         => $position++,
                ],
                self::UPDATED_AT        => [
                    ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                    ESchemaKey::TYPE             => EColumnType::TIMESTAMP,
                    ESchemaKey::DESCRIPTION      => 'Quando o registro foi atualizado por ultima vez.',
                    ESchemaKey::POSITION         => $position++,
                ],
            ],
        ];
        
        $this->addAuditColumns($schema, $position);
        
        return $schema;
    }
}
