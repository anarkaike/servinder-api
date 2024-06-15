<?php

namespace Modules\Palette\app\Http\Controllers;

use Modules\Palette\app\Collections\PalettesTypesCollection;
use Modules\Palette\app\Http\Requests\PaletteTypeRequest;
use Modules\Palette\app\Models\PaletteType;
use Modules\Palette\app\Policies\PalettesTypesPolicy;
use Modules\Palette\app\Resources\PaletteTypeResource;

class PaletteTypeController extends Controller
{
    protected $model = PaletteType::class;
    
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
    protected $request = PaletteTypeRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = PaletteTypeResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = PalettesTypesCollection::class;
}
