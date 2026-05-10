<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un género de videojuego.
 * Los géneros se crean mediante el GenreSeeder y no pueden ser creados
 * por usuarios desde la plataforma. Un género puede pertenecer a muchos
 * juegos y un juego puede tener muchos géneros.
 */
class Genre extends Model
{
    /**
     * Campos que se pueden asignar masivamente con Genre::create().
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Juegos que tienen este género asignado mediante la tabla intermedia game_genre.
     * Relación inversa de la belongsToMany definida en Game.
     * Uso: $genre->games
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function games()
    {
        return $this->belongsToMany(Game::class);
    }
}