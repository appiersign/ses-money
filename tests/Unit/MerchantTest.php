<?php

namespace Tests\Unit;

use App\Jobs\CreateMerchant;
use App\Merchant;
use App\User;
use Illuminate\Support\Facades\Auth;
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
        $response = $this->get('v2.0/', [
            'Authorization' => 'Basic '.base64_encode('api_user:api_key')
        ]);
        $response->assertStatus(401);
    }

    public function testAuthorizedMerchantRequest()
    {
        $merchant = Merchant::first();
        $response = $this->postJson('v2.0/debit', [], [
            'Authorization' => 'Basic '.base64_encode($merchant->api_user.':'.$merchant->api_key)
        ]);
        $response->assertStatus(200);
    }

    public function testResetMerchantPassword()
    {
        $user = factory(User::class)->create();
        $merchant = factory(Merchant::class)->create();

        Auth::onceUsingId($user->id);

        $response = $this->actingAs($user)->get('merchants/'.$merchant->ses_money_id.'/password.reset');
        $response->assertSeeText('password reset successful');
    }

    public function testToggleMerchantStatus()
    {
        $user = factory(User::class)->create();
        $merchant = factory(Merchant::class)->create();

        Auth::onceUsingId($user->id);

        $response = $this->actingAs($user)->get('merchants/'.$merchant->ses_money_id.'/status.toggle');
        $response->assertSeeText($merchant->name." status updated");
    }

    public function testUpdatePassword()
    {
        $user = factory(User::class)->create();

        Auth::onceUsingId($user->id);

        $response = $this->post('merchants/password.update', [
            'old' => 'admin',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $response->assertStatus(200);
    }
}

