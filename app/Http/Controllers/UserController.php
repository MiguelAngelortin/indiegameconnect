<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\User;


class UserController extends Controller
{
    public function show($user_id){
        $user = User::findOrFail($user_id);
        $games = null;
        if($user->role === 'developer' || $user->role === 'admin') {
            $games = Game::with('genres')
            ->where('user_id', $user->id)
            ->paginate(6);
        }
        return view('users.show', compact ('user','games'));
    }
}
