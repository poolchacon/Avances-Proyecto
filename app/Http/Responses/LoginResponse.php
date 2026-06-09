<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->rol === 'VENDEDOR') {
            // Vendedor va directo al POS con su sede asignada
            session(['sede_activa_id' => (int) $user->sede_id]);
            return redirect()->route('ventas.index');
        }

        // Admin elige sede
        return redirect()->route('selector-sede.index');
    }
}
