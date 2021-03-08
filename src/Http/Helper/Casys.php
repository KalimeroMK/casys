<?php


namespace App\Http\Helper;


use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Casys
{

    public function getCasysData($client, $amount): array
    {
        $length = [];

        $required = [
            'AmountToPay' => (round($amount) > 0) ? (round($amount) * 100) : '',
            'PayToMerchant' => config('casys.PayToMerchant'),
            'MerchantName' => config('casys.MerchantName'),
            'AmountCurrency' => config('casys.AmountCurrency'),
            'Details1' => $price,
            'Details2' => 'Price '.round($amount).config('casys.AmountCurrency'),
            'PaymentOKURL' => config('casys.PaymentOKURL'),
            'PaymentFailURL' => config('casys.PaymentFailURL'),
            'OriginalAmount' => round($amount),
            'OriginalCurrency' => config('casys.AmountCurrency'),
        ];

        try {
            $this->validation($required);
        } catch (ValidationException $e) {
        }
        foreach ($required as $key => $value) {
            $length[$key] = sprintf('%03d', mb_strlen($value, 'UTF-8'));
        }

        $user = [
            'FirstName' => $client->name,
            'LastName' => $client->last_name,
            'Country' => $client->country,
            'Email' => $client->email,
        ];
        try {
            $this->validationUser($user);
        } catch (ValidationException $e) {
        }
        // validate $additionalFields
        foreach ($user as $key => $value) {
            $user[$key] = $value;
            $length[$key] = sprintf('%03d', mb_strlen($value, 'UTF-8'));
        }
        // START Generate CheckSum
        $checkSumHeader = count($required) + count($user);
        foreach ($required as $key => $value) {
            $checkSumHeader .= $key.',';
        }
        foreach ($user as $key => $value) {
            $checkSumHeader .= $key.',';
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
            'user' => $user,
            'checkSumHeader' => $checkSumHeader,
        ];
    }

    /**
     * @param $required
     * @return array
     * @throws ValidationException
     */
    public function validation($required): array
    {
        return Validator::make($required, [
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

        ])->validate();

    }

    /**
     * @param $user
     * @return array
     * @throws ValidationException
     */
    public function validationUser($user): array
    {
        return Validator::make($user, [
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Country' => 'required|string|max:255',
            'Email' => 'required|email'

        ])->validate();


    }

}