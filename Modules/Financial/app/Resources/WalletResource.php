<?php

namespace Modules\Financial\app\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\AccessControl\app\Resources\RoleResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            // Aqui, estou assumindo que a sua classe Permission tem estes três campos no banco de dados
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            // as funções data e hora do Laravel são úteis para formatar datas e horas para a apresentação
            'created_at'  => $this->created_at->format('d-m-Y'),
            'updated_at'  => $this->updated_at->format('d-m-Y'),
            // Inclua também uma lista de roles que têm essa permissão.
            // Aqui, estou assumindo que você tem uma relação many-to-many entre Permissions e Roles
            'roles'       => RoleResource::collection($this->whenLoaded('roles')),
        ];
    }
}
