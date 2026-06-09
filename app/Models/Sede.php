<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sede extends Model
{
    protected $fillable = ['nombre', 'direccion', 'telefono', 'estado'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
