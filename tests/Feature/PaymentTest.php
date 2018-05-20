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
        $transaction_id = time().'00';
        $merchant = factory(Merchant::class)->create();
        $response = $this->postJson('api/payments', [
            'merchant_id' => $merchant->merchant_id,
            'transaction_id' => $transaction_id,
            'account_number' => '0556274000',
            'description' => 'testing from the other side',
            'amount' => '000000000010',
            'response_url' => 'http://sesmoney.proxy.beeceptor.com',
            'provider' => 'MTN'
        ],[
            'Authorization' => 'Basic '.base64_encode("$merchant->api_user:$merchant->api_key")
        ]);

        $response->assertJson([
            "merchant_id" => $merchant->merchant_id,
            "transaction_id" => $transaction_id,
            "description" => "testing from the other side",
            "amount" => "000000000010",
            "response_url" => "http://sesmoney.proxy.beeceptor.com",
            'account_number' => '0556274000',
            "provider" => "MTN",
            "status"    => "success",
            "code"      => 2001,
            "reason"    => "payment request sent"
        ]);
    }
}
