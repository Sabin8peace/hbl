<?php

namespace Bickyraj\Hbl;

use Illuminate\Support\ServiceProvider;

class HblServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge package config with app config
        $this->mergeConfigFrom(__DIR__ . '/../config/hbl.php', 'hbl');

        // Bind HblClient for dependency injection
        $this->app->singleton(HblClient::class, function ($app) {
            return new HblClient(config('hbl'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/hbl.php' => config_path('hbl.php'),
        ], 'config');
    }
}