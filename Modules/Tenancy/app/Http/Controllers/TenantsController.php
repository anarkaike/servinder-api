<?php

namespace Modules\Tenancy\app\Http\Controllers;

use Modules\Tenancy\app\Collections\TenantsCollection;
use Modules\Tenancy\app\Http\Requests\TenantRequest;
use Modules\Tenancy\app\Models\Tenant;
use Modules\Tenancy\app\Policies\TenantsPolicy;
use Modules\Tenancy\app\Resources\TenantResource;

class TenantsController extends Controller
{
    protected $model = Tenant::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = TenantsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = TenantRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = TenantResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = TenantsCollection::class;
}
