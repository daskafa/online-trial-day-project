<?php

namespace App\Repositories;

use App\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    private Team $model;

    public function __construct(Team $model)
    {
        $this->model = $model;
    }

    public function getTeams(): Collection
    {
        return $this->model->get();
    }
}
