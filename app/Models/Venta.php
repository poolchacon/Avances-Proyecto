<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $fillable = [
        'user_id', 'cliente_id', 'sede_id', 'fecha_venta',
        'tipo_pedido', 'metodo_pago',
        'monto_efectivo', 'monto_yape', 'monto_plin', 'monto_tarjeta',
        'subtotal', 'descuento', 'total', 'estado', 'observaciones',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
