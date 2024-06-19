<?php

namespace ModelSchemas\Traits;

use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;
use ModelSchemas\Enums\ESchemaValue;

trait AddDefaultColumnsTrait
{
    const ID                            = 'id';
    const TENANT_ID                     = 'tenant_id';
    const ALLOW_RECREATE_TABLE_IN_ORDER = TRUE;
    
    use AddAuditColumnsTrait;
    
    /**
     * Adiciona as colunas que devem existir em todas as tabelas
     *
     * @param array $schema
     *
     * @return array
     */
    private function addDefaultColumns(array &$schema, $position = 0): array
    {
        $schema = $this->addPrimaryKeyColumn($schema, $position);
        $schema = $this->addTenantIdColumns($schema, $position);
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
    private function addPrimaryKeyColumn(array &$schema): void
    {
        $schema = [
            ...[
                self::ID => [
                    ESchemaKey::TYPE           => EColumnType::UNSIGNED_INTEGER,
                    ESchemaKey::PRIMARY_KEY    => TRUE,
                    ESchemaKey::AUTO_INCREMENT => TRUE,
                    ESchemaKey::NOT_NULL       => TRUE,
                    ESchemaKey::LABEL          => 'ID',
                    ESchemaKey::DESCRIPTION    => 'ID do registro.',
                    ESchemaKey::POSITION       => 0,
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
    private function addTenantIdColumns(array &$schema): void
    {
        $schema = [
            ...[
                self::TENANT_ID => [
                    ESchemaKey::TYPE        => EColumnType::UNSIGNED_INTEGER,
                    ESchemaKey::NULLABLE    => TRUE,
                    ESchemaKey::LABEL       => 'Tenant ID',
                    ESchemaKey::DESCRIPTION => 'ID do Tenant.',
                    ESchemaKey::POSITION    => 1,
//                    ESchemaKey::ON          => [
//                        ESchemaKey::ON_FK     => 'tenants',
//                        ESchemaKey::ON_COLUMN => 'id',
//                        ESchemaKey::ON_DELETE => ESchemaValue::ON_NO_ACTION,
//                        ESchemaKey::ON_UPDATE => ESchemaValue::ON_NO_ACTION,
//                    ],
                ],
            ],
            ...$schema,
        ];
    }
}
