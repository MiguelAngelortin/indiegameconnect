<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa la valoración de un usuario hacia un developer.
 * Cada registro es único por combinación user_id + developer_id,
 * garantizando que un usuario solo puede valorar una vez a cada developer.
 * El campo rating solo admite dos valores: 1 (positivo) o -1 (negativo).
 */
class UserRating extends Model
{
    /**
     * Campos que se pueden asignar masivamente con UserRating::create()
     * y actualizar con UserRating::updateOrCreate().
     */
    protected $fillable = [
        'user_id',
        'developer_id',
        'rating',
    ];

    /**
     * El usuario que emite la valoración.
     * Uso: $rating->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El developer que recibe la valoración.
     * Se especifica 'developer_id' explícitamente porque Laravel asumiría
     * 'user_id' por defecto al apuntar ambas relaciones a la misma tabla users.
     * Uso: $rating->developer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}