<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormClienteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'     => 'required|string|max:100',
            'telefono'   => 'required|string|max:20',
            'direccion'  => 'nullable|string|max:200',
            'email'      => 'nullable|email|max:100',
            'referencia' => 'nullable|string|max:200',
            'estado'     => 'required|in:A,I',
        ];
    }
}
