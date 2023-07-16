<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\TeamRepositoryInterface::class,
            \App\Repositories\TeamRepository::class
        );

        $this->app->bind(
            \App\Interfaces\FixtureRepositoryInterface::class,
            \App\Repositories\FixtureRepository::class
        );

        $this->app->bind(
            \App\Interfaces\LeagueTableRepositoryInterface::class,
            \App\Repositories\LeagueTableRepository::class
        );

        $this->app->bind(
            \App\Interfaces\PlayedWeekRepositoryInterface::class,
            \App\Repositories\PlayedWeekRepository::class
        );
    }
}
