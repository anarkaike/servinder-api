<?php

namespace App\Http\app\Http\Requests;

use Orion\Http\Requests\Request as BaseRequest;

class UsersRequest extends BaseRequest
{
    public function commonRules(): array
    {
        return [
            'title' => 'required',
        ];
    }
    
    public function storeRules(): array
    {
        return [
            'status' => 'required|in:draft,review',
        ];
    }

//    /**
//     * Get the validation rules that apply to the request.
//     */
//    public function rules(): array
//    {
//        return [
//            //
//        ];
//    }
//
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return TRUE;
//    }
}
