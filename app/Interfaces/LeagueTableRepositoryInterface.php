<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface  LeagueTableRepositoryInterface
{
    public function getLeagueTables(): Collection;

    public function prepareLeagueTable(array $teams): void;
}
