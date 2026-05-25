<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prediction;
use App\Models\Game;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    /**
     * Show the full leaderboard page.
     */
    public function index(Request $request)
    {
        $leaderboard = HomeController::getLeaderboard(100);

        // Find current user's rank
        $userRank = null;
        if (auth()->check()) {
            $userId = auth()->id();
            $entry  = $leaderboard->firstWhere('id', $userId);
            $userRank = $entry?->rank;
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('leaderboard.partials.table', compact('leaderboard', 'userRank'))->render(),
            ]);
        }

        return view('leaderboard.index', compact('leaderboard', 'userRank'));
    }

}
