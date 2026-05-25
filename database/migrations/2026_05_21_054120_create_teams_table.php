<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name', 3);    // e.g. BRA, USA, ENG
            $table->string('flag')->nullable();  // path to flag image or emoji
            $table->string('flag_emoji', 10)->nullable();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->string('confederation', 20)->nullable(); // UEFA, CONMEBOL, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};