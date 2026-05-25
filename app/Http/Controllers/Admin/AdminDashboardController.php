<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'        => User::where('is_admin', false)->count(),
            'verified_users'     => User::where('is_admin', false)->whereNotNull('email_verified_at')->count(),
            'total_games'      => Game::count(),
            'completed_games'  => Game::where('status', 'completed')->count(),
            'upcoming_games'   => Game::where('status', 'upcoming')->count(),
            'total_predictions'  => Prediction::count(),
            'total_teams'        => Team::count(),
        ];

        $recentPredictions = Prediction::with(['user', 'game.homeTeam', 'game.awayTeam'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $upcomingGames = Game::with(['homeTeam', 'awayTeam'])
            ->upcoming()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPredictions', 'upcomingGames'));
    }
}