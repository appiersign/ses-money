<?php

namespace App\Jobs;

use App\Payment;
use Illuminate\Support\Facades\Log;

class CreatePaymentJob
{
    private $request;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->request = $data;
        $this->request['stan'] = '00'.time();
        $this->request['action'] = 'debit';
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $payment = Payment::create($this->request);
        if (is_null($payment)) {
            throw new \Exception("Payment could not be created");
        }
    }
}
