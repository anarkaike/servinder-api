<?php

namespace Modules\Financial\app\Http\Controllers;

use Modules\Financial\app\Collections\ReceiptsCollection;
use Modules\Financial\app\Http\Requests\ReceiptRequest;
use Modules\Financial\app\Models\Receipt;
use Modules\Financial\app\Policies\ReceiptsPolicy;
use Modules\Financial\app\Resources\ReceiptResource;

class ReceiptsController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Receipt::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ReceiptsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ReceiptRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ReceiptResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ReceiptsCollection::class;
}
