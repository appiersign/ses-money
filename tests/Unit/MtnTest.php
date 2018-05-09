<?php

namespace Tests\Unit;

use App\Merchant;
use App\Mtn;
use App\Payment;
use App\Transaction;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $payment = factory(Payment::class)->create([
            "provider" => 'MTN',
            "account_number" => "0249621938",
            "merchant_id" => $merchant->merchant_id
        ]);

        $transaction = new Transaction($payment);

        $this->assertEquals(["status" => "success", "code" => 2001, "reason" => "payment request sent"], $transaction->debit());
    }
}
