<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormStockRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'producto_id' => ['required', 'exists:productos,id'],
            'sede_id'     => ['required', 'exists:sedes,id'],
            'tipo'        => ['required', 'in:ENTRADA,SALIDA,AJUSTE'],
            'cantidad'    => ['required', 'numeric', 'min:0.01'],
            'motivo'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'producto_id' => 'producto',
            'sede_id'     => 'sede',
            'tipo'        => 'tipo de movimiento',
            'cantidad'    => 'cantidad',
            'motivo'      => 'motivo',
        ];
    }
}
