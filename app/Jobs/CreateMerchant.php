<?php

namespace App\Jobs;

use App\Merchant;
use App\Terminal;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMerchant implements ShouldQueue
{
    private $merchant;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->merchant = $data;
        $this->merchant['ses_money_id'] = str_random(12);
        $this->merchant['api_key'] = str_random(32);
        $this->merchant['api_user'] = str_random();
        $this->merchant['merchant_id'] = 'pending';
        $this->merchant['address'] = $data['address'] ?? 'address';
        $this->merchant['phone_number'] = $data['telephone'];
        $this->merchant['password'] = bcrypt('admin');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $merchant = Merchant::create($this->merchant);
        if ($merchant <> null) {
            $merchant->merchant_id = 'SES-'.str_pad($merchant->id, 8, 0, STR_PAD_LEFT);
            $merchant->save();

            $terminal = new Terminal();
            $terminal->register(['merchant_id' => $merchant->id, 'name' => $merchant->name]);
        }
    }
}
