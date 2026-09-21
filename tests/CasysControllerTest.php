<?php

declare(strict_types=1);

namespace Kalimero\Casys\Tests;

final class CasysControllerTest extends TestCase
{
    public function testItRendersTheLoaderView(): void
    {
        $response = $this->get(route('loader'));

        $response->assertOk();
        $response->assertViewIs('casys::loader');
    }

    public function testThePaymentRouteRendersTheFormWithTheCasysPayload(): void
    {
        $response = $this->post(route('validateAndPay'), [
            'name' => 'Ana',
            'last_name' => 'Petrova',
            'country' => 'MK',
            'email' => 'ana@example.com',
            'amount' => '100',
        ]);

        $response->assertOk();
        $response->assertViewIs('casys::index');
        $response->assertViewHas('casys');
        $response->assertSee('name="CheckSum"', false);
        $response->assertSee('name="PayToMerchant"', false);
        $response->assertSee('MERCHANT-1', false);
    }

    public function testThePaymentRouteValidatesItsInput(): void
    {
        $response = $this->post(route('validateAndPay'), [
            'name' => 'Ana',
            'email' => 'not-an-email',
            'amount' => 'abc',
        ]);

        $response->assertSessionHasErrors(['last_name', 'country', 'email', 'amount']);
    }

    public function testItRendersTheSuccessView(): void
    {
        $response = $this->post(route('paymentOKURL'));

        $response->assertOk();
        $response->assertViewIs('casys::okurl');
        $response->assertViewHas('success', 'Your transaction was successful');
    }

    public function testItRendersTheFailureView(): void
    {
        $response = $this->post(route('paymentFailURL'));

        $response->assertOk();
        $response->assertViewIs('casys::failurl');
        $response->assertViewHas('error', 'Your transaction failed');
    }
}
