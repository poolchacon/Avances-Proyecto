<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->dateTime('fecha_venta');
            $table->enum('tipo_pedido', ['SALON', 'DELIVERY', 'LLEVAR'])->default('SALON');
            $table->enum('metodo_pago', ['EFECTIVO', 'YAPE', 'PLIN', 'TARJETA', 'MIXTO'])->default('EFECTIVO');
            $table->decimal('monto_efectivo', 10, 2)->default(0);
            $table->decimal('monto_yape', 10, 2)->default(0);
            $table->decimal('monto_plin', 10, 2)->default(0);
            $table->decimal('monto_tarjeta', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('estado', ['COMPLETADA', 'ANULADA'])->default('COMPLETADA');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
