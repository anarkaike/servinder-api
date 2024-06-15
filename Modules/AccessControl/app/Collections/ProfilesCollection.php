<?php

namespace Modules\AccessControl\app\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfilesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data'  => $this->collection,
            'meta'  => [
                'count'             => $this->collection->count(),
                'permissions_count' => $this->collection->sum(
                    function ($permission)
                    {
                        return $permission->roles->count();
                    },
                ),
            ],
            'links' => [
                'self' => 'link-to-self',
            ],
        ];
    }
    
    /* Implemente seus próprios métodos personalizados aqui. */
}
