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
        Schema::create('league_tables', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedMediumInteger('team_id');
            $table->tinyInteger('played')->default(0);
            $table->tinyInteger('won')->default(0);
            $table->tinyInteger('drawn')->default(0);
            $table->tinyInteger('lost')->default(0);
            $table->smallInteger('goals_for')->default(0);
            $table->smallInteger('goals_against')->default(0);
            $table->tinyInteger('goal_difference')->default(0);
            $table->tinyInteger('points')->default(0);

            $table->foreign('team_id')->references('id')->on('teams');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league_tables');
    }
};
