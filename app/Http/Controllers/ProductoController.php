<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Inertia\Inertia;
use App\Http\Requests\FormProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Http\Resources\CategoriaResource;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');
        $keyword = $request->input('search');
        if ($keyword) {
            $query->where('nombre', 'like', '%' . $keyword . '%');
        }
        return Inertia::render('productos/index', [
            'search'     => $keyword,
            'collection' => ProductoResource::collection(
                $query->orderBy('id', 'DESC')->paginate(10)
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('productos/form', [
            'producto'   => new Producto(),
            'categorias' => CategoriaResource::collection(Categoria::where('estado', 'A')->get()),
        ]);
    }

    public function store(FormProductoRequest $request)
    {
        $producto = Producto::create($request->safe()->except('imagen'));
        $this->handleImagen($producto, $request);
        return to_route('productos.index')->with('message', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return Inertia::render('productos/form', [
            'producto'   => new ProductoResource($producto),
            'categorias' => CategoriaResource::collection(Categoria::where('estado', 'A')->get()),
        ]);
    }

    public function update(FormProductoRequest $request, Producto $producto)
    {
        $producto->update($request->safe()->except('imagen'));
        $this->handleImagen($producto, $request);
        return to_route('productos.index')->with('message', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return to_route('productos.index')->with('message', 'Producto eliminado correctamente.');
    }

    private function handleImagen(Producto $producto, FormProductoRequest $request): void
    {
        $imagen = $request->validated('imagen');
        if ($imagen && $imagen instanceof UploadedFile) {
            $producto->addMedia($imagen)->toMediaCollection('imagen');
        }
    }
}
