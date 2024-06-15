<?php

namespace Modules\Events\App\Http\Controllers;

use Modules\Events\App\Collections\ExperiencesCollection;
use Modules\Events\App\Http\Requests\ExperienceRequest;
use Modules\Events\app\Models\Experience;
use Modules\Events\App\Policies\ExperiencesPolicy;
use Modules\Events\App\Resources\ExperienceResource;

class ExperiencesController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Experience::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ExperiencesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ExperienceRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ExperienceResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ExperiencesCollection::class;
}
