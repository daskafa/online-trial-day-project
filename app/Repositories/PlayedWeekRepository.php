<?php

namespace App\Repositories;

use App\Interfaces\PlayedWeekRepositoryInterface;
use App\Models\PlayedWeeks;

class PlayedWeekRepository implements PlayedWeekRepositoryInterface
{
    private PlayedWeeks $model;

    public function __construct(PlayedWeeks $model)
    {
        $this->model = $model;
    }

    public function getPlayedWeek(): int
    {
        return $this->model->orderBy('id', 'desc')->first()->week ?? 1;
    }

    public function incrementPlayedWeek($week): void
    {
        $this->model->create(['week' => $week]);
    }

    public function resetPlayedWeek(): void
    {
        $this->model->truncate();
    }
}
