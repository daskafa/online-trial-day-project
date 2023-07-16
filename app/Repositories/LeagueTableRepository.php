<?php

namespace App\Repositories;

use App\Interfaces\LeagueTableRepositoryInterface;
use App\Models\Fixture;
use App\Models\LeagueTable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LeagueTableRepository implements LeagueTableRepositoryInterface
{
    private LeagueTable $model;

    public function __construct(LeagueTable $model)
    {
        $this->model = $model;
    }

    public function getLeagueTables(): Collection
    {
        return $this->model->with('team')->get();
    }

    public function prepareLeagueTable($teams): void
    {
        foreach ($teams as $team) {
            $this->model->create([
                'team_id' => $team->id,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
                'goal_difference' => 0,
                'points' => 0
            ]);
        }
    }

    public function updateLeagueTable(Fixture $fixture): void
    {
        $homeTeamScore = $fixture->home_team_score;
        $awayTeamScore = $fixture->away_team_score;
        $homeTeamWon = $homeTeamScore > $awayTeamScore ? 1 : 0;
        $homeTeamDrawn = $homeTeamScore == $awayTeamScore ? 1 : 0;
        $homeTeamLost = $homeTeamScore < $awayTeamScore ? 1 : 0;
        $points = $homeTeamScore > $awayTeamScore ? 3 : ($homeTeamScore == $awayTeamScore ? 1 : 0);

        $this->model->where('team_id', $fixture->home_team_id)->update([
            'played' => DB::raw('played + 1'),
            'won' => DB::raw('won + ' . $homeTeamWon),
            'drawn' => DB::raw('drawn + ' . $homeTeamDrawn),
            'lost' => DB::raw('lost + ' . $homeTeamLost),
            'goals_for' => DB::raw('goals_for + ' . $homeTeamScore),
            'goals_against' => DB::raw('goals_against + ' . $awayTeamScore),
            'goal_difference' => DB::raw('goal_difference + ' . ($homeTeamScore - $awayTeamScore)),
            'points' => DB::raw('points + ' . $points)
        ]);

        $this->model->where('team_id', $fixture->away_team_id)->update([
            'played' => DB::raw('played + 1'),
            'won' => DB::raw('won + ' . $homeTeamLost),
            'drawn' => DB::raw('drawn + ' . $homeTeamDrawn),
            'lost' => DB::raw('lost + ' . $homeTeamWon),
            'goals_for' => DB::raw('goals_for + ' . $awayTeamScore),
            'goals_against' => DB::raw('goals_against + ' . $homeTeamScore),
            'goal_difference' => DB::raw('goal_difference + ' . ($awayTeamScore - $homeTeamScore)),
            'points' => DB::raw('points + ' . ($homeTeamScore < $awayTeamScore ? 3 : ($homeTeamScore == $awayTeamScore ? 1 : 0)))
        ]);
    }

    public function getLeagueTablesOrderByPoints(): Collection
    {
        return $this->model->with('team')->orderBy('points', 'desc')->get();
    }

    public function resetLeagueTable(): void
    {
        $this->model->truncate();
    }
}
