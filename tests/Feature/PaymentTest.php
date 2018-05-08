<?php

namespace Tests\Feature;

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
        $response = $this->postJson('payment/create', [
            'merchant_id' => 'SES-000000001',
            'transaction_id' => time().'00',
            'description' => 'testing from the other side',
            'amount' => '000000000010',
            'provider' => 'MTN'
        ]);
        $response->assertJson([
            'merchant_id' => 'SES-000000001',
            'transaction_id' => time().'00',
            'description' => 'testing from the other side',
            'amount' => '000000000010',
            'provider' => 'MTN'
        ]);
    }
}
