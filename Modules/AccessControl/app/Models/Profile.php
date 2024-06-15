<?php

namespace Modules\AccessControl\app\Models;

use Modules\AccessControl\app\Enums\ERolesTypes;

class Profile extends Model
{
    use ProfileSchemeTrait;
    
    static public function getRolesTypes(): array
    {
        return [ERolesTypes::cases()];
    }
}
