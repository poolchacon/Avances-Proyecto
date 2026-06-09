<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $table = 'stock';

    protected $fillable = ['producto_id', 'sede_id', 'cantidad_disponible', 'stock_minimo'];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoStock::class, 'producto_id', 'producto_id')
            ->where('sede_id', $this->sede_id);
    }
}
