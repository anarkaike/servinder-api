<?php

namespace Modules\Events\App\Http\Controllers;

use Modules\Events\App\Collections\EventsTypesCollection;
use Modules\Events\App\Http\Requests\EventTypeRequest;
use Modules\Events\app\Models\EventType;
use Modules\Events\App\Policies\EventsTypesPolicy;
use Modules\Events\App\Resources\EventTypeResource;

class EventsTypesController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = EventType::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = EventsTypesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = EventTypeRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = EventTypeResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = EventsTypesCollection::class;
}
