<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un comentario o respuesta en un post del devlog.
 * Implementa un sistema de comentarios anidados de dos niveles mediante
 * una relación recursiva consigo mismo a través del campo parent_id.
 * Si parent_id es null es un comentario raíz, si tiene valor es una reply.
 */
class GamePostComment extends Model
{
    /**
     * Campos que se pueden asignar masivamente con GamePostComment::create().
     */
    protected $fillable = [
        'user_id',
        'game_post_id',
        'parent_id',
        'content',
    ];

    /**
     * El usuario que escribió el comentario o respuesta.
     * Uso: $comment->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El post del devlog al que pertenece este comentario.
     * Uso: $comment->gamePost
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gamePost()
    {
        return $this->belongsTo(GamePost::class);
    }

    /**
     * El comentario raíz al que responde este comentario.
     * Si parent_id es null esta relación devuelve null — es un comentario raíz.
     * Relación recursiva: GamePostComment apunta a sí mismo.
     * Uso: $reply->parent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(GamePostComment::class);
    }

    /**
     * Respuestas anidadas de este comentario.
     * Busca todos los registros de la misma tabla cuyo parent_id
     * coincide con el id de este comentario.
     * Relación recursiva: GamePostComment apunta a sí mismo.
     * Uso: $comment->replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(GamePostComment::class, 'parent_id');
    }
}