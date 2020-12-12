<?php


namespace App\Http\Controllers\Helper;


use Illuminate\Support\Facades\Validator;

class Casys
{

    public function getCasysData($client, $amount): array
    {
        // casys settings
        $user = $length = [];

        // required fields
        $required = [
            'AmountToPay' => (round($amount) > 0) ? (round($amount) * 100) : '',
            'PayToMerchant' => config('casys.PayToMerchant'),
            'MerchantName' => config('casys.MerchantName'),
            'AmountCurrency' => config('casys.AmountCurrency'),
            'Details1' => $amount,
            'Details2' => 'Price ' . round($amount) . config('casys.AmountCurrency'),
            'PaymentOKURL' => config('casys.PaymentOKURL'),
            'PaymentFailURL' => config('casys.PaymentFailURL')
        ];

        $this->validation($required);

        // additional fields
        $additionalFields = [
            'FirstName' => $client->name,
            'LastName' => $client->last_name,
            'Country' => $client->country,
            'Email' => $client->email,
            'OriginalAmount' => round($amount),
            'OriginalCurrency' => config('casys.AmountCurrency'),
        ];
        $this->validationUser($additionalFields);


        // START Generate CheckSum
        $checkSumHeader = count($required) + count($user);
        foreach ($required as $key => $value) {
            $checkSumHeader .= $key . ',';
        }
        foreach ($user as $key => $value) {
            $checkSumHeader .= $key . ',';
        }
        foreach ($required as $key => $value) {
            $checkSumHeader .= $length[$key];
        }
        foreach ($user as $key => $value) {
            $checkSumHeader .= $length[$key];
        }
        $checkSumHeaderParams = $checkSumHeader;
        foreach ($required as $key => $value) {
            $checkSumHeaderParams .= $value;
        }
        foreach ($user as $key => $value) {
            $checkSumHeaderParams .= $value;
        }
        $checkSumHeaderParams .= config('casys.Password');
        $md5 = md5($checkSumHeaderParams);
        // END Generate CheckSum
        return [
            'checkSum' => $md5,
            'required' => $required,
            'checkSumHeader' => $checkSumHeader,
            'fields' => $additionalFields
        ];
    }

    public function validation($required): \Illuminate\Http\RedirectResponse
    {
        $errors = Validator::make($required, [
            'AmountToPay' => 'required|integer',
            'PayToMerchant' => 'required|integer',
            'MerchantName' => 'required|string|max:255',
            'AmountCurrency' => 'required|string|max:255',
            'Details1' => 'required|integer',
            'Details2' => 'required|string|max:255',
            'PaymentOKURL' => 'required|string|max:255',
            'PaymentFailURL' => 'required|string|max:255',

        ]);

        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors);
        }
    }

    public function validationUser($additionalFields): \Illuminate\Http\RedirectResponse
    {
        $errors = Validator::make($additionalFields, [
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Country' => 'required|string|max:255',
            'Email' => 'required|email',
            'OriginalAmount' => 'required|integer',
            'OriginalCurrency' => 'required|string|max:255'

        ]);

        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors);
        }
    }

}