<?php

namespace Tests\Unit;

use App\Merchant;
use App\Payment;
use App\Transaction;
use App\Transfer;
use Tests\TestCase;

class MtnTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDebit()
    {
        $merchant = factory(Merchant::class)->create();
        $payment  = factory(Payment::class)->create([
            "provider" => 'MTN',
            "account_number" => "0249621938",
            "merchant_id" => $merchant->merchant_id,
            "response_url" => "http://sesmoney.proxy.beeceptor.com"
        ]);

        $transaction = new Transaction();

        $this->assertEquals(["status" => "success", "code" => 2001, "reason" => "payment request sent"], $transaction->debit($payment));
    }

    public function testCredit()
    {
        $merchant = factory(Merchant::class)->create();
        $transfer = factory(Transfer::class)->create([
            "provider" => "MTN",
            "account_number" => "0249621938",
            "merchant_id" => $merchant->merchant_id
        ]);

        $transaction = new Transaction();
        $this->assertEquals(["status" => "success", "code" => 2000, "reason" => "transfer successful"], $transaction->credit($transfer));
    }
}
