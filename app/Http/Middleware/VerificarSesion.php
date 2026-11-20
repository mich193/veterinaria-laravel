<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerificarSesion
{
    // Revisa que el usuario haya iniciado sesión, si no lo regresa al login
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('veterinario_id')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Inicia sesión para continuar.']);
        }

        $respuesta = $next($request);

        // para que al cerrar sesión no se pueda regresar con el botón "atrás" del navegador
        $respuesta->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $respuesta;
    }
}
