<?php

namespace Modules\AccessControl\app\Http\Controllers;

use Orion\Tests\Fixtures\App\Http\Requests\RoleRequest;
use Modules\AccessControl\app\{
    Collections\RolesCollection,
    Models\Role,
    Policies\RolesPolicy,
    Resources\RoleResource
};

class RolesController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Role::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = RolesPolicy::class;
    
    /**
     * @var string $request
     */
    protected $request = RoleRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = RoleResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = RolesCollection::class;
}
