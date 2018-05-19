<?php

namespace Tests\Unit;

use App\Merchant;
use App\Payment;
use App\Tigo;
use App\Transfer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TigoTest extends TestCase
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
            'account_number' => '0577621938',
            'provider' => 'TGO',
            'merchant_id' => $merchant->merchant_id
        ]);

        $tigo = new Tigo();
        $this->assertEquals(2001, $tigo->debit($payment));
    }

    public function testCredit()
    {
        $merchant = factory(Merchant::class)->create();
        $transfer = factory(Transfer::class)->create([
            'account_number' => '0577621938',
            'provider' => 'TGO',
            'merchant_id' => $merchant->merchant_id
        ]);

        $tigo = new Tigo();
        $this->assertEquals(2000, $tigo->credit($transfer));
    }
}
