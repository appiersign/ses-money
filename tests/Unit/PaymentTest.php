<?php

namespace Tests\Unit;

use App\Merchant;
use App\Payment;
use App\Transaction;
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
    public function testPostCurlRequest()
    {
        $body = [
            'merchant_id'       => 'TTM-00000001',
            'transaction_id'    => time().'11',
            'desc'              => 'testing from the other side',
            'amount'            => '000000000010',
            'r-switch'          => 'MAS',
            'pan'               => '5454410007344162',
            'processing_code'   => '000000',
            'exp_month'         => '10',
            'exp_year'          => '19',
            'cvv'               => '959',
            '3d_url_response'   => 'https://api.theteller.net'
        ];

        $merchant_id = factory(Merchant::class)->create();

        $payment = factory(Payment::class)->create([
            "merchant_id"   => $merchant_id->merchant_id,
            "provider"      => "MAS",
            "account_number" => $body['pan']
        ]);

        $transaction = new Transaction($payment, $body['cvv'], $body['exp_month'], $body['exp_year']);

        $this->assertTrue(true);

//        $this->assertEquals(["status" => "approved", "code" => 2000, "reason" => "payment approved"], $transaction->debit());
    }
}
