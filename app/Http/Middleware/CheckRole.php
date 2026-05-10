<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Comprueba que el usuario autenticado tiene el rol necesario para acceder a la ruta.
     * Los administradores tienen acceso a cualquier ruta protegida por rol,
     * independientemente del rol requerido.
     * Este middleware se aplica en las rutas con el alias 'role' definido en bootstrap/app.php.
     * Uso en rutas: ->middleware('role:developer')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Rol mínimo requerido para acceder a la ruta
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // El admin siempre puede acceder, o el usuario tiene exactamente el rol requerido
        if ($request->user()->role === 'admin' || $request->user()->role === $role) {
            return $next($request); // Deja pasar la petición al siguiente middleware o controlador
        }

        // Si no cumple ninguna condición, redirige a la home sin dar detalles del motivo
        return redirect('/');
    }
}