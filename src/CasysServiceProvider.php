<?php

namespace Kalimero\Casys;

use Illuminate\Support\ServiceProvider;

class CasysServiceProvider extends ServiceProvider
{


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__.'/resources/views', 'casys');
        $this->loadRoutesFrom(__DIR__.'/routes/casys.php');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/casys'),
            __DIR__ . '/config/casys.php' => config_path('/casys.php'),
            __DIR__ . '/routes/casys.php' => app_path('/../routes/casys.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/config/casys.php.php', 'casys');
    }

}
