<?php
/*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication 
    | for Cpay  integration in  your application. You may change these defaults as required.
    |
    */
return [
    'PayToMerchant' => env('PAY_TO_MERCHANT'),
    'MerchantName' => env('MERCHANT_NAME'),
    'AmountCurrency' => env('AMOUNT_CURRENCY', 'MKD'),
    'PaymentOKURL' => env('PAYMENT_OK_URL', 'paymentOKURL'),
    'PaymentFailURL' => env('PAYMENT_FAIL_URL', 'paymentFailURL'),
    'Password' => env('CASYS_TOKEN', 'TEST_PASS'),
    'RecurrentPaymentWsdl' => env('RECURRING_PAYMENT_WSDL', 'https://www.cpay.com.mk/Recurring/RecurringPaymentsWS.wsdl'),

];
