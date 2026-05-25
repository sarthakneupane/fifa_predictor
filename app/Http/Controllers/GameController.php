<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Group;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Show all Games with filtering options.
     */
    public function index(Request $request)
    {
        $query = Game::with(['homeTeam', 'awayTeam', 'group'])
            ->orderBy('game_date');

        // Filter by stage
        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        // Filter by group
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('homeTeam', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('awayTeam', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $games = $query->paginate(12)->withQueryString();
        $groups  = Group::orderBy('letter')->get();
        $stages  = Game::select('stage')->distinct()->pluck('stage');

        // If user is logged in, fetch their predictions for visible games
        $userPredictions = [];
        if (auth()->check()) {
            $gameIds = $games->pluck('id');
            $userPredictions = auth()->user()->predictions()
                ->whereIn('game_id', $gameIds)
                ->pluck('id', 'game_id')  // [game_id => prediction_id]
                ->toArray();
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('games.partials.game-cards', compact('games', 'userPredictions'))->render(),
                'pagination' => (string) $games->links(),
            ]);
        }

        return view('games.index', compact('games', 'groups', 'stages', 'userPredictions'));
    }

    /**
     * Show a single game detail page.
     */
    public function show(Game $game)
    {
        $game->load(['homeTeam', 'awayTeam', 'group', 'predictions.user']);

        $userPrediction = null;
        if (auth()->check()) {
            $userPrediction = auth()->user()->getPredictionForGame($game->id);
        }

        // Community stats
        $communityStats = $game->community_stats;

        // Top predictors who got this one right (if completed)
        $correctPredictors = [];
        if ($game->status === 'completed') {
            $correctPredictors = $game->predictions()
                ->with('user')
                ->where('is_correct', true)
                ->orderByDesc('points')
                ->take(10)
                ->get();
        }

        return view('games.show', compact('game', 'userPrediction', 'communityStats', 'correctPredictors'));
    }
}