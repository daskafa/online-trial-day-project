<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('home_team_id');
            $table->unsignedMediumInteger('away_team_id');
            $table->tinyInteger('home_team_score')->nullable();
            $table->tinyInteger('away_team_score')->nullable();
            $table->tinyInteger('week');

            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('away_team_id')->references('id')->on('teams');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
