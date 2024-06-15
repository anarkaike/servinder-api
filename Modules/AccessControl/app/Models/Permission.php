<?php

namespace Modules\AccessControl\app\Models;

use Modules\AccessControl\app\Enums\{
    EPermissionsCrudDefault,
    EPermissionsFeaturesDefault,
    EPermissionsTypes,
};

class Permission extends Model
{
    use PermissionSchemeTrait;
    
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
