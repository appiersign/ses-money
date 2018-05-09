<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    private $payment;
    private $card_details = [];
    private $response;

    /**
     * Transaction constructor.
     * @param Payment $payment
     * @param string|null $cvv
     * @param string|null $expiry_month
     * @param string $expiry_year
     */
    public function __construct(Payment $payment, string $cvv = null, string $expiry_month = null, string  $expiry_year = null)
    {
        $this->card_details['cvv']          = $cvv;
        $this->card_details['expiry_month'] = $expiry_month;
        $this->card_details['expiry_year']  = $expiry_year;
        $this->payment = $payment;
    }

    public function debit()
    {
        if (in_array($this->payment->provider, ['MAS', 'VIS'])){
            $debit = new PaySwitch($this->payment, $this->card_details);
            $this->response($debit->sendRequest()->getResponse());
        }
    }

    private function credit()
    {
//        check for the provider and route accordingly
    }

    private function airtime()
    {
//        check for the network and route accordingly
    }

    public static function postCurlRequest(array $data)
    {
        $curl = curl_init($data[0]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $data[1]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data[2]));

        try {
            return json_decode(curl_exec($curl), true);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function response($code)
    {
//        switch and return
    }
}
