<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface
{
    public function getTeams(): Collection;

    public function resetTeams(): void;
}
