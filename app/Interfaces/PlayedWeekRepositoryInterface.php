<?php

namespace App\Interfaces;

interface PlayedWeekRepositoryInterface
{
    public function getPlayedWeek(): int;

    public function incrementPlayedWeek($week): void;

    public function resetPlayedWeek(): void;
}
