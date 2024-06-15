<?php

namespace Modules\Tenancy\App\Http\Controllers;

use Modules\Tenancy\app\Collections\SystemsCollection;
use Modules\Tenancy\app\Http\Requests\SystemRequest;
use Modules\Tenancy\app\Models\System;
use Modules\Tenancy\app\Policies\SystemsPolicy;
use Modules\Tenancy\app\Resources\SystemResource;

class SystemsController extends Controller
{
    protected $model = System::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = SystemsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = SystemRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = SystemResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = SystemsCollection::class;
}
