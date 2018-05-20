<?php

namespace App;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Airtel extends Model
{
    private $client_id;
    private $client_secret;
    private $merchant_number;
    private $nick_name;
    private $url;
    private $end_point;
    private $ts;
    private $response;
    private $responseCode;
    private $payment;
    private $transfer;
    public $amount;
    public $customer_number;
    public $transaction_id;
    public $reference;

    function __construct( )
    {
        $this->client_id        = "EK0d9hw8UfgTyy6wk4w3vS1Y9eLmCyRMJSrPgR4VHukdZosQtzIwEsbD4w5X8z+Ah/jsQuIN12GslfKraFuxnw=="; //env('ATL_CLIENT_ID');
        $this->client_secret    = "p8RXFJbpuY8SdmjhFrVnamtLnhnzfs1A8e1lM3iQkIMcgLA1zzl6/PDdE0oU1yv1t9dMHwWjasyzoJ0mXJxNNA=="; //env('ATL_CLIENT_SECRET');
        $this->nick_name        = env('ATL_NICK_NAME');
        $this->merchant_number  = env('ATL_MERCHANT_NUMBER');
        $date                   = new DateTime('now', new DateTimeZone('Africa/Accra'));
        $this->ts               = $date->format('Y-m-d H:i:s');
    }

    public function setResponse($response)
    {
        $this->response = json_decode($response, 1);
        return $this;
    }

    public function debit(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment->authorization_code = '003101';
        $this->payment->save();

        $description = substr( $this->payment->description, 0, 15 );
        $this->end_point = 'debitCustomerWallet';
        $this->url = "https://payalert-api.anmgw.com/$this->end_point";

        $data = array
        (
            "customer_number" 			=> $this->payment->account_number,
            "merchant_number" 			=> $this->merchant_number,
            "amount" 					=> $this->payment->getAmountAttribute(),
            "exttrid" 					=> $this->payment->stan,
            "reference" 				=> $description,
            "nickname" 					=> $this->nick_name,
            "ts" 						=> $this->ts
        );

        // generating Authorization signature with sha256 encryption
        $data = json_encode( $data );
        $body = "/$this->end_point$data";
        $signature = hash_hmac( 'sha256', $body, $this->client_secret );
        $auth = "$this->client_id:$signature";

        $header = array(
            "Authorization: $auth"
        );

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $this->url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $curl, CURLOPT_POSTREDIR, 3 );

        try {
            $this->setResponse(curl_exec($curl));
            $this->responseCode = $this->response['resp_code'];
            $this->payment->authorization_code = '003' . $this->responseCode;
            $this->payment->narration = $this->response['resp_desc'];
            $this->payment->save();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->responseCode = 9000;
        }
        return $this->getResponse();
    }

    public function credit(Transfer $transfer)
    {
        $this->transfer = $transfer;
        $this->transfer->authorization_code = '003101';
        $this->transfer->save();

        $description = substr( $this->transfer->description, 0, 15 );
        $this->end_point = 'creditCustomerWallet';
        $this->url = env('ATL_URL').$this->end_point;

        $data = array
        (
            "customer_number" 			=> $this->transfer->getAccountNumberAttribute(),
            "merchant_number" 			=> env('ATL_MERCHANT_NUMBER'),
            "amount" 					=> $this->transfer->getAmountAttribute(),
            "exttrid" 					=> $this->transfer->stan,
            "reference" 				=> $description,
            "ts" 						=> $this->ts
        );
        $data = json_encode( $data );
        $body = "/$this->end_point$data";
        $signature = hash_hmac( 'sha256', $body, $this->client_secret );
        $auth = "$this->client_id:$signature";
        $header = array(
            "Authorization: $auth"
        );

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $this->url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );

        try {
            $this->setResponse(curl_exec($curl));
            $this->responseCode = $this->response['resp_code'];
            $this->transfer->authorization_code = '003'. $this->response['resp_code'];
            $this->transfer->external_id = $this->response['refid'];
            $this->transfer->narration = $this->response['resp_desc'];
            $this->transfer->save();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->responseCode = 9000;
        }
        return $this->getResponse();
    }

    public function getResponse($code = null)
    {
        if ($code <> null) {
            $this->responseCode = $code;
        }

        switch ($this->responseCode)
        {
            // successful transaction
            case '200':
                return 2000;
                break;
            // payment request sent
            case '121':
                return 2001;
                break;
            // insufficient funds
            case '60019':
                return 4003;
                break;
            // Unregistered number for debiting
            case '102':
                return 4000;
                break;
            // Unregistered number for crediting
            case '99051':
                return 4000;
                break;
            // wrong PIN or time out
            case '00068':
                return 4001;
                break;
            // Transaction declined or terminted
            case '114':
                return 4002;
                break;
            // Transaction declined or terminted
            case '107':
                return 4002;
                break;
            // Invalid amount or general failure
//            case '104':
//                return [105, $responseCode];
//                break;
//            // Invalid amount
//            case '010022':
//                return [105, $responseCode];
//                break;
//            // Invalid amount
//            case '00017':
//                return [106, $responseCode];
//                break;
            // NetWork Busy
            case '116':
                return 5001;
                break;
            // request failed
            default:
                return 9000;
                break;
        }
    }
}
