<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\FormCategoriaRequest;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Categoria::query();
        $keyword = $request->input('search');
        if ($keyword) {
            $query->where('nombre', 'like', '%' . $keyword . '%');
        }
        return Inertia::render('categorias/index', [
            'search'     => $keyword,
            'collection' => CategoriaResource::collection(
                $query->orderBy('id', 'DESC')->paginate(10)
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('categorias/form', [
            'categoria' => new Categoria(),
        ]);
    }

    public function store(FormCategoriaRequest $request)
    {
        Categoria::create($request->validated());
        return to_route('categorias.index')->with('message', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return Inertia::render('categorias/form', [
            'categoria' => new CategoriaResource($categoria),
        ]);
    }

    public function update(FormCategoriaRequest $request, Categoria $categoria)
    {
        $categoria->update($request->validated());
        return to_route('categorias.index')->with('message', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return to_route('categorias.index')->with('message', 'Categoría eliminada correctamente.');
    }
}
