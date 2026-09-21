<?php

declare(strict_types=1);

namespace Kalimero\Casys\Tests;

use Kalimero\Casys\CasysServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [CasysServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('casys.PayToMerchant', 'MERCHANT-1');
        $app['config']->set('casys.MerchantName', 'Test Shop');
        $app['config']->set('casys.AmountCurrency', 'MKD');
        $app['config']->set('casys.PaymentOKURL', 'https://shop.test/ok');
        $app['config']->set('casys.PaymentFailURL', 'https://shop.test/fail');
        $app['config']->set('casys.Password', 'super-secret-merchant-password');

        $app['config']->set('view.paths', array_merge(
            $app['config']->get('view.paths', []),
            [__DIR__ . '/stubs/views'],
        ));
    }
}
