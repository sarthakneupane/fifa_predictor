<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class PredictionController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'verified']);
    // }

    /**
     * Store or update a prediction via AJAX.
     * Returns JSON response consumed by jQuery.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'game_id'   => 'required|exists:games,id',
            'home_score' => 'required|integer|min:0|max:20',
            'away_score' => 'required|integer|min:0|max:20',
        ]);

        $game = Game::findOrFail($request->game_id);

        // Check deadline
        if (!$game->is_predictable) {
            return response()->json([
                'success' => false,
                'message' => 'Prediction deadline has passed for this game.',
            ], 422);
        }

        // Upsert prediction
        $prediction = Prediction::updateOrCreate(
            [
                'user_id'  => auth()->id(),
                'game_id' => $game->id,
            ],
            [
                'home_score'    => $request->home_score,
                'away_score'    => $request->away_score,
                'is_calculated' => false, // reset when edited
                'points'        => 0,
            ]
        );

        $isNew = $prediction->wasRecentlyCreated;

        return response()->json([
            'success'    => true,
            'is_new'     => $isNew,
            'message'    => $isNew
                ? '🎯 Prediction saved! Good luck!'
                : '✏️ Prediction updated successfully!',
            'prediction' => [
                'id'         => $prediction->id,
                'home_score' => $prediction->home_score,
                'away_score' => $prediction->away_score,
            ],
        ]);
    }

    /**
     * Delete a prediction via AJAX (only before deadline).
     */
    public function destroy(Prediction $prediction): JsonResponse
    {
        $this->authorize('delete', $prediction);

        $game = $prediction->game;

        if (!$game->is_predictable) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete prediction after game has started.',
            ], 422);
        }

        $prediction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prediction removed.',
        ]);
    }

    /**
     * Show the authenticated user's prediction history.
     */
    public function myPredictions()
    {
        $predictions = auth()->user()
            ->predictions()
            ->with(['game.homeTeam', 'game.awayTeam'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('predictions.my-predictions', compact('predictions'));
    }
}