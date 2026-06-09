<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'producto_id'         => $this->producto_id,
            'producto'            => $this->whenLoaded('producto', fn() => $this->producto?->nombre),
            'sede_id'             => $this->sede_id,
            'sede'                => $this->whenLoaded('sede', fn() => $this->sede?->nombre),
            'cantidad_disponible' => $this->cantidad_disponible,
            'stock_minimo'        => $this->stock_minimo,
            'bajo_minimo'         => $this->cantidad_disponible <= $this->stock_minimo,
        ];
    }
}
