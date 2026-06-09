<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\FormSedeRequest;
use App\Http\Resources\SedeResource;
use App\Models\Sede;

class SedeController extends Controller
{
    public function index(Request $request)
    {
        $query = Sede::query();
        $keyword = $request->input('search');
        if ($keyword) {
            $query->where('nombre', 'like', '%' . $keyword . '%');
        }
        return Inertia::render('sedes/index', [
            'search'     => $keyword,
            'collection' => SedeResource::collection(
                $query->orderBy('id', 'DESC')->paginate(10)
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('sedes/form', [
            'sede' => new Sede(),
        ]);
    }

    public function store(FormSedeRequest $request)
    {
        Sede::create($request->validated());
        return to_route('sedes.index')->with('message', 'Sede creada correctamente.');
    }

    public function edit(Sede $sede)
    {
        return Inertia::render('sedes/form', [
            'sede' => new SedeResource($sede),
        ]);
    }

    public function update(FormSedeRequest $request, Sede $sede)
    {
        $sede->update($request->validated());
        return to_route('sedes.index')->with('message', 'Sede actualizada correctamente.');
    }

    public function destroy(Sede $sede)
    {
        $sede->delete();
        return to_route('sedes.index')->with('message', 'Sede eliminada correctamente.');
    }
}
