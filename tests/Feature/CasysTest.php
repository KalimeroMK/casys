<?php

namespace Kalimero\Casys\Tests\Feature;

use Orchestra\Testbench\TestCase;

class CasysTest extends TestCase

{
    public function testBasic()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}