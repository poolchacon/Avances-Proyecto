<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormCategoriaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'      => 'required|string|max:50',
            'descripcion' => 'nullable|string|max:200',
            'estado'      => 'required|in:A,I',
        ];
    }
}
