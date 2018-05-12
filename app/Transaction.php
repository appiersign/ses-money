<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    private $payment;
    private $transfer;
    private $card_details = [];
    private $response;
    private $type;

    /**
     * Transaction constructor.
     * @param string|null $cvv
     * @param string|null $expiry_month
     * @param string $expiry_year
     */
    public function __construct(string $cvv = null, string $expiry_month = null, string  $expiry_year = null)
    {
        $this->card_details['cvv']          = $cvv;
        $this->card_details['expiry_month'] = $expiry_month;
        $this->card_details['expiry_year']  = $expiry_year;
    }

    public function debit(Payment $payment)
    {
        $this->type     = "payment";
        $this->payment  = $payment;

        if (in_array($this->payment->provider, ['MAS', 'VIS'])){
            $pay_switch = new PaySwitch($this->payment, $this->card_details);
            $this->response = $pay_switch->sendRequest()->getResponseCode();
        } elseif ($this->payment->provider === 'MTN') {
            $mtn = new Mtn();
            $this->response = $mtn->debit($this->payment);
        }
        return $this->getResponse();
    }

    public function credit(Transfer $transfer)
    {
        $this->type      = "transfer";
        $this->transfer  = $transfer;

        if ($this->transfer->provider === "MTN") {
            $mtn = new Mtn();
            $this->response = $mtn->credit($this->transfer);
        }

        return $this->getResponse();
    }

    private function airtime()
    {
//        check for the network and route accordingly
    }

    private function getResponse()
    {
        $response = [];
        switch ($this->response) {
            case 2000:
                $response = [
                    "status"    => "success",
                    "code"      => 2000,
                    "reason"    => "payment successful"
                ];
                break;

            case 2001:
                $response = [
                    "status"    => "success",
                    "code"      => 2000,
                    "reason"    => "payment request sent"
                ];
                break;

            case 2002:
                $response = [
                    "status"    => "success",
                    "code"      => 2000,
                    "reason"    => "transfer successful"
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

            case 9000:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "transfer could not be processed"
                ];
                break;
        }

        if ($this->type === "payment") {
            $this->payment->response_code       = $response['code'];
            $this->payment->response_status     = $response['status'];
            $this->payment->response_message    = $response['reason'];
            $this->payment->save();
        } elseif ($this->type === "transfer") {
            $this->transfer->response_code       = $response['code'];
            $this->transfer->response_status     = $response['status'];
            $this->transfer->response_message    = $response['reason'];
            $this->transfer->save();
        }

        return $response;
    }
}
