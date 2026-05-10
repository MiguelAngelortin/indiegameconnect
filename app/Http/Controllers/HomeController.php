<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\GameFollow;
use App\Models\UserFollow;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Muestra la página principal con rankings, juegos en desarrollo,
     * un juego aleatorio y la detección de primera visita para el modal
     * de bienvenida.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Comprueba si el usuario ya visitó la web buscando la cookie 'visited'
        // Si no existe la cookie, es la primera visita y se mostrará el modal de bienvenida
        $firstVisit = !request()->cookie('visited');

        // Top 5 juegos más seguidos en los últimos 30 días
        // withCount con closure filtra el conteo solo al último mes, no todos los follows históricos
        $topGames = Game::with(['genres', 'user'])
            ->withCount(['follows' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('follows_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 developers y admins más seguidos en los últimos 30 días
        $topDevelopers = User::where('role', 'developer')
            ->orWhere('role', 'admin')
            ->withCount(['follows' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('follows_count', 'desc')
            ->take(5)
            ->get();

        // Juegos actualmente en desarrollo (alpha o beta), ordenados del más reciente
        $inDevelopment = Game::with(['genres', 'user'])
            ->whereIn('status', ['alpha', 'beta'])
            ->latest()
            ->take(5)
            ->get();

        // Juego aleatorio para el botón "Feel Lucky?" de la home
        $randomGame = Game::inRandomOrder()->first();

        // Se construye la respuesta manualmente en lugar de usar return view()
        // porque necesitamos adjuntar una cookie a la respuesta HTTP
        $response = response()->view('home', compact(
            'topGames', 'topDevelopers', 'inDevelopment', 'randomGame', 'firstVisit'
        ));

        // Solo se escribe la cookie en la primera visita — dura 1 año (60min * 24h * 365días)
        // Las visitas siguientes ya tendrán la cookie y no verán el modal de bienvenida
        if ($firstVisit) {
            $response->cookie('visited', 'true', 60 * 24 * 365);
        }

        return $response;
    }
}