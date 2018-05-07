<?php

namespace Tests\Unit;

use App\Merchant;
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
        $merchant = factory(Merchant::class)->make();
        $this->assertDatabaseMissing('merchants', [
            'email' => $merchant->email
        ]);
    }
}
