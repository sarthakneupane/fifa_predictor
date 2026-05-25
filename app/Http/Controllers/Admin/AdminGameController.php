<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Group;
use App\Models\Prediction;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminGameController extends Controller
{
    /**
     * List all games.
     */
    public function index(Request $request)
    {
        $games = Game::with(['homeTeam', 'awayTeam', 'group'])
            ->orderBy('game_date')
            ->paginate(20);

        return view('admin.games.index', compact('games'));
    }

    /**
     * Show create game form.
     */
    public function create()
    {
        $teams  = Team::orderBy('name')->get();
        $groups = Group::orderBy('letter')->get();
        return view('admin.games.create', compact('teams', 'groups'));
    }

    /**
     * Store new game.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team_id'        => 'required|exists:teams,id',
            'away_team_id'        => 'required|exists:teams,id|different:home_team_id',
            'group_id'            => 'nullable|exists:groups,id',
            'game_date'          => 'required|date',
            'stadium'             => 'nullable|string|max:255',
            'city'                => 'nullable|string|max:255',
            'stage'               => 'required|in:Group Stage,Round of 32,Round of 16,Quarter Final,Semi Final,Third Place,Final',
            'prediction_deadline' => 'required|date|before_or_equal:game_date',
            'game_number'        => 'nullable|string|max:20',
        ]);

        Game::create($validated);

        return redirect()->route('admin.games.index')
            ->with('success', 'game created successfully!');
    }

    /**
     * Show edit game form.
     */
    public function edit(Game $game)
    {
        $teams  = Team::orderBy('name')->get();
        $groups = Group::orderBy('letter')->get();
        return view('admin.games.edit', compact('game', 'teams', 'groups'));
    }

    /**
     * Update game (also handles score update → triggers point calculation).
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'home_team_id'        => 'required|exists:teams,id',
            'away_team_id'        => 'required|exists:teams,id|different:home_team_id',
            'group_id'            => 'nullable|exists:groups,id',
            'game_date'          => 'required|date',
            'stadium'             => 'nullable|string|max:255',
            'city'                => 'nullable|string|max:255',
            'stage'               => 'required|in:Group Stage,Round of 32,Round of 16,Quarter Final,Semi Final,Third Place,Final',
            'home_score'          => 'nullable|integer|min:0|max:20',
            'away_score'          => 'nullable|integer|min:0|max:20',
            'status'              => 'required|in:upcoming,live,completed',
            'prediction_deadline' => 'required|date',
            'game_number'        => 'nullable|string|max:20',
        ]);

        $oldStatus = $game->status;
        $game->update($validated);

        // If game just marked completed with scores → calculate all predictions
        if (
            $validated['status'] === 'completed'
            && $validated['home_score'] !== null
            && $validated['away_score'] !== null
            && ($oldStatus !== 'completed' || !$game->predictions()->where('is_calculated', true)->exists())
        ) {
            $this->calculategamePredictions($game->fresh());
        }

        return redirect()->route('admin.games.index')
            ->with('success', 'Game updated and predictions calculated!');
    }

    /**
     * Delete game (cascades to predictions via FK).
     */
    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('admin.games.index')
            ->with('success', 'game deleted.');
    }

    /**
     * Manually trigger prediction calculation for a game.
     */
    public function calculatePredictions(Game $game)
    {
        if ($game->status !== 'completed' || $game->home_score === null) {
            return back()->with('error', 'Game must be completed with scores to calculate predictions.');
        }

        $count = $this->calculateGamePredictions($game);

        return back()->with('success', "Calculated points for {$count} predictions.");
    }

    private function calculateGamePredictions(Game $game): int
    {
        $predictions = $game->predictions()->get();

        foreach ($predictions as $prediction) {
            $prediction->calculatePoints();
        }

        return $predictions->count();
    }
}