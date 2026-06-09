<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'email'   => $this->email,
            'sede_id' => $this->sede_id,
            'sede'    => $this->whenLoaded('sede', fn() => $this->sede?->nombre),
            'rol'     => $this->rol,
            'estado'  => $this->estado,
        ];
    }
}
