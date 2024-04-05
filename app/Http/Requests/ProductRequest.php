<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'El código del producto es obligatorio.',
            'description.required' => 'La descripción del producto es obligatoria.',
            'quantity.required' => 'La cantidad del producto es obligatorio.'
        ];
    }
}
