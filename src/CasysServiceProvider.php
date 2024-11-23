<?php

namespace Kalimero\Casys;

use Illuminate\Support\ServiceProvider;
use Kalimero\Casys\Interfaces\RecurringPaymentInterface;
use Kalimero\Casys\Service\RecurringPayment;
use SoapClient;
use InvalidArgumentException;

class CasysServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'casys');
        $this->loadRoutesFrom(__DIR__ . '/routes/casys.php');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/casys'),
            __DIR__ . '/config/casys.php' => config_path('casys.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton(RecurringPaymentInterface::class, function ($app) {
            $wsdl = config('casys.RecurrentPaymentWsdl');

            // Ensure the WSDL is valid
            if (!is_string($wsdl) || empty($wsdl)) {
                throw new InvalidArgumentException('The WSDL URL for the Recurrent Payment service must be a valid non-empty string.');
            }

            $soapClient = new SoapClient($wsdl);

            return new RecurringPayment($soapClient);
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/casys.php', 'casys');
    }
}
