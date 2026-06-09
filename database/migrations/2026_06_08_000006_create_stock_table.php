<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->integer('cantidad_disponible')->default(0);
            $table->integer('stock_minimo')->default(10);
            $table->timestamps();

            $table->unique(['producto_id', 'sede_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
