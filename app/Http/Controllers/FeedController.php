<?php

namespace App\Http\Controllers;

use App\Models\GamePost;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Muestra el feed personalizado del usuario autenticado con los posts
     * de los juegos que sigue, ordenados del más reciente al más antiguo.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        // Obtiene solo los IDs de los juegos que sigue el usuario
        // pluck() devuelve una colección simple de valores en lugar de objetos completos
        $followedGameIds = $user->followedGames()->pluck('game_id');

        // Carga los posts cuyo game_id esté en la lista de juegos seguidos
        // with() evita el problema N+1 cargando game y user en una sola query adicional
        $posts = GamePost::with(['game', 'user'])
            ->whereIn('game_id', $followedGameIds) // Filtra por los juegos seguidos
            ->latest()                             // Ordena por created_at descendente
            ->paginate(10);

        return view('feed', compact('posts'));
    }
}