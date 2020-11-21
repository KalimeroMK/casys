## Laravel >=5


This is a package to integrate Casys payment gateway in laravel it generates complete scaffolding views/controller/traits for simple integration.

Casys, Macedonia payment card processor 

## Installation

Require this package in your composer.json `"kalimeromk/casys-laravel": "9999999-dev"`,
and run composer update or run `composer require kalimeromk/casys-laravel`

After updating composer you need to run :

    $ php artisan vendor:publish --provider="Kalimero\Casys\CasysServiceProvider"

You need to publish the files for this package. This will add the files 

`config/casys.php`,
`app/Http/Controllers/CasysController.php`,
`app/Traits/Casys.php`,
`resources/views/vendor/casys`

Add routes manually
### Laravel 

use App\Http\Controllers\CasysController;

`Route::get('paymentLoader', [CasysController::class, 'index'])->name('loader');`
`Route::post('payment', [CasysController::class, 'getCasys'])->name('validateAndPay');`
`Route::post('paymentOKURL', [CasysController::class, 'success'])->name('paymentOKURL');`
`Route::post('paymentFailURL', [CasysController::class, 'fail'])->name('paymentFailURL');`

**NOTE:** *This is only needed in Laravel <=7*

`Route::get('paymentLoader', 'CasysController@index')->name('loader');`
`Route::post('payment', 'CasysController@getCasys')->name('validateAndPay');`
`Route::post('paymentOKURL', 'CasysController@success')->name('paymentOKURL');`
`Route::post('paymentFailURL', 'CasysController@fail')->name('paymentFailURL');`


## Info

This package is still very alpha.

    - Suggestions are welcome :)