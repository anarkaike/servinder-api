<?php

namespace Modules\Tenancy\App\Http\Controllers;

use Modules\Tenancy\app\Collections\ModulesCollection;
use Modules\Tenancy\app\Http\Requests\ModuleRequest;
use Modules\Tenancy\app\Models\Module;
use Modules\Tenancy\app\Policies\ModulesPolicy;
use Modules\Tenancy\app\Resources\ModuleResource;

class ModulesController extends Controller
{
    protected $model = Module::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ModulesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ModuleRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ModuleResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ModulesCollection::class;
}
