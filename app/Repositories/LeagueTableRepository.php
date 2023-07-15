<?php

namespace App\Repositories;

use App\Interfaces\LeagueTableRepositoryInterface;
use App\Models\LeagueTable;
use Illuminate\Database\Eloquent\Collection;

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
}
