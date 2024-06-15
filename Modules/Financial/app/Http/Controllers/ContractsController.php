<?php

namespace Modules\Financial\app\Http\Controllers;

use Modules\Financial\app\Collections\ContractsCollection;
use Modules\Financial\app\Http\Requests\ContractRequest;
use Modules\Financial\app\Models\Contract;
use Modules\Financial\app\Policies\ContractsPolicy;
use Modules\Financial\app\Resources\ContractResource;

class ContractsController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Contract::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ContractsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ContractRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ContractResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ContractsCollection::class;
}
