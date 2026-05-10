<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un like de un usuario en un post del devlog.
 * Cada registro es único por combinación user_id + game_post_id,
 * garantizando que un usuario solo puede dar un like por post.
 */
class GamePostLike extends Model
{
    /**
     * Campos que se pueden asignar masivamente con GamePostLike::create().
     */
    protected $fillable = [
        'user_id',
        'game_post_id',
    ];

    /**
     * El usuario que dio el like.
     * Uso: $like->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El post que recibió el like.
     * Uso: $like->gamePost
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gamePost()
    {
        return $this->belongsTo(GamePost::class);
    }
}