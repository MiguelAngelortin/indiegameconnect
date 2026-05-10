<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa el seguimiento de un juego por parte de un usuario.
 * Corresponde a la tabla game_follows que actúa como tabla intermedia
 * entre users y games, pero con modelo propio para poder consultarla
 * directamente con withCount() y para el feed personalizado.
 */
class GameFollow extends Model
{
    /**
     * Campos que se pueden asignar masivamente con GameFollow::create().
     */
    protected $fillable = [
        'user_id',
        'game_id',
    ];

    /**
     * El usuario que sigue el juego.
     * Uso: $follow->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El juego que está siendo seguido.
     * Uso: $follow->game
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}