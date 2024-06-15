<?php

namespace Modules\Financial\app\Http\Controllers;

use Modules\Financial\app\Collections\WalletsCollection;
use Modules\Financial\app\Http\Requests\WalletRequest;
use Modules\Financial\app\Models\Wallet;
use Modules\Financial\app\Policies\WalletsPolicy;
use Modules\Financial\app\Resources\WalletResource;

class WalletsController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Wallet::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = WalletsPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = WalletRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = WalletResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = WalletsCollection::class;
}
