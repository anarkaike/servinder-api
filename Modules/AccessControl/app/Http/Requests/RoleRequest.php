<?php

namespace Modules\AccessControl\app\Http\Requests;

use Modules\AccessControl\app\Models\Permission;
use Orion\Tests\Fixtures\App\Http\Requests\RoleRequest as BaseRoleRequest;

class RoleRequest extends BaseRoleRequest
{
    public function rules()
    {
        return [
            ...parent::rules(),
            ...[
//                'name'         => 'required|string|unique:permissions,name,' . $this->permission,
//                'display_name' => 'required|string',
//                'description'  => 'nullable|string',
//                'type'         => 'required|string|in:' . implode(',', Permission::getAllTypes()),
            ],
        ];
    }
    
    public function authorize()
    {
        return parent::authorize();
    }
    
    public function messages()
    {
        return [
            ...parent::messages(),
            ...[
//            'name.unique'           => 'Já existe uma permissão com esse nome.',
//            'name.required'         => 'O campo nome é obrigatório.',
//            'display_name.required' => 'O campo nome de exibição é obrigatório.',
//            'type.in'               => 'O tipo selecionado é inválido.',
            ],
        ];
    }
}
