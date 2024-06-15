<?php

namespace Modules\Palette\app\Http\Controllers;

use Modules\Palette\app\Collections\PalettesTypesCollection;
use Modules\Palette\app\Http\Requests\PaletteValueRequest;
use Modules\Palette\app\Models\PaletteValue;
use Modules\Palette\app\Policies\PalettesTypesPolicy;
use Modules\Palette\app\Resources\PaletteValueResource;

class PaletteValueController extends Controller
{
    protected $model = PaletteValue::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = PalettesTypesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = PaletteValueRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = PaletteValueResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = PalettesTypesCollection::class;
}
