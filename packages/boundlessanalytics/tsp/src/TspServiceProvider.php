<?php

namespace BoundlessAnalytics\Tsp;


use Illuminate\Support\ServiceProvider;

class TspServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'tsp');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}