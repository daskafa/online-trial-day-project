<?php

namespace App\Interfaces;

interface FixtureRepositoryInterface
{
    public function saveFixture(array $fixture): void;
}
