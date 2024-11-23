
# Casys Laravel Package

This is a package to integrate the Casys payment gateway into Laravel. It generates complete scaffolding for simple integration, including support for recurring payments.

## Features
1. **Views**
    - `resources/views/vendor/casys`
2. **Configuration**
    - `config/casys.php`
3. **Controllers**
    - `/Http/Controllers/CasysController`
    - `/Http/Controllers/RecurringPaymentController`
4. **Recurring Payment Support**
    - `/Http/Services/RecurringPayment.php`
    - Example integration for managing recurring payments using SOAP services.
5. **Routes**
    - Predefined routes for standard and recurring payments.

---

## Installation

Require this package by running:

```bash
composer require kalimeromk/casys-laravel
```

After installation, publish the package files:

```bash
php artisan vendor:publish --provider="Kalimero\Casys\CasysServiceProvider"
```

This will publish the following files:
- `config/casys.php`
- `resources/views/vendor/casys`

---

## Laravel Setup

Register the route file in your `RouteServiceProvider` or add the following routes to your existing route file:

### Standard Payment Routes
```php
use App\Http\Controllers\CasysController;

Route::get('paymentLoader', [CasysController::class, 'index'])->name('loader');
Route::post('payment', [CasysController::class, 'getCasys'])->name('validateAndPay');
Route::post('paymentOKURL', [CasysController::class, 'success'])->name('paymentOKURL');
Route::post('paymentFailURL', [CasysController::class, 'fail'])->name('paymentFailURL');
```

### Recurring Payment Routes
```php
use KalimeroMK\Casys\Controllers\RecurringPaymentController;

Route::post('/recurring-payment', [RecurringPaymentController::class, 'handleRecurringPayment'])->name('recurring.payment');
```

For Laravel <=7, use the controller string syntax:
```php
Route::get('paymentLoader', 'CasysController@index')->name('loader');
Route::post('payment', 'CasysController@getCasys')->name('validateAndPay');
Route::post('paymentOKURL', 'CasysController@success')->name('paymentOKURL');
Route::post('paymentFailURL', 'CasysController@fail')->name('paymentFailURL');
```

---

## How to Use

### Configuration
Add your credentials to the `.env` file:
```env
PAY_TO_MERCHANT=your_merchant_id
MERCHANT_NAME=your_merchant_name
AMOUNT_CURRENCY=MKD
PAYMENT_OK_URL=your_success_url
PAYMENT_FAIL_URL=your_fail_url
CASYS_TOKEN=your_token
```

### Standard Payments
For standard payments, simply pass the amount and client data to the appropriate method in the `CasysController`. The views provided by the package will handle the UI. If you wish to customize the views, edit the published views in `resources/views/vendor/casys`.

---

### Recurring Payments

The package includes support for recurring payments through the `RecurringPayment` class and `RecurringPaymentController`. Here’s how you can integrate it:

#### **Recurring Payment Parameters**
The recurring payment requires the following parameters:
- **RPRef**: A string containing details about the recurring payment, formatted as:
  ```plaintext
  RPRef = RequestType,BillingCycle,MaxBCycles,BillingAmount,BillingCycleStart
  ```
- **RPRefID**: Unique ID returned during the initial registration of the recurring transaction.

#### **Example Workflow**
1. **Initial Registration**
   The cardholder performs the initial transaction with the `RPRef` parameter set. The system returns an `RPRefID`, which you should store for future recurring payments.

2. **Subsequent Payments**
   Use the stored `RPRefID` to initiate subsequent payments without user involvement.

#### **Example Request**
Call the `/recurring-payment` endpoint with the following payload:
```json
{
    "merchant_id": "YourMerchantID",
    "rp_ref": "R,1M,12,500000,20240101",
    "rp_ref_id": "UniqueRPRefID",
    "amount": 500000,
    "password": "YourMerchantPassword"
}
```

#### **Example Recurring Payment Integration**
You can use the `RecurringPayment` service class in your application:

```php
use KalimeroMK\Casys\Services\RecurringPayment;

$recurringPayment = new RecurringPayment();

$response = $recurringPayment->sendPayment(
    $merchantId,
    $rpRef,
    $rpRefId,
    $amount,
    $password
);

if ($response['success']) {
    echo "Recurring payment successful! Reference: " . $response['payment_reference'];
} else {
    echo "Recurring payment failed: " . $response['error_description'];
}
```

---

### Provided Controllers

#### **CasysController**
Handles the standard payment flow, including:
- Loading the payment page (`paymentLoader` route).
- Validating and processing payments (`payment` route).
- Handling success (`paymentOKURL` route) and failure (`paymentFailURL` route) callbacks.

#### **RecurringPaymentController**
Handles recurring payment requests via the `/recurring-payment` route. Example integration is included for making SOAP calls to the Casys gateway.

---

## Info

This package is in an alpha stage and is designed to be flexible for your specific needs. **Suggestions are welcome!**
