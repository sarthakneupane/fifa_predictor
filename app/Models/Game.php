<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Game extends Model
{
    use HasFactory;

    protected $table = 'games';

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'group_id',
        'game_date',
        'stadium',
        'city',
        'stage',
        'home_score',
        'away_score',
        'status',
        'prediction_deadline',
        'game_number',
    ];

    protected $casts = [
        'game_date'          => 'datetime',
        'prediction_deadline' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class, 'game_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')->orderBy('game_date');
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'live');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')->orderByDesc('game_date');
    }

    public function scopeByStage($query, string $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopeByGroup($query, int $groupId)
    {
        return $query->where('group_id', $groupId);
    }

    // ─── Computed Attributes ─────────────────────────────────────────────────

    /**
     * Check if prediction is still allowed.
     */
    public function getIsPredictableAttribute(): bool
    {
        return $this->status === 'upcoming'
            && Carbon::now()->lt($this->prediction_deadline);
    }

    /**
     * Seconds until game starts (for countdown timer).
     */
    public function getSecondsUntilGameAttribute(): int
    {
        return max(0, Carbon::now()->diffInSeconds($this->game_date, false));
    }

    /**
     * Human-readable result string (e.g. "2 - 1").
     */
    public function getResultStringAttribute(): string
    {
        if ($this->status === 'completed' || $this->status === 'live') {
            return ($this->home_score ?? 0) . ' - ' . ($this->away_score ?? 0);
        }
        return 'vs';
    }

    /**
     * Determine the winner: 'home', 'away', or 'draw'.
     */
    public function getWinnerAttribute(): ?string
    {
        if ($this->home_score === null || $this->away_score === null) {
            return null;
        }
        if ($this->home_score > $this->away_score) return 'home';
        if ($this->away_score > $this->home_score) return 'away';
        return 'draw';
    }

    /**
     * Count of predictions for this game.
     */
    public function getPredictionCountAttribute(): int
    {
        return $this->predictions()->count();
    }

    /**
     * Community prediction stats (% predicting home/draw/away).
     */
    public function getCommunityStatsAttribute(): array
    {
        $total = $this->predictions()->count();
        if ($total === 0) {
            return ['home' => 0, 'draw' => 0, 'away' => 0, 'total' => 0];
        }

        $homeWins = $this->predictions()
            ->whereColumn('home_score', '>', 'away_score')->count();
        $draws    = $this->predictions()
            ->whereColumn('home_score', '=', 'away_score')->count();
        $awayWins = $this->predictions()
            ->whereColumn('away_score', '>', 'home_score')->count();

        return [
            'home'  => round(($homeWins / $total) * 100),
            'draw'  => round(($draws / $total) * 100),
            'away'  => round(($awayWins / $total) * 100),
            'total' => $total,
        ];
    }

    /**
     * Stage badge color class.
     */
    public function getStageBadgeClassAttribute(): string
    {
        return match ($this->stage) {
            'Final'         => 'bg-yellow-500 text-black font-bold',
            'Semi Final'    => 'bg-orange-500 text-white',
            'Quarter Final' => 'bg-blue-600 text-white',
            'Round of 16'   => 'bg-purple-600 text-white',
            'Round of 32'   => 'bg-indigo-600 text-white',
            'Group Stage'   => 'bg-emerald-600 text-white',
            default         => 'bg-gray-500 text-white',
        };
    }
}