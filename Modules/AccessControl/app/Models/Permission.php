<?php

namespace Modules\AccessControl\app\Models;

use Modules\AccessControl\app\Enums\{
    EPermissionsCrudDefault,
    EPermissionsFeaturesDefault,
    EPermissionsTypes,
};

class Permission extends Model
{
    protected $table = 'permissions';
    use PermissionSchemeTrait;
    
    const SCHEME_SYNC_ACTIVED      = TRUE;
    const SCHEME_SYNC_EDIT_ACTIVED = TRUE;
    
    static public function getAllTypes(): array
    {
        return [EPermissionsTypes::cases()];
    }
    
    static public function getPermissionsCrudDefault(): array
    {
        return [EPermissionsCrudDefault::cases()];
    }
    
    static public function getPermissionsFeaturesDefault(): array
    {
        return [EPermissionsFeaturesDefault::cases()];
    }
}
