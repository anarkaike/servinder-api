<?php

namespace Modules\AccessControl\app\Enums;
/**
 * Tipos de permissoes
 */
enum EPermissionsTypes
{
    /**
     * Permissoes de Baixo Nivel - Controle direto ao model
     *
     * Permissão explicita de acesso a um registro da tabela,
     * a uma ação em um model
     */
    const ACCESS_RESORCES = 'accessResources';
    
    /**
     * Permissoes de Alto Nivel - Controle indireto aos models
     *
     * Permissões para o front end e back end controlar
     * acessos a funcionalidades complexas espefificas
     * (que não é crud)
     *
     *  Screens é uma feature que o front end pode usar
     *  para controlar o acesso a tela e visualizacao de menus
     */
    const ACCESS_FEATURES = 'accessFeatures';
}
