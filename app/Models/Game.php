<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa un videojuego indie publicado en la plataforma.
 * Un juego pertenece a un developer, tiene varios géneros y puede tener
 * posts en su devlog y usuarios que lo siguen.
 */
class Game extends Model
{
    /**
     * Campos que se pueden asignar masivamente con Game::create() o $game->update().
     * Cualquier campo no listado aquí será ignorado aunque venga en la request,
     * protegiéndo la BD de asignaciones masivas no deseadas.
     */
    protected $fillable = [
        'title',
        'description',
        'publisher',
        'release_date',
        'status',
        'engine',
        'download_url',
        'cover_image',
        'version',
        'user_id',
    ];

    /**
     * El developer que publicó el juego.
     * Relación inversa de la hasMany en User.
     * Uso: $game->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Géneros asociados al juego mediante la tabla intermedia game_genre.
     * Relación many-to-many: un juego puede tener varios géneros
     * y un género puede pertenecer a varios juegos.
     * Uso: $game->genres
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    /**
     * Posts del devlog publicados para este juego.
     * Relación one-to-many: un juego puede tener muchos posts.
     * Uso: $game->gamePosts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gamePosts()
    {
        return $this->hasMany(GamePost::class);
    }

    /**
     * Registros de la tabla game_follows asociados a este juego.
     * Devuelve los objetos GameFollow completos con user_id y game_id.
     * Uso: $game->follows (usado para contar con withCount)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function follows()
    {
        return $this->hasMany(GameFollow::class);
    }

    /**
     * Usuarios que siguen este juego a través de la tabla game_follows.
     * Relación many-to-many: un juego puede ser seguido por muchos usuarios
     * y un usuario puede seguir muchos juegos.
     * Uso: $game->followers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'game_follows', 'game_id', 'user_id');
    }
}