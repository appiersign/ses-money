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
            'account_number' => '0272994753',
            'provider' => 'TGO',
            'merchant_id' => $merchant->merchant_id,
            "response_url" => "https://webhook.site/52b5e75e-cab4-4339-b0c1-9cea380e4ba6"
        ]);

        $tigo = new Tigo();
        $this->assertEquals(2001, $tigo->debit($payment));
    }

    public function testCredit()
    {
        $merchant = factory(Merchant::class)->create();
        $transfer = factory(Transfer::class)->create([
            'account_number' => '0272994753',
            'provider' => 'TGO',
            'merchant_id' => $merchant->merchant_id,
            "response_url" => "https://webhook.site/52b5e75e-cab4-4339-b0c1-9cea380e4ba6"
        ]);

        $tigo = new Tigo();
        $this->assertEquals(2000, $tigo->credit($transfer));
    }
}
