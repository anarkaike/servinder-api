<?php

namespace Modules\AccessControl\app\Enums;
/**
 * Tipos de permissoes
 */
enum ERolesTypes
{
    /**
     * ROLES internos do sistema
     */
    const INTERNAL = 'internal';
    
    /**
     * ROLES / Funções  padrões com funções especiais (tenant user, tenant admin)
     */
    const DEFAULT  = 'default';
    
    /**
     * ROLES / Funções para gerenciar o saas (super admin)
     */
    const SUPER_ADMIN = 'super_admin';
    
    /**
     * ROLES criados pelos usuarios
     */
    const CUSTOM = 'custom';
}
