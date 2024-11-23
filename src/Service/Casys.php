<?php

namespace Kalimero\Casys\Service;

use Illuminate\Support\Str;
use stdClass;

class Casys
{
    /**
     * Generate Casys data for payment processing.
     *
     * @param stdClass $client An object containing client data (name, last_name, country, email).
     * @param float $amount The amount to be paid.
     * @return array{checkSum: string, required: array<string, mixed>, user: array<string, mixed>, checkSumHeader: string}
     */
    public function getCasysData(stdClass $client, float $amount): array
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

        $userData = [
            'FirstName' => $client->name,
            'LastName' => $client->last_name,
            'Country' => $client->country,
            'Email' => $client->email,
        ];

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
}
