<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('away_team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->dateTime('game_date');
            $table->string('stadium')->nullable();
            $table->string('city')->nullable();
            $table->enum('stage', [
                'Group Stage',
                'Round of 32',
                'Round of 16',
                'Quarter Final',
                'Semi Final',
                'Third Place',
                'Final'
            ])->default('Group Stage');
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->enum('status', ['upcoming', 'live', 'completed'])->default('upcoming');
            $table->dateTime('prediction_deadline'); // usually = game_date
            $table->string('game_number')->nullable(); // e.g. "game 1"
            $table->timestamps();

            $table->index(['status', 'game_date']);
            $table->index('stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};