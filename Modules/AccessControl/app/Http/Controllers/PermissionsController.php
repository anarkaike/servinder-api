<?php

namespace Modules\AccessControl\app\Http\Controllers;

use Modules\AccessControl\app\{
    Collections\PermissionsCollection,
    Http\Requests\PermissionRequest,
    Models\Permission,
    Policies\PermissionsPolicy,
    Resources\PermissionResource,
};

class PermissionsController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Permission::class;
    /**
     * @var string $policy
     */
    protected string $policy = PermissionsPolicy::class;
    
    /**
     * @var string $request
     */
    protected $request = PermissionRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = PermissionResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = PermissionsCollection::class;
    
}
