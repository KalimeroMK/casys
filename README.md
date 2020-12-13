## Laravel >=5


This is a package to integrate Casys payment gateway in laravel it generates complete scaffolding for simple integration. It create:<br>
## Views <br>
## Controller<br>
## Aditional class

Casys, Macedonia payment card processor 

## Installation

Require this package in your composer.json `"kalimeromk/casys-laravel": "9999999-dev"`,
and run composer update or run `composer require kalimeromk/casys-laravel`

After updating composer you need to run :

    $ php artisan vendor:publish --provider="Kalimero\Casys\CasysServiceProvider"

It will publish the files from this package it will add this files 

`config/casys.php`,<br />
`app/Http/Controllers/CasysController.php`,<br />
`app/Traits/Casys.php`,<br />
`resources/views/vendor/casys`<br />
`routes/casys.php`<br />


### Laravel 

Register route file in RouteServiceProvider or add this routes to existing root file

`use App\Http\Controllers\CasysController;`<br/>

`Route::get('paymentLoader', [CasysController::class, 'index'])->name('loader');`<br />
`Route::post('payment', [CasysController::class, 'getCasys'])->name('validateAndPay');`<br />
`Route::post('paymentOKURL', [CasysController::class, 'success'])->name('paymentOKURL');`<br />
`Route::post('paymentFailURL', [CasysController::class, 'fail'])->name('paymentFailURL');`<br />

**NOTE:** *This is only needed in Laravel <=7*

`Route::get('paymentLoader', 'CasysController@index')->name('loader');`<br />
`Route::post('payment', 'CasysController@getCasys')->name('validateAndPay');`<br />
`Route::post('paymentOKURL', 'CasysController@success')->name('paymentOKURL');`<br />
`Route::post('paymentFailURL', 'CasysController@fail')->name('paymentFailURL');`<br />


## how to use 
 
Add you credentionals in .env file like this 

PAY_TO_MERCHANT = <br>
MERCHANT_NAME = <br>
AMOUNT_CURRENCY = MKD <br>
PAYMENT_OK_URL = <br>
PAYMENT_FAIL_URL = <br>
CASYS_TOKEN = <br>

and now only need to pass amount and client data to the method in controller 

Views at the moment are coming form the package if you want to use the published one jus edit the controller 

## Info

This package is still very alpha and it is not created as a proper package so it can be easy updated to feed you needs 

    - Suggestions are welcome :)
