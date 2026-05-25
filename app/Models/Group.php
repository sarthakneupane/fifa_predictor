<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'letter'];

    /**
     * Get all teams in this group.
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get all group stage matches for this group.
     */
    public function games()
    {
        return $this->hasMany(Game::class);
    }
}