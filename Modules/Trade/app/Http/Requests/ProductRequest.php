<?php

namespace Modules\Trade\App\Http\Requests;

class ProductRequest extends TradeableRequest
{
    public function rules()
    {
        return [
            ...parent::messages(),
            ...[
//            'name'         => 'required|string|unique:permissions,name,' . $this->permission,
//            'display_name' => 'required|string',
//            'description'  => 'nullable|string',
//            'type'         => 'required|string|in:' . implode(',', Permission::getAllTypes()),
            ],
        ];
    }
    
    public function authorize()
    {
        return parent::authorize() && TRUE; // O retorno dependerá da sua lógica de autorização
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
