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
        Schema::create('teams', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name');
            $table->tinyInteger('team_power')->comment('Team power from 1 to 10');
            $table->tinyInteger('supporter_power')->comment('Supporter power from 1 to 10');
            $table->tinyInteger('goalkeeper_power')->comment('Goalkeeper power from 1 to 10');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
