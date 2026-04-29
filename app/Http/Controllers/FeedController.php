<?php

namespace App\Http\Controllers;

use App\Models\GamePost;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $followedGameIds = $user->followedGames()->pluck('game_id');
        $posts = GamePost::with(['game', 'user'])
            ->whereIn('game_id', $followedGameIds)
            ->latest()
            ->paginate(10);

        return view('feed', compact('posts'));
    }
}