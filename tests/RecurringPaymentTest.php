<?php

declare(strict_types=1);

namespace Kalimero\Casys\Tests;

use Kalimero\Casys\Interfaces\RecurringPaymentInterface;

final class RecurringPaymentTest extends TestCase
{
    public function testThePostedAmountReachesTheGatewayInsteadOfZero(): void
    {
        $spy = $this->bindSpy();

        $response = $this->post(route('recurring.payment'), [
            'merchant_id' => 'MERCHANT-1',
            'rp_ref' => 'REF-1',
            'rp_ref_id' => 'REFID-1',
            'amount' => '100',
            'password' => 'pass',
        ]);

        $response->assertOk();
        $this->assertSame(100, $spy->received['amount']);
    }

    public function testADecimalAmountIsRoundedRatherThanDiscarded(): void
    {
        $spy = $this->bindSpy();

        $this->post(route('recurring.payment'), [
            'merchant_id' => 'MERCHANT-1',
            'rp_ref' => 'REF-1',
            'rp_ref_id' => 'REFID-1',
            'amount' => '100.60',
            'password' => 'pass',
        ]);

        $this->assertSame(101, $spy->received['amount']);
    }

    public function testItReturnsTheGatewayPayloadAsJson(): void
    {
        $this->bindSpy(['success' => true, 'payment_reference' => 'CPAY-9']);

        $response = $this->post(route('recurring.payment'), [
            'merchant_id' => 'MERCHANT-1',
            'rp_ref' => 'REF-1',
            'rp_ref_id' => 'REFID-1',
            'amount' => '100',
            'password' => 'pass',
        ]);

        $response->assertOk();
        $response->assertExactJson(['success' => true, 'payment_reference' => 'CPAY-9']);
    }

    public function testItValidatesTheRecurringPaymentInput(): void
    {
        $this->bindSpy();

        $response = $this->post(route('recurring.payment'), ['merchant_id' => 'MERCHANT-1']);

        $response->assertSessionHasErrors(['rp_ref', 'rp_ref_id', 'amount', 'password']);
    }

    /**
     * @param array<string, mixed>|null $result
     */
    private function bindSpy(?array $result = null): object
    {
        $spy = new class($result ?? ['success' => true, 'payment_reference' => 'X']) implements RecurringPaymentInterface {
            /** @var array<string, mixed> */
            public array $received = [];

            /**
             * @param array<string, mixed> $result
             */
            public function __construct(private readonly array $result) {}

            public function sendPayment(
                string $merchantID,
                string $rpRef,
                string $rpRefID,
                int $amount,
                string $password,
            ): array {
                $this->received = ['merchantID' => $merchantID, 'rpRef' => $rpRef, 'rpRefID' => $rpRefID, 'amount' => $amount, 'password' => $password];

                return $this->result;
            }
        };

        $this->app->instance(RecurringPaymentInterface::class, $spy);

        return $spy;
    }
}
