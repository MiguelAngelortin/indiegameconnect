<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePost extends Model
{
    protected $fillable = [
        "game_id",
        "user_id",
        "title",
        "content",
        "image_url",
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function likes(){
        return $this->hasMany(GamePostLike::class);
    }

    public function Comments(){
        return $this->hasMany(GamePostComment::class);
    }
}