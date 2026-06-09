<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\FormStockRequest;
use App\Http\Resources\StockResource;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\SedeResource;
use App\Models\Stock;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stock::with(['producto', 'sede']);
        $keyword = $request->input('search');
        $sedeId  = $request->input('sede_id');
        if ($keyword) {
            $query->whereHas('producto', fn($q) => $q->where('nombre', 'like', '%' . $keyword . '%'));
        }
        if ($sedeId) {
            $query->where('sede_id', $sedeId);
        }
        return Inertia::render('stock/index', [
            'search'     => $keyword,
            'sede_id'    => $sedeId ? (int) $sedeId : null,
            'sedes'      => SedeResource::collection(Sede::where('estado', 'A')->get()),
            'collection' => StockResource::collection(
                $query->orderBy('id', 'DESC')->paginate(15)
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('stock/movimiento', [
            'productos' => ProductoResource::collection(Producto::where('estado', 'A')->get()),
            'sedes'     => SedeResource::collection(Sede::where('estado', 'A')->get()),
        ]);
    }

    public function store(FormStockRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $stock = Stock::firstOrCreate(
                ['producto_id' => $data['producto_id'], 'sede_id' => $data['sede_id']],
                ['cantidad_disponible' => 0, 'stock_minimo' => 0]
            );

            $anterior = $stock->cantidad_disponible;

            $nuevo = match ($data['tipo']) {
                'ENTRADA' => $anterior + $data['cantidad'],
                'SALIDA'  => max(0, $anterior - $data['cantidad']),
                'AJUSTE'  => $data['cantidad'],
            };

            $stock->update(['cantidad_disponible' => $nuevo]);

            MovimientoStock::create([
                'producto_id'   => $data['producto_id'],
                'sede_id'       => $data['sede_id'],
                'tipo'          => $data['tipo'],
                'cantidad'      => $data['cantidad'],
                'stock_anterior'=> $anterior,
                'stock_nuevo'   => $nuevo,
                'user_id'       => auth()->id(),
                'motivo'        => $data['motivo'] ?? null,
            ]);
        });

        return to_route('stock.index')->with('message', 'Movimiento de stock registrado correctamente.');
    }
}
