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
    }
}
