<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DraftSession extends Model
{
    use HasFactory;

    protected $fillable = ['league_id', 'name', 'active'];

    public function league()
    {
        return $this->belongsTo(League::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('remaining_credits')
            ->withTimestamps();
    }
}
