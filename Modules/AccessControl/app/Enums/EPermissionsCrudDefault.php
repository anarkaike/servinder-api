<?php

namespace Modules\AccessControl\app\Enums;

/**
 * Permissões padrões previstas
 */
enum EPermissionsCrudDefault
{
    const READ          = 'read';
    const LIST          = 'list';
    const CREATE        = 'create';
    const UPDATE        = 'update';
    const DELETE        = 'delete';
    const CHANCE_FIELDS = 'change_fields';
}
