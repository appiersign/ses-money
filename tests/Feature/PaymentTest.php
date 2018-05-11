<?php

namespace Tests\Feature;

use App\Merchant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMakePayment()
    {
        $merchant = factory(Merchant::class)->create();
        $response = $this->postJson('api/payments', [
            'merchant_id' => $merchant->merchant_id,
            'transaction_id' => time().'00',
            'account_number' => '024961938',
            'description' => 'testing from the other side',
            'amount' => '000000000010',
            'response_url' => 'https://qisimah.com',
            'provider' => 'MTN'
        ],[
            'Authorization' => 'Basic '.base64_encode("$merchant->api_user:$merchant->api_key")
        ]);

        $response->assertJson([
            'merchant_id' => $merchant->merchant_id,
            'transaction_id' => time().'00',
            'description' => 'testing from the other side',
            'amount' => '000000000010',
            'response_url' => 'https://qisimah.com',
            'provider' => 'MTN'
        ]);
    }
}
