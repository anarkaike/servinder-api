<?php

namespace Modules\Trade\App\Http\Controllers;

use Modules\Trade\app\Collections\ServicesCollection;
use Modules\Trade\app\Http\Requests\ServiceRequest;
use Modules\Trade\app\Models\Service;
use Modules\Trade\app\Policies\ServicesPolicy;
use Modules\Trade\app\Resources\ServiceResource;

class ServicesController extends Controller
{
    protected $model = Service::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ServicesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ServiceRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ServiceResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ServicesCollection::class;
}
