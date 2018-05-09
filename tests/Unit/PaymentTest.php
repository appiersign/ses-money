<?php

namespace Tests\Unit;

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
        $url = "https://api.theteller.net/v1.1/transaction/process";

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic '. base64_encode("testuser:MTk1NjQyMTQ4N3Rlc3R1c2VyVGh1LUZlYiAxNi0yMDE5")
        ];

        $body = [
            'merchant_id' => 'TTM-00000001',
            'transaction_id' => time().'11',
            'desc' => 'testing from the other side',
            'amount' => '000000000010',
            'r-switch' => 'MAS',
            'pan' => '5454410007344162',
            'processing_code' => '000000',
            'exp_month' => '10',
            'exp_year' => '19',
            'cvv' => '959',
            '3d_url_response' => 'https://api.theteller.net'
        ];

        $transaction = new Transaction();

        $this->assertEquals('approved', $transaction->postCurlRequest($url, $headers, $body)['status']);
    }
}
