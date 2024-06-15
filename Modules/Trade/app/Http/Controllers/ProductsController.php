<?php

namespace Modules\Trade\app\Http\Controllers;

use Modules\Trade\app\Collections\ProductsCollection;
use Modules\Trade\app\Http\Requests\ProductRequest;
use Modules\Trade\app\Models\Product;
use Modules\Trade\app\Policies\ProductsPolicy;
use Modules\Trade\app\Resources\ProductResource;

class ProductsController extends Controller
{
    protected $model = Product::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = ProductsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = ProductRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = ProductResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = ProductsCollection::class;
}
