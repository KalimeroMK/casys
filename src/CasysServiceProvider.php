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

        $this->loadRoutesFrom(__DIR__ . '/routes/casys.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'casys');
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/casys'),
        ]);
        $this->publishes([
            __DIR__ . '/config/casys.php' => config_path('/casys.php')
        ]);
        $this->publishes([
            __DIR__ . '/Http/Controllers/CasysController.php' => app_path('/Http/Controllers/CasysController.php'),
        ]);
        $this->publishes([
            __DIR__ . '/Traits/Casys.php' => app_path('/Traits/Casys.php'),
        ]);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }

}
