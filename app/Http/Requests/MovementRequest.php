<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'stock_status_id' => 'required|integer|exists:stock_status,id',
            'date_movement' => 'required|date',
            'quantity' => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'El ID del producto es obligatorio.',
            'product_id.integer' => 'El ID del producto debe ser un número entero.',
            'product_id.exists' => 'El producto especificado no existe.',
            'stock_status_id.required' => 'El ID del estado de stock es obligatorio.',
            'stock_status_id.integer' => 'El ID del estado de stock debe ser un número entero.',
            'stock_status_id.exists' => 'El estado de stock especificado no existe.',
            'date_movement.required' => 'La fecha del movimiento es obligatoria.',
            'date_movement.date' => 'La fecha del movimiento debe ser una fecha válida.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.numeric' => 'La cantidad debe ser un número.',
            'quantity.min' => 'La cantidad debe ser al menos 1.',
        ];
    }
}
