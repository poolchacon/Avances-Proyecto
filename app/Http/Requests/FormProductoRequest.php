<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormProductoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'       => 'required|string|max:100',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'estado'       => 'required|in:A,I',
            'imagen'       => ['nullable', 'image', 'max:2048'],
        ];
    }
}
