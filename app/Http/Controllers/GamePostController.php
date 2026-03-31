<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePost;
use App\Models\GamePostLike;
use App\Models\GamePostComment;


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

public function show($game_id, $post_id)
{
    $game = Game::findOrFail($game_id);
    $post = GamePost::findOrFail($post_id);
     $comments = $post->comments()->whereNull('parent_id')->orderBy('created_at', 'desc')->with('replies')->get();
    return view('games.posts.show', compact('game', 'post','comments'));
}

public function like($game_id, $post_id)
{
    $like = GamePostLike::where('game_post_id', $post_id)
        ->where('user_id', auth()->user()->id)
        ->first();

    if ($like) {
        $like->delete();
    } else {
        GamePostLike::create([
            'game_post_id' => $post_id,
            'user_id' => auth()->user()->id,
        ]);
    }

    return redirect()->back();
}

public function storeComment(Request $request, $game_id, $post_id)
{
    $request->validate([
        'content' => ['required', 'string'],
    ]);

    GamePostComment::create([
        'game_post_id' => $post_id,
        'user_id' => auth()->user()->id,
        'content' => $request->content,
        'parent_id' => $request->parent_id ?? null,
    ]);

    return redirect()->back();
}
}

