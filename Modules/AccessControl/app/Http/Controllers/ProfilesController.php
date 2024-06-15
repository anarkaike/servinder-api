<?php

namespace Modules\AccessControl\app\Http\Controllers;

use Modules\AccessControl\app\{
    Collections\ProfilesCollection,
    Http\Requests\ProfileRequest,
    Policies\ProfilesPolicy,
    Resources\ProfileResource,
    Models\Profile,
};

class ProfilesController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Profile::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ProfilesPolicy::class;
    
    /**
     * @var string $request
     */
    protected $request = ProfileRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ProfileResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ProfilesCollection::class;
}
