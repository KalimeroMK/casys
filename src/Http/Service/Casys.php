<?php

namespace Kalimero\Casys\Http\Service;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Casys
{

    public function getCasysData($client, $amount): array
    {
        $requiredData = [
            'AmountToPay' => (int) round($amount) > 0 ? (int) round($amount) * 100 : null,
            'PayToMerchant' => config('casys.PayToMerchant'),
            'MerchantName' => config('casys.MerchantName'),
            'AmountCurrency' => config('casys.AmountCurrency'),
            'Details1' => Str::random(12),
            'Details2' => 'Price ' . round($amount) . config('casys.AmountCurrency'),
            'PaymentOKURL' => config('casys.PaymentOKURL'),
            'PaymentFailURL' => config('casys.PaymentFailURL'),
            'OriginalAmount' => (int) round($amount),
            'OriginalCurrency' => config('casys.AmountCurrency'),
        ];

        $this->validateRequiredData($requiredData);

        $userData = [
            'FirstName' => $client->name,
            'LastName' => $client->last_name,
            'Country' => $client->country,
            'Email' => $client->email,
        ];

        $this->validateUserData($userData);

        $checkSumHeader = implode(',', array_merge(array_keys($requiredData), array_keys($userData)));
        $checkSumHeaderLengths = implode('', array_merge(array_values($requiredData), array_values($userData)));
        $checkSumHeaderParams = $checkSumHeaderLengths . md5(config('casys.Password'));

        foreach ($requiredData as $value) {
            $checkSumHeaderParams .= $value;
        }

        foreach ($userData as $value) {
            $checkSumHeaderParams .= $value;
        }

        return [
            'checkSum' => $checkSumHeaderParams,
            'required' => $requiredData,
            'user' => $userData,
            'checkSumHeader' => $checkSumHeader . $checkSumHeaderLengths,
        ];
    }

    private function validateRequiredData($data): void
    {
        Validator::make($data, [
            'AmountToPay' => 'required|integer',
            'PayToMerchant' => 'required|integer',
            'MerchantName' => 'required|string|max:255',
            'AmountCurrency' => 'required|string|max:255',
            'Details1' => 'required|integer',
            'Details2' => 'required|string|max:255',
            'PaymentOKURL' => 'required|string|max:255',
            'PaymentFailURL' => 'required|string|max:255',
            'OriginalAmount' => 'required|integer',
            'OriginalCurrency' => 'required|string|max:255',
        ])->validate();
    }

    private function validateUserData($userData): void
    {
        Validator::make($userData, [
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Country' => 'required|string|max:255',
            'Email' => 'required|email',
        ])->validate();
    }
}
