<?php

namespace Tests\Unit;

use App\Merchant;
use App\Payment;
use App\Transaction;
use Tests\TestCase;
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

        $transaction = new Transaction($body['cvv'], $body['exp_month'], $body['exp_year']);

        $this->assertTrue(true);

//        $this->assertEquals(["status" => "approved", "code" => 2000, "reason" => "payment approved"], $transaction->debit($payment));
    }

    public function testResponse(array $response = [])
    {
        $merchant_id    = factory(Merchant::class)->create();
        $payment        = factory(Payment::class)->create([
            "merchant_id"       => $merchant_id->merchant_id,
            "provider"          => "mtn",
            "account_number"    => "0249621938",
            "response_url"      => "http://sesmoney.proxy.beeceptor.com",
            "response_status"   => "success",
            "response_code"     => 2001,
            "response_message"  => "payment request sent",
            "authorization_code" => "001101",
            "external_id"       => str_random(12)
        ]);

        $response["provider"]       = "mtn";
        $response["transaction_id"] = microtime();
        $response["responseCode"]   = "01";
        $response["external_id"]    = $payment->external_id;

        $this->assertEquals(json_encode(["status" => "approved", "code" => 2000, "reason" => "transaction successful"]), $payment->response($response));
    }
}
