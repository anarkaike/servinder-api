<?php

namespace Modules\Users\app\Models;

use App\ModelSchemas\Enums\EColumnType;
use App\ModelSchemas\Enums\ESchemaKey;
use App\ModelSchemas\Enums\ESchemaValue;
use App\ModelSchemas\Helpers\SchemaHelper;
use App\ModelSchemas\Traits\AddDefaultColumnsTrait;

trait UserSchemeTrait
{
    use AddDefaultColumnsTrait;
    
    const NAME              = 'name';
    const PARENT_ID         = 'parent_id';
    const EMAIL             = 'email';
    const EMAIL_VERIFIED_AT = 'email_verified_at';
    const REMEMBER_TOKEN    = 'remember_token';
    const PASSWORD          = 'password';
    
    public function getSchema(): array
    {
        $position = 1;
        $schema = [
            self::PARENT_ID         => [
                ESchemaKey::TYPE     => EColumnType::UNSIGNED_BIG_INTEGER,
                ESchemaKey::NULLABLE => TRUE,
                ESchemaKey::ON       => [
                    ESchemaKey::ON_FK     => SchemaHelper::table(User::class),
                    ESchemaKey::ON_COLUMN => 'id',
                    ESchemaKey::ON_DELETE => ESchemaValue::ON_NO_ACTON,
                    ESchemaKey::ON_UPDATE => ESchemaValue::ON_NO_ACTON,
                ],
            ],
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
                ESchemaKey::POSITION         => $position++,
            ],
            self::EMAIL_VERIFIED_AT => [
                ESchemaKey::ONLY_DESCRIPTION => TRUE, // Schema does not manage
                ESchemaKey::TYPE             => EColumnType::TIMESTAMP,
                ESchemaKey::NULLABLE         => TRUE,
                ESchemaKey::DESCRIPTION      => 'Email verification timestamp',
                ESchemaKey::POSITION         => $position++,
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
        ];
        
        return $this->addDefaultColumns($schema, $position); // PK & Audit
    }
}
