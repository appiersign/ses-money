<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MerchantTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateMerchant()
    {
        $this->assertTrue(true);
    }

    public function testMerchantLogin()
    {
        $this->assertDatabaseMissing('merchants', [
            'email' => 'solomon@qisimah.com'
        ]);
    }
}
