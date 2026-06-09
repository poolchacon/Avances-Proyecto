<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\SelectorSedeController;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return $user->rol === 'VENDEDOR'
            ? redirect()->route('ventas.index')
            : redirect()->route('selector-sede.index');
    }
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // ── Acceso compartido: VENDEDOR y ADMIN ──────────────────────────────
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::resource('stock', StockController::class)->only(['index', 'create', 'store']);
    Route::get('ventas/historial', [VentaController::class, 'historial'])->name('ventas.historial');
    Route::resource('ventas', VentaController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // ── Solo ADMINISTRADOR ───────────────────────────────────────────────
    Route::middleware('admin')->group(function () {
        Route::inertia('dashboard', 'Dashboard')->name('dashboard');
        Route::get('selector-sede', [SelectorSedeController::class, 'index'])->name('selector-sede.index');
        Route::post('selector-sede', [SelectorSedeController::class, 'store'])->name('selector-sede.store');
        Route::resource('sedes', SedeController::class);
        Route::resource('categorias', CategoriaController::class);
        Route::resource('clientes', ClienteController::class);
        Route::resource('productos', ProductoController::class);
        Route::resource('usuarios', UserController::class);
    });
});

require __DIR__.'/settings.php';
