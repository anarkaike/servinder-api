<?php

namespace Modules\Events\app\Http\Controllers;

use Modules\Events\App\Collections\EventsCollection;
use Modules\Events\App\Http\Requests\EventRequest;
use Modules\Events\app\Models\Event;
use Modules\Events\App\Policies\EventsPolicy;
use Modules\Events\App\Resources\EventResource;

class EventsController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Event::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = EventsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = EventRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = EventResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = EventsCollection::class;
}
