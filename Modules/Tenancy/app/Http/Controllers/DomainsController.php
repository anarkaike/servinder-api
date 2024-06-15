<?php

namespace Modules\Tenancy\app\Http\Controllers;

use Modules\Tenancy\app\Collections\DomainsCollection;
use Modules\Tenancy\app\Http\Requests\DomainRequest;
use Modules\Tenancy\app\Models\Domain;
use Modules\Tenancy\app\Policies\DomainsPolicy;
use Modules\Tenancy\app\Resources\DomainResource;

class DomainsController extends Controller
{
    protected $model = Domain::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = DomainsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = DomainRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = DomainResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = DomainsCollection::class;
}
