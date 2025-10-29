<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'max_players',
        'initial_credits',
        'invite_code',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($league) {
            $league->invite_code = strtoupper(Str::random(8));
        });
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function draftSessions()
    {
        return $this->hasMany(DraftSession::class);
    }
}
