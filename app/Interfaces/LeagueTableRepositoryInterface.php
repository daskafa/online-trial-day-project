<?php

namespace App\Interfaces;

use App\Models\Fixture;
use Illuminate\Database\Eloquent\Collection;

interface  LeagueTableRepositoryInterface
{
    public function getLeagueTables(): Collection;

    public function prepareLeagueTable(array $teams): void;

    public function updateLeagueTable(Fixture $fixture): void;

    public function resetLeagueTable(): void;
}
