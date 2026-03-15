<?php

namespace App\Http\Controllers;

use App\Models\Genre;
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

    public function store(){
        
    }
}
