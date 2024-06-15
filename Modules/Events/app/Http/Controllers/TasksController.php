<?php

namespace Modules\Events\App\Http\Controllers;

use Modules\Events\App\Collections\TasksCollection;
use Modules\Events\App\Http\Requests\TaskRequest;
use Modules\Events\app\Models\Task;
use Modules\Events\App\Policies\TasksPolicy;
use Modules\Events\App\Resources\TaskResource;

class TasksController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Task::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = TasksPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = TaskRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = TaskResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = TasksCollection::class;
}
