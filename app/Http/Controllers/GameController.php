<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index() {
        return view("games");
    }

    public function show($game_id) {
    return view("games.show");
    }
}
