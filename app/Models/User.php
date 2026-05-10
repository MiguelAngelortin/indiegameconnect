<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo principal de usuario de la plataforma.
 * Extiende Authenticatable para integrar el sistema de autenticación de Laravel.
 * Un usuario puede tener rol user, developer o admin, y según su rol
 * tendrá acceso a distintas funcionalidades de la plataforma.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos que se pueden asignar masivamente con User::create() o $user->update().
     * Incluye campos de perfil, donaciones y estado de baneo.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'bio',
        'profile_img',
        'donation_kofi',
        'donation_paypal',
        'donation_patreon',
        'donation_other',
        'is_banned',
    ];

    /**
     * Campos excluidos cuando el modelo se serializa a JSON o array.
     * Evita que la contraseña y el token de sesión se expongan en respuestas.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversiones automáticas de tipos al leer los atributos del modelo.
     * email_verified_at se convierte a objeto Carbon para operar con fechas.
     * password se hashea automáticamente al asignarse.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Valoraciones recibidas por este developer de otros usuarios.
     * La clave foránea es developer_id en lugar del user_id por defecto,
     * por eso se especifica explícitamente.
     * Uso: $user->ratings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(UserRating::class, 'developer_id');
    }

    /**
     * Usuarios que siguen a este developer.
     * La clave foránea es developer_id porque en user_follows este usuario
     * es el que está siendo seguido, no el que sigue.
     * Uso: $user->follows / $user->follows()->count()
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function follows()
    {
        return $this->hasMany(UserFollow::class, 'developer_id');
    }

    /**
     * Developers que este usuario sigue.
     * La clave foránea es user_id porque en user_follows este usuario
     * es el que realiza el seguimiento.
     * Uso: $user->following
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function following()
    {
        return $this->hasMany(UserFollow::class, 'user_id');
    }

    /**
     * Accessor que calcula y devuelve el trust level de un developer
     * basándose en el porcentaje de valoraciones positivas recibidas.
     * Devuelve null si el developer no tiene ninguna valoración todavía.
     * Al ser un accessor, se accede como propiedad: $user->trust_level
     *
     * Rangos:
     *  0–30%  → Negative  (trust-negative)
     * 31–60%  → Mixed     (trust-mixed)
     * 61–85%  → Positive  (trust-positive)
     * 86–100% → Very Positive (trust-positive)
     *
     * @return array{percent: int, label: string, class: string}|null
     */
    public function getTrustLevelAttribute()
    {
        $total = $this->ratings()->count();

        // Sin valoraciones no se muestra el trust level
        if ($total === 0) return null;

        $positive = $this->ratings()->where('rating', 1)->count();
        $percent  = round(($positive / $total) * 100);

        // Determina la etiqueta según el porcentaje de valoraciones positivas
        $label = $percent >= 86
            ? 'Very Positive'
            : ($percent >= 61
                ? 'Positive'
                : ($percent >= 31
                    ? 'Mixed'
                    : 'Negative'));

        // Determina la clase CSS para colorear la barra de trust level en la vista
        $class = $percent >= 61
            ? 'trust-positive'
            : ($percent >= 31
                ? 'trust-mixed'
                : 'trust-negative');

        // Devuelve un array con los tres valores necesarios para renderizar el trust level
        return compact('percent', 'label', 'class');
    }

    /**
     * Juegos que este usuario sigue mediante la tabla intermedia game_follows.
     * Relación many-to-many: un usuario puede seguir muchos juegos
     * y un juego puede ser seguido por muchos usuarios.
     * Uso: $user->followedGames
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followedGames()
    {
        return $this->belongsToMany(Game::class, 'game_follows', 'user_id', 'game_id');
    }
}