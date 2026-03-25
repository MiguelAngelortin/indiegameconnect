<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePostComment extends Model
{
    protected $fillable = [
    'user_id',
    'game_post_id',
    'parent_id',
    'content'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function gamePost(){
        return $this->belongsTo(GamePost::class);
    }

    public function parent(){
        return $this->belongsTo(GamePostComment::class);
    }

    public function replies(){
        return $this->hasMany(GamePostComment::class, 'parent_id');
    }
}
