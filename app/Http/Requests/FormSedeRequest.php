<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormSedeRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'    => 'required|string|max:100',
            'direccion' => 'nullable|string|max:200',
            'telefono'  => 'nullable|string|max:20',
            'estado'    => 'required|in:A,I',
        ];
    }
}
