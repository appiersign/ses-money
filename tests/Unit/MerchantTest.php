<?php

namespace Tests\Unit;

use App\Jobs\CreateMerchant;
use App\Merchant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MerchantTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateMerchant()
    {
        $data = ['name' => 'QISIMAH AUDIO INSIGHTS', 'email' => 'email@email.com', 'password' => '$2y$10$Y/VxGM/vqTTBBqtY3ou/wu45LlXft4PaUSqMCfLRCovot1LXWOO7y', 'phone_number' => '233249621938'];
        $this->assertNull((new CreateMerchant($data))->handle());
    }

    public function testMerchantLogin()
    {
        $this->assertDatabaseHas('merchants', [
            'email' => 'email@email.com',
            'password' => '$2y$10$Y/VxGM/vqTTBBqtY3ou/wu45LlXft4PaUSqMCfLRCovot1LXWOO7y'
        ]);
    }

    public function testMerchantApiAuthentication()
    {
        $merchant = factory(Merchant::class)->create();
        $this->assertDatabaseHas('merchants', [
            'api_key' => $merchant->api_key,
            'api_user' => $merchant->api_user
        ]);
    }

    public function testUnauthorizedMerchantRequest()
    {
        $response = $this->get('api/', [
            'Authorization' => 'Basic '.base64_encode('api_user:api_key')
        ]);
        $response->assertStatus(401);
    }

    public function testAuthorizedMerchantRequest()
    {
        $merchant = Merchant::first();
        $response = $this->postJson('api/debit', [], [
            'Authorization' => 'Basic '.base64_encode($merchant->api_user.':'.$merchant->api_key)
        ]);
        $response->assertStatus(200);
    }
}
