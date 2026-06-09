<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Sede;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $user    = auth()->user();
        $esAdmin = $user->rol === 'ADMINISTRADOR';

        // VENDEDOR: siempre ve solo su propia sede, sin importar el filtro
        if (! $esAdmin) {
            $sedeId = $user->sede_id;
        } else {
            $sedeId = $request->input('sede_id', session('sede_activa_id'));
        }

        $desde  = $request->input('desde', now()->startOfMonth()->toDateString());
        $hasta  = $request->input('hasta', now()->toDateString());

        $query = Venta::where('estado', 'COMPLETADA')
            ->whereBetween(DB::raw('DATE(fecha_venta)'), [$desde, $hasta]);

        if ($sedeId) {
            $query->where('sede_id', $sedeId);
        }

        $totales = (clone $query)->selectRaw('COUNT(*) as total_ventas, SUM(total) as total_ingresos')->first();

        $porMetodo = (clone $query)
            ->selectRaw('metodo_pago, COUNT(*) as cantidad, SUM(total) as monto')
            ->groupBy('metodo_pago')
            ->get();

        $porSede = Venta::where('estado', 'COMPLETADA')
            ->whereBetween(DB::raw('DATE(fecha_venta)'), [$desde, $hasta])
            ->selectRaw('sede_id, COUNT(*) as cantidad, SUM(total) as monto')
            ->with('sede:id,nombre')
            ->groupBy('sede_id')
            ->get();

        $topProductos = DetalleVenta::select('producto_id', DB::raw('SUM(cantidad) as total_cantidad'), DB::raw('SUM(subtotal) as total_monto'))
            ->whereHas('venta', function ($q) use ($desde, $hasta, $sedeId) {
                $q->where('estado', 'COMPLETADA')
                  ->whereBetween(DB::raw('DATE(fecha_venta)'), [$desde, $hasta]);
                if ($sedeId) $q->where('sede_id', $sedeId);
            })
            ->with('producto:id,nombre')
            ->groupBy('producto_id')
            ->orderByDesc('total_cantidad')
            ->limit(10)
            ->get();

        $ventasPorDia = (clone $query)
            ->selectRaw('DATE(fecha_venta) as fecha, COUNT(*) as cantidad, SUM(total) as monto')
            ->groupBy(DB::raw('DATE(fecha_venta)'))
            ->orderBy('fecha')
            ->get();

        return Inertia::render('reportes/index', [
            'desde'        => $desde,
            'hasta'        => $hasta,
            'sede_id'      => $sedeId ? (int) $sedeId : null,
            'es_admin'     => $esAdmin,
            // VENDEDOR no recibe lista de sedes (no puede cambiarla)
            'sedes'        => $esAdmin ? Sede::where('estado', 'A')->get(['id', 'nombre']) : [],
            'totales'      => $totales,
            'por_metodo'   => $porMetodo,
            'por_sede'     => $esAdmin ? $porSede : [],   // no mostrar desglose de otras sedes
            'top_productos'=> $topProductos,
            'ventas_dia'   => $ventasPorDia,
        ]);
    }
}
