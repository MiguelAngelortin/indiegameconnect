<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
 * Punto de entrada de configuración de la aplicación Laravel.
 * Define el enrutamiento, los middleware globales y el manejo de excepciones.
 */
return Application::configure(basePath: dirname(__DIR__))

    /*
     * Registro de archivos de rutas de la aplicación.
     * web.php — rutas HTTP accesibles desde el navegador.
     * console.php — comandos Artisan personalizados.
     * health: '/up' — ruta de comprobación de estado para Railway y otros servicios de hosting.
     */
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // Registra el middleware de roles con el alias 'role' para usarlo en rutas
        // Uso en rutas: ->middleware('role:developer') o ->middleware('role:admin')
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Añade SetLocale al stack de middleware web para que se ejecute en cada petición
        // Al estar en 'web', se aplica a todas las rutas del navegador automáticamente
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        // Captura globalmente la excepción de throttle (429 Too Many Requests)
        // En lugar de mostrar la página de error 429, redirige hacia atrás con un mensaje flash
        // El mensaje se muestra en la vista mediante session('throttle_error')
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
            return redirect()->back()->with('throttle_error', 'You have reached the limit of 1 game per day. Please try again tomorrow.');
        });
    })

    ->create();