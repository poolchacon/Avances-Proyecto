<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'nombre'     => $this->nombre,
            'telefono'   => $this->telefono,
            'direccion'  => $this->direccion,
            'email'      => $this->email,
            'referencia' => $this->referencia,
            'estado'     => $this->estado,
        ];
    }
}
