<?php


namespace App\Traits;


trait Casys
{
    /**
     * @param $client
     * @param $amount
     * @return array
     */
    protected function getCasysData($client, $amount): array
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
        // validate $required
        foreach ($required as $key => $value) {
            if (mb_strlen($value, 'UTF-8') == 0) {
                abort(402, 'Required field(s) missing!');
            }
            $length[$key] = sprintf('%03d', mb_strlen($value, 'UTF-8'));
        }

        // additional fields
        $additionalFields = [
            'FirstName' => $client->name,
            'LastName' => $client->last_name,
            'Country' => $client->country,
            'Email' => $client->email,
            'OriginalAmount' => round($amount),
            'OriginalCurrency' => config('casys.AmountCurrency'),
        ];

        // validate $additionalFields
        foreach ($additionalFields as $key => $value) {
            if ($value === '') {
                continue;
            }
            $user[$key] = $value;
            $length[$key] = sprintf('%03d', mb_strlen($value, 'UTF-8'));
        }

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
}
