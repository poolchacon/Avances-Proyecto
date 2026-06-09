<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\FormClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();
        $keyword = $request->input('search');
        if ($keyword) {
            $query->where('nombre', 'like', '%' . $keyword . '%')
                  ->orWhere('telefono', 'like', '%' . $keyword . '%');
        }
        return Inertia::render('clientes/index', [
            'search'     => $keyword,
            'collection' => ClienteResource::collection(
                $query->orderBy('id', 'DESC')->paginate(10)
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('clientes/form', [
            'cliente' => new Cliente(),
        ]);
    }

    public function store(FormClienteRequest $request)
    {
        Cliente::create($request->validated());
        return to_route('clientes.index')->with('message', 'Cliente creado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return Inertia::render('clientes/form', [
            'cliente' => new ClienteResource($cliente),
        ]);
    }

    public function update(FormClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());
        return to_route('clientes.index')->with('message', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return to_route('clientes.index')->with('message', 'Cliente eliminado correctamente.');
    }
}
