<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->unsignedTinyInteger('home_score');
            $table->unsignedTinyInteger('away_score');
            $table->unsignedTinyInteger('points')->default(0); // 0, 1, or 3
            $table->boolean('is_exact')->default(false);       // exact score game
            $table->boolean('is_correct')->default(false);     // correct result (W/D/L)
            $table->boolean('is_calculated')->default(false);  // has points been calculated?
            $table->timestamps();

            // Each user can only predict once per game
            $table->unique(['user_id', 'game_id']);
            $table->index(['user_id', 'points']);
            $table->index('game_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};