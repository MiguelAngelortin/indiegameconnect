<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un post del devlog de un juego.
 * Los posts son publicados por el developer del juego para informar
 * a los seguidores del progreso. Pueden recibir likes y comentarios.
 */
class GamePost extends Model
{
    /**
     * Campos que se pueden asignar masivamente con GamePost::create().
     */
    protected $fillable = [
        'game_id',
        'user_id',
        'title',
        'content',
        'image_url',
    ];

    /**
     * El usuario (developer) que publicó el post.
     * Uso: $post->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El juego al que pertenece este post del devlog.
     * Uso: $post->game
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Likes recibidos por este post.
     * Relación one-to-many: un post puede tener muchos likes.
     * Uso: $post->likes / $post->likes()->count()
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(GamePostLike::class);
    }

    /**
     * Comentarios y respuestas de este post.
     * Incluye tanto comentarios raíz como replies anidadas.
     * Para obtener solo los comentarios raíz usar ->whereNull('parent_id').
     * Uso: $post->comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(GamePostComment::class);
    }
}