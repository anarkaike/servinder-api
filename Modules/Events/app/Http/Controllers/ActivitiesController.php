<?php

namespace Modules\Events\App\Http\Controllers;

use Modules\Events\App\Collections\ActivitiesCollection;
use Modules\Events\App\Http\Requests\ActivityRequest;
use Modules\Events\app\Models\Activity;
use Modules\Events\App\Policies\ActivitiesPolicy;
use Modules\Events\App\Resources\ActivityResource;

class ActivitiesController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Activity::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ActivitiesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ActivityRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ActivityResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ActivitiesCollection::class;
}
