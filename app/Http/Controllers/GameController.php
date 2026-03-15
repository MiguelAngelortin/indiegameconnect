<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Game;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index() {
        return view("games");
    }

    public function show($game_id) {
    return view("games.show");
    }

    public function create(){
        $genres = Genre::all();
        return view("games.create", compact('genres'));
    }

    public function store(Request $request) {
        $request->validate([
            'title'=> ['required','string','max:255'],
            'description'=> ['required','string'],
            'genres'=> ['required','array','min:1'],
            'status'=> ['required','in:alpha,beta,release,cancelled'],
            'engine'=> ['required','in:Unity,Unreal,Godot,GameMaker,Other'],
            'publisher'=> ['nullable','string'],
            'release_date'=> ['nullable','date'],
            'cover_image'=> ['nullable','string'],
            'download_url'=> ['nullable','string'],
            'version'=> ['nullable','string'],
        ]);

        dd($request->all());

        //ver usuario actual
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
        

        return redirect ('/games/'.$game->id);
    }
}
