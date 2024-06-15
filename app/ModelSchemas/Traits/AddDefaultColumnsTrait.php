<?php

namespace App\ModelSchemas\Traits;

use App\ModelSchemas\Enums\EColumnType;
use App\ModelSchemas\Enums\ESchemaKey;
use App\ModelSchemas\Enums\ESchemaValue;

trait AddDefaultColumnsTrait
{
    const ID        = 'id';
    const TENANT_ID = 'tenant_id';
    
    use AddAuditColumnsTrait;
    
    /**
     * Adiciona as colunas que devem existir em todas as tabelas
     *
     * @param array $schema
     *
     * @return array
     */
    private function addDefaultColumns(array $schema, $position = 0): array
    {
        $schema = $this->addPrimaryKeyColumns($schema, $position);
//        $schema = $this->addTenantIdColumns($schema, $position);
        $schema = $this->addAuditColumns($schema, $position);
        
        return $schema;
    }
    
    /**
     * Adiciona a Primary Key no esquema
     *
     * @param array $schema
     *
     * @return array
     */
    private function addPrimaryKeyColumns(array $schema, &$position = 0): array
    {
        return [
            ...[
                self::ID => [
                    ESchemaKey::TYPE        => EColumnType::INCREMENTS,
                    ESchemaKey::NOT_NULL    => TRUE,
                    ESchemaKey::LABEL       => 'ID',
                    ESchemaKey::DESCRIPTION => 'ID do registro.',
                    ESchemaKey::POSITION    => $position++,
                ],
            ],
            ...$schema,
        ];
    }
    
    /**
     * Adiciona o tenent_id no esquema
     *
     * @param array $schema
     *
     * @return array
     */
    private function addTenantIdColumns(array $schema, &$position = 0): array
    {
        return [
            ...[
                self::TENANT_ID => [
                    ESchemaKey::TYPE        => EColumnType::UNSIGNED_BIG_INTEGER,
                    ESchemaKey::NULLABLE    => TRUE,
                    ESchemaKey::LABEL       => 'Tenant ID',
                    ESchemaKey::DESCRIPTION => 'ID do Tenant.',
                    ESchemaKey::POSITION    => $position++,
                    ESchemaKey::ON          => [
                        ESchemaKey::ON_FK     => 'tenants',
                        ESchemaKey::ON_COLUMN => 'id',
                        ESchemaKey::ON_DELETE => ESchemaValue::ON_NO_ACTON,
                        ESchemaKey::ON_UPDATE => ESchemaValue::ON_NO_ACTON,
                    ],
                ],
            ],
            ...$schema,
        ];
    }
}
