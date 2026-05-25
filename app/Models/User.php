<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'avatar',
        'country',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_admin'          => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    // ─── Stats Accessors ─────────────────────────────────────────────────────

    /**
     * Total points earned.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->predictions()->where('is_calculated', true)->sum('points');
    }

    /**
     * Total predictions made.
     */
    public function getTotalPredictionsAttribute(): int
    {
        return $this->predictions()->count();
    }

    /**
     * Total correct predictions (1 or 3 pts).
     */
    public function getCorrectPredictionsAttribute(): int
    {
        return $this->predictions()
            ->where('is_calculated', true)
            ->where('is_correct', true)
            ->count();
    }

    /**
     * Total exact score predictions (3 pts).
     */
    public function getExactPredictionsAttribute(): int
    {
        return $this->predictions()
            ->where('is_calculated', true)
            ->where('is_exact', true)
            ->count();
    }

    /**
     * Accuracy percentage (correct / calculated predictions).
     */
    public function getAccuracyPercentageAttribute(): float
    {
        $calculated = $this->predictions()
            ->where('is_calculated', true)
            ->count();

        if ($calculated === 0) return 0.0;

        return round(($this->correct_predictions / $calculated) * 100, 1);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Get avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        // Default avatar using initials from UI Avatars API
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=C8102E&color=fff&size=128';
    }

    /**
     * Get prediction for a specific match.
     */
    public function getPredictionForGame(int $matchId): ?Prediction
    {
        return $this->predictions()->where('game_id', $matchId)->first();
    }
}