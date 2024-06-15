<?php

namespace Modules\Trade\App\Resources;

class SpaceResource extends TradeableResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
