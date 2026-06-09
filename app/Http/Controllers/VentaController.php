<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\FormVentaRequest;
use App\Http\Resources\VentaResource;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\ClienteResource;
use App\Http\Resources\SedeResource;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Stock;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    private function sedeActivaId(): ?int
    {
        $user = auth()->user();
        if ($user->rol === 'VENDEDOR') {
            return $user->sede_id;
        }
        return session('sede_activa_id');
    }

    // POS directo — este es el index (lo que ven cuando van a /ventas)
    public function index()
    {
        $sedeActivaId = $this->sedeActivaId();

        // Admin sin sede activa → redirigir al selector
        if (! $sedeActivaId && auth()->user()->rol === 'ADMINISTRADOR') {
            return redirect()->route('selector-sede.index');
        }

        // Stock de cada producto en la sede activa (keyed by producto_id)
        $stockSede = $sedeActivaId
            ? Stock::where('sede_id', $sedeActivaId)->pluck('cantidad_disponible', 'producto_id')->toArray()
            : [];

        return Inertia::render('ventas/create', [
            'productos'    => ProductoResource::collection(
                Producto::with('categoria')->where('estado', 'A')->orderBy('nombre')->get()
            ),
            'clientes'     => ClienteResource::collection(
                Cliente::where('estado', 'A')->orderBy('nombre')->get()
            ),
            'sedes'        => SedeResource::collection(Sede::where('estado', 'A')->get()),
            'sede_activa_id' => $sedeActivaId,
            'stock'          => $stockSede,
        ]);
    }

    // create redirige al index (POS)
    public function create()
    {
        return redirect()->route('ventas.index');
    }

    public function store(FormVentaRequest $request)
    {
        $data = $request->validated();

        // Validar stock disponible antes de procesar
        foreach ($data['items'] as $item) {
            $stock = Stock::where('producto_id', $item['producto_id'])
                          ->where('sede_id', $data['sede_id'])
                          ->first();

            if ($stock && $stock->cantidad_disponible < $item['cantidad']) {
                $producto = \App\Models\Producto::find($item['producto_id']);
                return back()->withErrors([
                    'items' => "Stock insuficiente para «{$producto->nombre}». "
                             . "Disponible: {$stock->cantidad_disponible}, solicitado: {$item['cantidad']}.",
                ])->withInput();
            }

            if ($stock && $stock->cantidad_disponible === 0) {
                $producto = \App\Models\Producto::find($item['producto_id']);
                return back()->withErrors([
                    'items' => "El producto «{$producto->nombre}» está agotado en esta sede.",
                ])->withInput();
            }
        }

        DB::transaction(function () use ($data) {
            $subtotal  = collect($data['items'])->sum(fn($i) => $i['cantidad'] * $i['precio']);
            $descuento = $data['descuento'] ?? 0;
            $total     = $subtotal - $descuento;

            $venta = Venta::create([
                'user_id'        => auth()->id(),
                'sede_id'        => $data['sede_id'],
                'cliente_id'     => $data['cliente_id'] ?? null,
                'fecha_venta'    => now(),
                'tipo_pedido'    => $data['tipo_pedido'],
                'metodo_pago'    => $data['metodo_pago'],
                'monto_efectivo' => $data['monto_efectivo'] ?? 0,
                'monto_yape'     => $data['monto_yape'] ?? 0,
                'monto_plin'     => $data['monto_plin'] ?? 0,
                'monto_tarjeta'  => $data['monto_tarjeta'] ?? 0,
                'subtotal'       => $subtotal,
                'descuento'      => $descuento,
                'total'          => $total,
                'estado'         => 'COMPLETADA',
                'observaciones'  => $data['observaciones'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                DetalleVenta::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $item['producto_id'],
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal'        => $item['cantidad'] * $item['precio'],
                ]);

                $stock = Stock::where('producto_id', $item['producto_id'])
                              ->where('sede_id', $data['sede_id'])
                              ->first();
                if ($stock) {
                    $anterior = $stock->cantidad_disponible;
                    $nuevo    = max(0, $anterior - $item['cantidad']);
                    $stock->update(['cantidad_disponible' => $nuevo]);
                    MovimientoStock::create([
                        'producto_id'    => $item['producto_id'],
                        'sede_id'        => $data['sede_id'],
                        'tipo'           => 'SALIDA',
                        'cantidad'       => $item['cantidad'],
                        'stock_anterior' => $anterior,
                        'stock_nuevo'    => $nuevo,
                        'user_id'        => auth()->id(),
                        'motivo'         => 'Venta #' . $venta->id,
                    ]);
                }
            }
        });

        return to_route('ventas.index')->with('message', 'Venta registrada correctamente.');
    }

    // Historial de ventas (ruta separada)
    public function historial(Request $request)
    {
        $query   = Venta::with(['sede', 'cliente', 'user']);
        $keyword = $request->input('search');
        $fecha   = $request->input('fecha');

        if ($keyword) {
            $query->whereHas('cliente', fn($q) => $q->where('nombre', 'like', '%' . $keyword . '%'));
        }
        if ($fecha) {
            $query->whereDate('fecha_venta', $fecha);
        }
        return Inertia::render('ventas/historial', [
            'search'     => $keyword,
            'fecha'      => $fecha,
            'collection' => VentaResource::collection(
                $query->orderBy('id', 'DESC')->paginate(15)
            ),
        ]);
    }

    public function show(Venta $venta)
    {
        return Inertia::render('ventas/show', [
            'venta' => new VentaResource($venta->load(['sede', 'cliente', 'user', 'detalles.producto'])),
        ]);
    }

    public function destroy(Venta $venta)
    {
        $venta->update(['estado' => 'ANULADA']);
        return to_route('ventas.historial')->with('message', 'Venta anulada correctamente.');
    }
}
