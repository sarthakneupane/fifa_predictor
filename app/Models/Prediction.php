<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'home_score',
        'away_score',
        'points',
        'is_exact',
        'is_correct',
        'is_calculated',
    ];

    protected $casts = [
        'is_exact'      => 'boolean',
        'is_correct'    => 'boolean',
        'is_calculated' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    // ─── Scoring Logic ────────────────────────────────────────────────────────

    /**
     * Calculate and store points based on the actual game result.
     * Call this after a game is completed and scores are entered.
     */
    public function calculatePoints(): void
    {
        $game = $this->game;

        if (!$game || $game->home_score === null || $game->away_score === null) {
            return;
        }

        $actualHome = $game->home_score;
        $actualAway = $game->away_score;

        $predictedHome = $this->home_score;
        $predictedAway = $this->away_score;

        // Exact score: 3 points
        if ($predictedHome === $actualHome && $predictedAway === $actualAway) {
            $this->points      = 3;
            $this->is_exact    = true;
            $this->is_correct  = true;
        }
        // Correct result (win/draw): 1 point
        elseif ($this->getResult($predictedHome, $predictedAway) === $this->getResult($actualHome, $actualAway)) {
            $this->points      = 1;
            $this->is_exact    = false;
            $this->is_correct  = true;
        }
        // Wrong: 0 points
        else {
            $this->points      = 0;
            $this->is_exact    = false;
            $this->is_correct  = false;
        }

        $this->is_calculated = true;
        $this->save();
    }

    /**
     * Get result string: 'home', 'draw', or 'away'.
     */
    private function getResult(int $home, int $away): string
    {
        if ($home > $away) return 'home';
        if ($away > $home) return 'away';
        return 'draw';
    }

    /**
     * Human-readable predicted score.
     */
    public function getPredictedScoreAttribute(): string
    {
        return $this->home_score . ' - ' . $this->away_score;
    }

    /**
     * Points badge class.
     */
    public function getPointsBadgeClassAttribute(): string
    {
        return match ($this->points) {
            3 => 'bg-green-500 text-white',
            1 => 'bg-yellow-500 text-black',
            default => 'bg-red-500 text-white',
        };
    }

    /**
     * Points label.
     */
    public function getPointsLabelAttribute(): string
    {
        if (! $this->is_calculated) {
            return 'Pending';
        }

        return match ((int) $this->points) {
            3 => '🎯 Exact',
            1 => '✅ Correct',
            default => '❌ Wrong',
        };
    }
}