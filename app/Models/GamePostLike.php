<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePostLike extends Model
{
    protected $fillable = [
    'user_id',
    'game_post_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function gamePost(){
        return $this->belongsTo(GamePost::class);
    }
}
