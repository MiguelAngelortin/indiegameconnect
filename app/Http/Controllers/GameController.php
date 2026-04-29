<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameFollow;
use App\Models\Genre;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::all();
        $games = Game::with(['genres', 'user'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%');
            })
            ->when($request->genre, function ($query) use ($request) {
                $query->whereHas('genres', function ($q) use ($request) {
                    $q->where('genres.id', $request->genre);
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->followed_by, function ($query) use ($request) {
                $query->whereHas('followers', function ($q) use ($request) {
                    $q->where('user_id', $request->followed_by);
                });
            })
            ->paginate(12);

        return view('games', compact('games', 'genres'));
    }

    public function show($game_id)
    {
        $game = Game::with(['user', 'genres'])->findOrFail($game_id);
        $posts = $game->gamePosts()->orderBy('created_at', 'desc')->paginate(5);

        return view('games.show', compact('game', 'posts'));
    }

    public function create()
    {
        $genres = Genre::all();

        return view('games.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'genres' => ['required', 'array', 'min:1'],
            'status' => ['required', 'in:alpha,beta,release,cancelled'],
            'engine' => ['required', 'in:Unity,Unreal,Godot,GameMaker,Other'],
            'publisher' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'string'],
            'download_url' => ['nullable', 'string'],
            'version' => ['nullable', 'string'],
        ]);

        // ver usuario actual
        //  dd(auth()->user());

        $game = Game::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'status' => $request->status,
            'engine' => $request->engine,
            'publisher' => $request->publisher,
            'release_date' => $request->release_date,
            'cover_image' => $request->cover_image,
            'download_url' => $request->download_url,
            'version' => $request->version,
        ]);
        $game->genres()->attach($request->genres);

        return redirect('/games/'.$game->id);
    }

    public function edit($game_id)
    {
        $game = Game::with('genres')->findOrFail($game_id);
        $genres = Genre::all();
        if (auth()->user()->id !== $game->user_id) {
            return redirect('/games/'.$game_id);
        }

        return view('games.edit', compact('game', 'genres'));
    }

    public function update(Request $request, $game_id)
    {
        $game = Game::findOrFail($game_id);
        if (auth()->user()->id !== $game->user_id) {
            return redirect('/games/'.$game_id);
        }
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'genres' => ['required', 'array', 'min:1'],
            'status' => ['required', 'in:alpha,beta,release,cancelled'],
            'engine' => ['required', 'in:Unity,Unreal,Godot,GameMaker,Other'],
            'publisher' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'string'],
            'download_url' => ['nullable', 'string'],
            'version' => ['nullable', 'string'],
        ]);
        $game->update($request->except('genres', '_token', '_method'));
        $game->genres()->sync($request->genres);

        return redirect('/games/'.$game_id);
    }

    public function destroy($game_id)
    {
        $game = Game::findOrFail($game_id);
        if (auth()->user()->id !== $game->user_id) {
            return redirect('/games/'.$game_id);
        }
        $game->delete();

        return redirect('/games');
    }

    public function follow($game_id)
    {
        $follow = GameFollow::where('user_id', auth()->user()->id)
            ->where('game_id', $game_id)
            ->first();

        if ($follow) {
            $follow->delete();
        } else {
            GameFollow::create([
                'user_id' => auth()->user()->id,
                'game_id' => $game_id,
            ]);
        }

        return redirect()->back();
    }
}
