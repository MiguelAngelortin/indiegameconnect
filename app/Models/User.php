<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ratings()
    {
        return $this->hasMany(UserRating::class, 'developer_id');
    }

    public function follows()
    {
        return $this->hasMany(UserFollow::class, 'developer_id');
    }

    public function following()
    {
        return $this->hasMany(UserFollow::class, 'user_id');
    }

    public function getTrustLevelAttribute()
{
    $total = $this->ratings()->count();
    if ($total === 0) return null;
    $positive = $this->ratings()->where('rating', 1)->count();
    $percent = round(($positive / $total) * 100);
    $label = $percent >= 86 ? 'Very Positive' : ($percent >= 61 ? 'Positive' : ($percent >= 31 ? 'Mixed' : 'Negative'));
    $class = $percent >= 31 ? ($percent >= 61 ? 'trust-positive' : 'trust-mixed') : 'trust-negative';
    return compact('percent', 'label', 'class');
}

public function followedGames()
{
    return $this->belongsToMany(Game::class, 'game_follows', 'user_id', 'game_id');
}
}
