<?php

namespace Tests\Unit;

use App\Merchant;
use App\Terminal;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TerminalTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegister()
    {
        $merchant = Merchant::inRandomOrder()->first();
        $data = [
            'merchant_id' => $merchant->id,
            'name' => str_random(),
            'type' => 'web'
        ];
        $terminal = new Terminal();
        $this->assertArrayHasKey('ses_money_id', $terminal->register($data)->toArray());
    }
}
