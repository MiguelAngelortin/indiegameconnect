<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use App\Models\GameFollow;
use App\Models\UserFollow;

class HomeController extends Controller
{
    public function index()
    {
        // Top 5 juegos más seguidos del mes
        $topGames = Game::with(['genres', 'user'])
            ->withCount(['follows' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('follows_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 developers del mes
        $topDevelopers = User::where('role', 'developer')
            ->orWhere('role', 'admin')
            ->withCount(['follows' => function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('follows_count', 'desc')
            ->take(5)
            ->get();

        // Juegos en desarrollo
        $inDevelopment = Game::with(['genres', 'user'])
            ->whereIn('status', ['alpha', 'beta'])
            ->latest()
            ->take(5)
            ->get();

        // Juego aleatorio
        $randomGame = Game::inRandomOrder()->first();

        return view('home', compact('topGames', 'topDevelopers', 'inDevelopment', 'randomGame'));
    }
}