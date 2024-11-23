<?php

namespace Kalimero\Casys\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class CasysTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_it_loads_the_casys_loader_view_on_index()
    {
        $response = $this->get(route('casys.index'));

        $response->assertStatus(200);
        $response->assertViewIs('casys::loader');
    }

    public function test_it_returns_casys_data_on_getCasys()
    {
        $client = $this->faker->word;
        $amount = $this->faker->randomNumber(2);

        $response = $this->get(route('casys.getCasys', ['client' => $client, 'amount' => $amount]));

        $response->assertStatus(200);
        $response->assertViewIs('casys::index');
        $response->assertViewHas('casys');
    }

    public function test_it_returns_success_view_on_success()
    {
        $response = $this->get(route('casys.success'));

        $response->assertStatus(200);
        $response->assertViewIs('casys::okurl');
        $response->assertViewHas('success', 'You transaction was successful');
    }

    public function test_it_returns_fail_view_on_fail()
    {
        $response = $this->get(route('casys.fail'));

        $response->assertStatus(200);
        $response->assertViewIs('casys::failurl');
        $response->assertViewHas('success', 'Your transaction failed');
    }
}
