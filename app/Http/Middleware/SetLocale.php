<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Establece el idioma de la aplicación en cada petición según la preferencia
     * guardada en sesión. Si no hay idioma en sesión, usa el idioma por defecto
     * definido en config/app.php (APP_LOCALE en .env).
     * Este middleware se ejecuta en cada petición al estar registrado globalmente
     * en bootstrap/app.php.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lee el idioma guardado en sesión; si no existe usa el idioma por defecto de la app
        $locale = session('locale', config('app.locale'));

        // Aplica el idioma a la instancia de la aplicación para esta petición
        // Esto afecta a todas las llamadas a __() y trans() en vistas y controladores
        app()->setLocale($locale);

        // Deja pasar la petición al siguiente middleware o controlador
        return $next($request);
    }
}