<?php

namespace App\Repositories;

use App\Interfaces\FixtureRepositoryInterface;
use App\Models\Fixture;

class FixtureRepository implements FixtureRepositoryInterface
{
    private Fixture $model;

    public function __construct(Fixture $model)
    {
        $this->model = $model;
    }

    public function saveFixture(array $fixture): void
    {
        $this->model->insert($fixture);
    }

    public function checkIfFixtureExist(): bool
    {
        return $this->model->count() > 0;
    }
}
