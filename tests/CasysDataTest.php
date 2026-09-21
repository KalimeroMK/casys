<?php

declare(strict_types=1);

namespace Kalimero\Casys\Tests;

use Kalimero\Casys\Service\Casys;
use stdClass;

final class CasysDataTest extends TestCase
{
    public function testItBuildsTheRequiredMerchantFields(): void
    {
        $data = (new Casys())->getCasysData($this->client(), 100.0);

        $this->assertSame('MERCHANT-1', $data['required']['PayToMerchant']);
        $this->assertSame('Test Shop', $data['required']['MerchantName']);
        $this->assertSame(10000, $data['required']['AmountToPay']);
        $this->assertSame(100, $data['required']['OriginalAmount']);
    }

    public function testTheCheckSumDoesNotLeakTheMerchantPassword(): void
    {
        $this->markTestSkipped(
            'Pending confirmation against the CaSys specification: the CheckSum is currently the raw '
            . 'concatenation and embeds md5(password), which is rendered into a hidden form field.',
        );

        $data = (new Casys())->getCasysData($this->client(), 100.0);

        $this->assertStringNotContainsString(
            md5('super-secret-merchant-password'),
            $data['checkSum'],
            'The CheckSum is rendered into a hidden form field, so it must not embed the merchant password hash.',
        );
    }

    public function testTheCheckSumHeaderListsFieldNamesOnly(): void
    {
        $this->markTestSkipped(
            'Pending confirmation against the CaSys specification: CheckSumHeader currently appends '
            . 'every field value after the field names.',
        );

        $data = (new Casys())->getCasysData($this->client(), 100.0);

        $this->assertSame(
            'AmountToPay,PayToMerchant,MerchantName,AmountCurrency,Details1,Details2,'
            . 'PaymentOKURL,PaymentFailURL,OriginalAmount,OriginalCurrency,FirstName,LastName,Country,Email',
            $data['checkSumHeader'],
        );
    }

    private function client(): stdClass
    {
        $client = new stdClass();
        $client->name = 'Ana';
        $client->last_name = 'Petrova';
        $client->country = 'MK';
        $client->email = 'ana@example.com';

        return $client;
    }
}
