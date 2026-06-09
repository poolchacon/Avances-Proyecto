<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormVentaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'sede_id'        => ['required', 'exists:sedes,id'],
            'cliente_id'     => ['nullable', 'exists:clientes,id'],
            'tipo_pedido'    => ['required', 'in:SALON,DELIVERY,LLEVAR'],
            'metodo_pago'    => ['required', 'in:EFECTIVO,YAPE,PLIN,TARJETA,MIXTO'],
            'monto_efectivo' => ['nullable', 'numeric', 'min:0'],
            'monto_yape'     => ['nullable', 'numeric', 'min:0'],
            'monto_plin'     => ['nullable', 'numeric', 'min:0'],
            'monto_tarjeta'  => ['nullable', 'numeric', 'min:0'],
            'descuento'      => ['nullable', 'numeric', 'min:0'],
            'observaciones'  => ['nullable', 'string'],
            'items'          => ['required', 'array', 'min:1'],
            'items.*.producto_id' => ['required', 'exists:productos,id'],
            'items.*.cantidad'    => ['required', 'numeric', 'min:0.01'],
            'items.*.precio'      => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'sede_id'     => 'sede',
            'tipo_pedido' => 'tipo de pedido',
            'metodo_pago' => 'método de pago',
            'items'       => 'productos',
        ];
    }
}
