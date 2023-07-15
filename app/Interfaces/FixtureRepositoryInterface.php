<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface FixtureRepositoryInterface
{
    public function saveFixture(array $fixture): void;

    public function checkIfFixtureExist(): bool;

    public function getFixtures(): Collection;

    public function getFirstWeekFixtures(): Collection;
}
