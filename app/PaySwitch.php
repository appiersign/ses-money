<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaySwitch extends Model
{
    private $request;
    private $response;
    private $payment;

    public function __construct(Payment $payment, array $request)
    {
        $this->request = $request;
        $this->payment = $payment;
    }

    public function sendRequest()
    {
        $this->payment->authorization_code = ($this->payment->provider === 'VIS')? '005101': '006101';
        $this->payment->save();

        $url = "https://api.theteller.net/v1.1/transaction/process";

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic '. base64_encode("testuser:MTk1NjQyMTQ4N3Rlc3R1c2VyVGh1LUZlYiAxNi0yMDE5")
        ];

        $body = [
            'merchant_id'       => 'TTM-00000001',
            'transaction_id'    => $this->payment->stan,
            'desc'              => $this->payment->description,
            'amount'            => $this->payment->amount,
            'r-switch'          => $this->payment->provider,
            'pan'               => $this->payment->account_number,
            'processing_code'   => '000000',
            'exp_month'         => $this->request['expiry_month'],
            'exp_year'          => $this->request['expiry_year'],
            'cvv'               => $this->request['cvv'],
            '3d_url_response'   => 'https://api.theteller.net'
        ];

        $this->response = Transaction::postCurlRequest([$url, $headers, $body]);

        return $this;
    }

    public function getResponse()
    {
        switch ($this->response['code']) {
            case '000':
                $code = 2000;
                break;

            default:
                $code = 5000;
                break;
        }

        return $code;
    }
}
