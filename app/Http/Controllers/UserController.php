<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\FormUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\SedeResource;
use App\Models\User;
use App\Models\Sede;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('sede');
        $keyword = $request->input('search');
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }
        return Inertia::render('usuarios/index', [
            'search'     => $keyword,
            'collection' => UserResource::collection(
                $query->orderBy('id', 'DESC')->paginate(10)
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('usuarios/form', [
            'usuario' => new User(),
            'sedes'   => SedeResource::collection(Sede::where('estado', 'A')->get()),
        ]);
    }

    public function store(FormUserRequest $request)
    {
        $data = $request->safe()->except('password');
        $data['password'] = Hash::make($request->validated('password'));
        User::create($data);
        return to_route('usuarios.index')->with('message', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return Inertia::render('usuarios/form', [
            'usuario' => new UserResource($usuario->load('sede')),
            'sedes'   => SedeResource::collection(Sede::where('estado', 'A')->get()),
        ]);
    }

    public function update(FormUserRequest $request, User $usuario)
    {
        $data = $request->safe()->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->validated('password'));
        }
        $usuario->update($data);
        return to_route('usuarios.index')->with('message', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        $usuario->delete();
        return to_route('usuarios.index')->with('message', 'Usuario eliminado correctamente.');
    }
}
