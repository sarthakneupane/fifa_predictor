<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'verified']);
    // }

    /**
     * User dashboard with stats, upcoming Games, and recent predictions.
     */
    public function index()
    {
        $user = auth()->user();

        // Upcoming Games user hasn't predicted on yet
        $unpredictedGames = Game::with(['homeTeam', 'awayTeam', 'group'])
            ->upcoming()
            ->whereDoesntHave('predictions', fn($q) => $q->where('user_id', $user->id))
            ->take(5)
            ->get();

        // User's recent predictions
        $recentPredictions = $user->predictions()
            ->with(['game.homeTeam', 'game.awayTeam'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Stats
        $calculatedPredictions = $user->predictions()
            ->where('is_calculated', true)->get();

        $stats = [
            'total_points'       => $calculatedPredictions->sum('points'),
            'total_predictions'  => $user->predictions()->count(),
            'correct_predictions'=> $calculatedPredictions->where('is_correct', true)->count(),
            'exact_predictions'  => $calculatedPredictions->where('is_exact', true)->count(),
            'accuracy'           => 0,
        ];

        if ($calculatedPredictions->count() > 0) {
            $stats['accuracy'] = round(
                ($stats['correct_predictions'] / $calculatedPredictions->count()) * 100,
                1
            );
        }

        // User's rank
        $leaderboard = HomeController::getLeaderboard(200);
        $userEntry   = $leaderboard->firstWhere('id', $user->id);
        $userRank    = $userEntry?->rank ?? 'N/A';

        // Chart data: points per game (last 10 calculated predictions)
        $chartData = $user->predictions()
            ->with('game')
            ->where('is_calculated', true)
            ->orderBy('created_at')
            ->take(10)
            ->get()
            ->map(fn($p) => [
                'label'  => $p->game->homeTeam->short_name . ' vs ' . $p->game->awayTeam->short_name,
                'points' => $p->points,
            ]);

        return view('dashboard.index', compact(
            'user',
            'unpredictedGames',
            'recentPredictions',
            'stats',
            'userRank',
            'chartData'
        ));
    }
}