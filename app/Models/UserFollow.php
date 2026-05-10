<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa el seguimiento de un usuario a un developer.
 * Corresponde a la tabla user_follows con dos claves foráneas que apuntan
 * ambas a la tabla users: user_id (el que sigue) y developer_id (el seguido).
 */
class UserFollow extends Model
{
    /**
     * Campos que se pueden asignar masivamente con UserFollow::create().
     */
    protected $fillable = [
        'user_id',
        'developer_id',
    ];

    /**
     * El usuario que realiza el seguimiento.
     * Uso: $follow->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * El developer que está siendo seguido.
     * Se especifica 'developer_id' explícitamente porque Laravel asumiría
     * 'user_id' por defecto al apuntar ambas relaciones a la misma tabla users.
     * Uso: $follow->developer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}