<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VentaResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'fecha_venta'    => $this->fecha_venta?->format('d/m/Y H:i'),
            'tipo_pedido'    => $this->tipo_pedido,
            'metodo_pago'    => $this->metodo_pago,
            'subtotal'       => $this->subtotal,
            'descuento'      => $this->descuento,
            'total'          => $this->total,
            'estado'         => $this->estado,
            'observaciones'  => $this->observaciones,
            'sede_id'        => $this->sede_id,
            'sede'           => $this->whenLoaded('sede', fn() => $this->sede?->nombre),
            'cliente_id'     => $this->cliente_id,
            'cliente'        => $this->whenLoaded('cliente', fn() => $this->cliente?->nombre),
            'user_id'        => $this->user_id,
            'usuario'        => $this->whenLoaded('user', fn() => $this->user?->name),
            'detalles'       => $this->whenLoaded('detalles', fn() => $this->detalles->map(fn($d) => [
                'id'              => $d->id,
                'producto_id'     => $d->producto_id,
                'producto'        => $d->producto?->nombre,
                'cantidad'        => $d->cantidad,
                'precio_unitario' => $d->precio_unitario,
                'subtotal'        => $d->subtotal,
            ])),
        ];
    }
}
