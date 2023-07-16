<?php

namespace App\Interfaces;

use App\Models\Fixture;
use Illuminate\Database\Eloquent\Collection;

interface FixtureRepositoryInterface
{
    public function saveFixture(array $fixture): void;

    public function checkIfFixtureExist(): bool;

    public function getFixtures(): Collection;

    public function updateFixtureByWeek(Fixture $fixture, array $score): void;
}
