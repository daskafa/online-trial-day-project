<?php

use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TournamentController::class, 'teams']);
Route::get('fixtures', [TournamentController::class, 'fixtures']);
Route::get('simulation/{week?}', [TournamentController::class, 'simulation']);

Route::get('reset-tournament', [TournamentController::class, 'resetTournament']);
