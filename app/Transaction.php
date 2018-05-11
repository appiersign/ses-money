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
            $pay_switch = new PaySwitch($this->payment, $this->card_details);
            $this->response = $pay_switch->sendRequest()->getResponseCode();
        } elseif ($this->payment->provider === 'MTN') {
            $mtn = new Mtn();
            $this->response = $mtn->debit($this->payment);
        }
        return $this->getResponse();
    }

    private function credit()
    {
//        check for the provider and route accordingly
    }

    private function airtime()
    {
//        check for the network and route accordingly
    }

    private function getResponse()
    {
        switch ($this->response) {
            case 2000:
                $response = [
                    "status"    => "approved",
                    "code"      => $this->response,
                    "reason"    => "payment approved"
                ];
                break;

            case 2001:
                $response = [
                    "status"    => "success",
                    "code"      => $this->response,
                    "reason"    => "payment request sent"
                ];
                break;

            case 5000:
                $response = [
                    "status"    => "error",
                    "code"      => $this->response,
                    "reason"    => "payment could not be processed"
                ];
                break;

            case 9001:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "payment could not be processed"
                ];
                break;
        }

        $this->payment->response_code       = $response['code'];
        $this->payment->response_status     = $response['status'];
        $this->payment->response_message    = $response['reason'];
        $this->payment->save();

        return $response;
    }
}
