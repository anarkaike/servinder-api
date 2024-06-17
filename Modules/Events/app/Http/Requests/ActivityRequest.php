<?php

namespace Modules\Events\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function rules()
    {
        return [
            // Validações
        ];
    }
    
    public function authorize()
    {
        return TRUE; // O retorno dependerá da sua lógica de autorização
    }
    
    public function messages()
    {
        return [
            ...parent::messages(),
            ...[
                // mensagns das validações
            ],
        ];
    }
}
