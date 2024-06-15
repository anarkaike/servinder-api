<?php

namespace Modules\Financial\app\Http\Controllers;

use Modules\Financial\app\Collections\ProposalsCollection;
use Modules\Financial\app\Http\Requests\ProposalRequest;
use Modules\Financial\app\Models\Proposal;
use Modules\Financial\app\Policies\ProposalsPolicy;
use Modules\Financial\app\Resources\ProposalResource;

class ProposalsController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Proposal::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ProposalsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ProposalRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ProposalResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ProposalsCollection::class;
}
