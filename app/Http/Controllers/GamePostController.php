<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePost;


class GamePostController extends Controller
{
    public function create($game_id)
{
    $game = Game::findOrFail($game_id);
    return view('games.posts.create', compact('game'));
}

public function store(Request $request, $game_id)
{
    $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
        'image_url' => ['nullable', 'string'],
    ]);

    GamePost::create([
        'game_id' => $game_id,
        'user_id' => auth()->user()->id,
        'title' => $request->title,
        'content' => $request->content,
        'image_url' => $request->image_url,
    ]);

    return redirect('/games/' . $game_id);
}
}

