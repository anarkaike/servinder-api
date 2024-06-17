<?php

namespace Modules\Users\app\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Modules\Users\app\Collections\UsersCollection;
use Modules\Users\app\Http\Requests\UsersRequest;
use Modules\Users\app\Models\User;
use Modules\Users\app\Policies\UsersPolicy;
use Modules\Users\Transformers\UserResource;

class UsersController extends Controller
{
    const SCHEMA_MODELS_CRUD = TRUE;
    /**
     * Fully-qualified model class name
     */
    protected $model = User::class;
    /**
     * @var string $policy
     */
    protected $policy = UsersPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = UsersRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = UserResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = UsersCollection::class;
    
    
    /**
     * Retrieves currently authenticated user based on the guard.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function resolveUser()
    {
        return Auth::guard('sanctum')->user();
    }
    
}
