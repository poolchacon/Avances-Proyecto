<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sede;

class SelectorSedeController extends Controller
{
    public function index()
    {
        return Inertia::render('SelectorSede', [
            'sedes' => Sede::where('estado', 'A')->get(['id', 'nombre', 'direccion', 'telefono']),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['sede_id' => ['required', 'exists:sedes,id']]);
        session(['sede_activa_id' => (int) $request->sede_id]);
        return redirect()->route('ventas.index');
    }
}
