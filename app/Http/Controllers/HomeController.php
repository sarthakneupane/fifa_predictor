<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Group;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the public homepage with hero, upcoming Games, and leaderboard.
     */
    public function index()
    {
        // Upcoming Games (next 6)
        $upcomingGames = Game::with(['homeTeam', 'awayTeam', 'group'])
            ->upcoming()
            ->take(6)
            ->get();

        // Live Games
        $liveGames = Game::with(['homeTeam', 'awayTeam'])
            ->live()
            ->get();

        // Recent results (last 5 completed)
        $recentResults = Game::with(['homeTeam', 'awayTeam'])
            ->completed()
            ->take(5)
            ->get();

        // Top 5 leaders for homepage widget
        $topPredictors = $this->getLeaderboard(5);

        // Groups overview
        $groups = Group::with('teams')->get();

        // Stats counters
        $stats = [
            'total_users'       => User::whereNotNull('email_verified_at')->count(),
            'total_predictions' => Prediction::count(),
            'total_games'     => Game::count(),
            'completed_games' => Game::where('status', 'completed')->count(),
        ];

        return view('home', compact(
            'upcomingGames',
            'liveGames',
            'recentResults',
            'topPredictors',
            'groups',
            'stats'
        ));
    }

    /**
     * Build leaderboard data (reused by HomeController and LeaderboardController).
     */
    public static function getLeaderboard(int $limit = 50): \Illuminate\Support\Collection
    {
        return User::query()
            ->where('is_admin', false)
            ->whereNotNull('email_verified_at')
            ->withCount([
                'predictions as total_predictions_count',
                'predictions as correct_count' => fn($q) => $q
                    ->where('is_calculated', true)->where('is_correct', true),
                'predictions as exact_count' => fn($q) => $q
                    ->where('is_calculated', true)->where('is_exact', true),
            ])
            ->withSum(['predictions as total_points' => fn($q) => $q
                ->where('is_calculated', true)], 'points')
            ->orderByDesc('total_points')
            ->orderByDesc('correct_count')
            ->orderByDesc('exact_count')
            ->take($limit)
            ->get()
            ->map(function ($user, $index) {
                $calculated = $user->predictions()
                    ->where('is_calculated', true)->count();

                $user->rank = $index + 1;
                $user->accuracy = $calculated > 0
                    ? round(($user->correct_count / $calculated) * 100, 1)
                    : 0;

                return $user;
            });
    }
}