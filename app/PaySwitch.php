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
            'Authorization: Basic '. base64_encode(env('PAYSWITCH_API_USER').":".env('PAYSWITCH_API_TOKEN'))
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

        $this->postCurl([$url, $headers, $body]);

        return $this;
    }

    private function postCurl(array $data)
    {
        $curl = curl_init($data[0]);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $data[1]);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data[2]));

        try {
            $this->response = json_decode(curl_exec($curl), true);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->response = [
                "status" => "failed",
                "code" => 9000,
                "reason" => "Error Occurred, try again later"
            ];
        }
    }

    public function getResponseCode()
    {
        $this->payment->authorization_code = substr($this->payment->authorization_code, 0, 3). $this->response['code'];
        $this->payment->save();

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
