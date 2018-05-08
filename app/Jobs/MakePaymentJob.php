<?php

namespace App\Jobs;

use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MakePaymentJob implements ShouldQueue
{
    private $payment;
    private $response;

    public function getMakePaymentResponse()
    {
        return $this->response;
    }
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->payment = $data;
        $this->payment['stan'] = '00'.time();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Payment::create($this->payment);
        $this->response = [
            'status' => 'success',
            'code' => 2000,
            'reason' => 'payment request accepted'
        ];
    }
}
