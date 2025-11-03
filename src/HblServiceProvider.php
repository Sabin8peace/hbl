<?php

namespace Bickyraj\Hbl;

use Illuminate\Support\ServiceProvider;

class HblServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php','hbl'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
          __DIR__.'/config/config.php' => config_path('hbl.php'),
        ], 'config');
    }
}
