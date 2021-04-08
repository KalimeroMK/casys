<?php


namespace Kalimero\Casys\Http\Helper;


use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'Details1' => Str::random(12),
            'Details2' => 'Price '.round($amount).config('casys.AmountCurrency'),
            'PaymentOKURL' => config('casys.PaymentOKURL'),
            'PaymentFailURL' => config('casys.PaymentFailURL'),
            'OriginalAmount' => round($amount),
            'OriginalCurrency' => config('casys.AmountCurrency'),
        ];

        try {
            $this->validation($required);
        } catch (Exception $exception) {
        }

        $user = [
            'FirstName' => $client->name,
            'LastName' => $client->last_name,
            'Country' => $client->country,
            'Email' => $client->email,
        ];
        try {
            $this->validationUser($user);
        } catch (Exception $exception) {
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
        $checkSumHeaderParams .= md5(config('casys.Password'));
        // END Generate CheckSum

        return [
            'checkSum' => $checkSumHeaderParams,
            'required' => $required,
            'user' => $user,
            'checkSumHeader' => $checkSumHeader,
        ];
    }

    /**
     * @param $required
     * @return array
     * @throws exception
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
     * @throws exception
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