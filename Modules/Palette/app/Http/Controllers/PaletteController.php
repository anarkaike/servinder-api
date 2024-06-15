<?php

namespace Modules\Palette\app\Http\Controllers;

use Modules\Palette\app\Collections\PalettesCollection;
use Modules\Palette\app\Http\Requests\PaletteRequest;
use Modules\Palette\app\Models\Palette;
use Modules\Palette\app\Policies\PalettesPolicy;
use Modules\Palette\app\Resources\PaletteResource;

class PaletteController extends Controller
{
    protected $model = Palette::class;
    
    /**
     * @var string $policy
     */
    protected string $policy = PalettesPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
//    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = PaletteRequest::class;
    
    /**
     * @var string $resource
     */
    protected $resource = PaletteResource::class;
    
    /**
     * @var string $collectionResource
     */
    protected $collectionResource = PalettesCollection::class;
}
