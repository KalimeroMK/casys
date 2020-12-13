<?php


namespace App\Http\Helper;


use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class Casys
{

    public function getCasysData($client, $amount): array
    {

        $required = [
            'AmountToPay' => (round($amount) > 0) ? (round($amount) * 100) : '',
            'PayToMerchant' => config('casys.PayToMerchant'),
            'MerchantName' => config('casys.MerchantName'),
            'AmountCurrency' => config('casys.AmountCurrency'),
            'Details1' => $amount,
            'Details2' => 'Price ' . round($amount) . config('casys.AmountCurrency'),
            'PaymentOKURL' => config('casys.PaymentOKURL'),
            'PaymentFailURL' => config('casys.PaymentFailURL'),
            'OriginalAmount' => round($amount),
            'OriginalCurrency' => config('casys.AmountCurrency'),
        ];

        $this->validation($required);

        $user = [
            'FirstName' => $client->name,
            'LastName' => $client->last_name,
            'Country' => $client->country,
            'Email' => $client->email,
        ];
        $this->validationUser($user);

        $checkSumHeaderParams = config('casys.Password');
        $checkSum = md5($checkSumHeaderParams);
        $CheckSumHeader = "AmountToPay,PayToMerchant,MerchantName,AmountCurrency,Details1,Details2,PaymentOKURL,PaymentFailURL,FirstName,LastName,Email,OriginalAmount,OriginalCurrency" . $required['AmountToPay'] . $required['PayToMerchant'] . $required['MerchantName'] . $required['AmountCurrency'] . $required['Details1'] . $required['Details2'] . $required['PaymentOKURL'] . $required['PaymentFailURL'] . $required['FirstName'] . $required['LastName'] . $required['Email'] . $required['OriginalAmount'];
        $checkSumHeader = $CheckSumHeader . $required['AmountToPay'] . $required['PayToMerchant'] . $required['MerchantName'] . $required['AmountCurrency'] . $required['Details1'] . $required['Details2'] . $required['PaymentOKURL'] . $required['PaymentFailURL'] . $required['FirstName'] . $required['LastName'] . $required['Email'] . $required['OriginalAmount'] . $checkSum;
        return [
            'checkSum' => $checkSum,
            'required' => $required,
            'user' => $user,
            'checkSumHeader' => $checkSumHeader,
        ];
    }

    /**
     * @param $required
     * @return RedirectResponse
     */
    public function validation($required)
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
            'OriginalAmount' => 'required|integer',
            'OriginalCurrency' => 'required|string|max:255'

        ]);

        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors);
        }
    }

    /**
     * @param $user
     * @return RedirectResponse
     */
    public function validationUser($user)
    {
        $errors = Validator::make($user, [
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Country' => 'required|string|max:255',
            'Email' => 'required|email'

        ]);

        if ($errors->fails()) {
            return redirect()->back()->withErrors($errors);
        }
    }

}