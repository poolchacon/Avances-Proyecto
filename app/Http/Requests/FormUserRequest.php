<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class FormUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId = $this->route('usuario')?->id;
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $userId],
            'password' => $userId ? ['nullable', 'string', 'min:8'] : ['required', 'string', 'min:8'],
            'sede_id'  => ['required', 'exists:sedes,id'],
            'rol'      => ['required', 'in:ADMINISTRADOR,VENDEDOR'],
            'estado'   => ['required', 'in:A,I'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'nombre completo',
            'email'    => 'correo electrónico',
            'password' => 'contraseña',
            'sede_id'  => 'sede',
            'rol'      => 'rol',
            'estado'   => 'estado',
        ];
    }
}
