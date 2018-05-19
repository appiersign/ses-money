<?php

namespace Tests\Unit;

use App\Merchant;
use App\Payment;
use App\Transaction;
use App\Transfer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AirtelTest extends TestCase
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
            "provider" => "ATL",
            "account_number" => "0244676729",
            "merchant_id" => $merchant->merchant_id
        ]);

        $transaction = new Transaction();

        $this->assertEquals(["status" => "success", "code" => 2001, "reason" => "payment request sent"], $transaction->debit($payment));
    }

    public function testCredit()
    {
        $merchant = factory(Merchant::class)->create();
        $transfer = factory(Transfer::class)->create([
            "provider" => "ATL",
            "account_number" => "0244676729",
            "merchant_id" => $merchant->merchant_id
        ]);

        $transaction = new Transaction();
        $this->assertEquals(["status" => "approved", "code" => 2000, "reason" => "transaction successful"], $transaction->credit($transfer));
    }
}
