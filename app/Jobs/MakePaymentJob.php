<?php

namespace App\Jobs;

use App\Payment;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MakePaymentJob implements ShouldQueue
{
    private $request;
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
        $this->request = $data;
        $this->request['stan'] = '00'.time();
        $this->request['action'] = 'debit';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payment = Payment::create($this->request);

        if (in_array($this->request['provider'], ['MAS', 'VIS'])) {
            $transaction = new Transaction($payment, $this->request['cvv'], $this->request['expiry_month'], $this->request['expiry_year']);
        } else {
            $transaction = new Transaction($payment);
        }

        $transaction->debit();

        $this->response = [
            'status' => 'success',
            'code' => 2000,
            'reason' => 'payment request accepted'
        ];
    }
}
