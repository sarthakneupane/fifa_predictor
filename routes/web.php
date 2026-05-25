<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\Admin\AdminTeamController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');



// ─── Authenticated + Email Verified Routes ───────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Predictions (AJAX)
    Route::post('/predictions', [PredictionController::class, 'store'])->name('predictions.store');
    Route::delete('/predictions/{prediction}', [PredictionController::class, 'destroy'])->name('predictions.destroy');
    Route::get('/my-predictions', [PredictionController::class, 'myPredictions'])->name('predictions.my');

});

// ─── Admin Routes ─────────────────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Games management
        Route::resource('games', AdminGameController::class);
        Route::post('games/{game}/calculate', [AdminGameController::class, 'calculatePredictions'])
            ->name('games.calculate');

        // Teams management
        Route::resource('teams', AdminTeamController::class);

        // Users management
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

// ─── Auth Routes (Breeze) ─────────────────────────────────────────────────────
require __DIR__ . '/auth.php';