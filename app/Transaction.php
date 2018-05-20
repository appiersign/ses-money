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

    /**
     * @param mixed $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
        $this->setType('payment');
        return $this;
    }

    /**
     * @param mixed $transfer
     */
    public function setTransfer($transfer)
    {
        $this->transfer = $transfer;
        $this->setType('transfer');
        return $this;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
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
        } elseif ($this->payment->provider === 'ATL') {
            $airtel = new Airtel();
            $this->response = $airtel->debit($this->payment);
        } elseif ($this->payment->provider === 'TGO') {
            $tigo = new Tigo();
            $this->response = $tigo->debit($this->payment);
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
        } elseif ($this->transfer->provider === 'ATL') {
            $airtel = new Airtel();
            $this->response = $airtel->credit($this->transfer);
        } elseif ($this->transfer->provider === 'TGO') {
            $tigo = new Tigo();
            $this->response = $tigo->credit($transfer);
        }

        return $this->getResponse();
    }

    private function airtime()
    {
//        check for the network and route accordingly
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        $response = [];
        switch ($this->response) {
            case 2000:
                $response = [
                    "status"    => "approved",
                    "code"      => 2000,
                    "reason"    => "transaction successful"
                ];
                break;

            case 2001:
                $response = [
                    "status"    => "success",
                    "code"      => 2001,
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

            case 4000:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "number not registered for the service"
                ];
                break;

            case 4001:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "wrong authorization PIN"
                ];
                break;

            case 4002:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "transaction terminated or timed out"
                ];
                break;

            case 4003:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "insufficient funds"
                ];
                break;

            case 5000:
                $response = [
                    "status"    => "error",
                    "code"      => $this->response,
                    "reason"    => "payment could not be processed"
                ];
                break;

            case 5001:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "network busy"
                ];
                break;

            case 5006:
                $response = [
                    "status"    => "error",
                    "code"      => $this->response,
                    "reason"    => "Transfer could be processed. Please try again later"
                ];
                break;

            case 5005:
                $response = [
                    "status"    => "failed",
                    "code"      => $this->response,
                    "reason"    => "External server error"
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

        Log::debug($response);

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

    public static function postResponse($url, $transaction_id, $status, $code, $reason)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            "transaction_id" => $transaction_id,
            "status" => $status,
            "code" => $code,
            "reason" => $reason
        ]));
        curl_exec($curl);
    }
}
