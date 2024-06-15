<?php

namespace Modules\Trade\App\Http\Controllers;

use Modules\Trade\app\Collections\SpacesCollection;
use Modules\Trade\app\Http\Requests\SpaceRequest;
use Modules\Trade\app\Models\Space;
use Modules\Trade\app\Policies\SpacesPolicy;
use Modules\Trade\app\Resources\SpaceResource;

class SpacesController extends Controller
{
    protected $model = Space::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = SpacesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = SpaceRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = SpaceResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = SpacesCollection::class;
}
