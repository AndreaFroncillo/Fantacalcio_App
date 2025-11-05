<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\League[] $leagues
 */

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


    public function ownedLeagues()
    {
        return $this->hasMany(League::class, 'owner_id');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function draftSessions()
    {
        return $this->belongsToMany(DraftSession::class)
            ->withPivot('remaining_credits')
            ->withTimestamps();
    }

    public function leagues()
    {
        return $this->belongsToMany(League::class)
            ->withPivot(['is_admin', 'credits', 'goalkeepers', 'defenders', 'midfielders', 'forwards'])
            ->withTimestamps();
    }
}
