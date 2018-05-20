<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use SoapClient;

class Mtn extends Model
{
    private $response = [];
    private $responseCode;
    private $payment;
    private $transfer;
    private $username;
    private $password;

    /**
     * Mtn constructor.
     */
    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->url = env('MTN_URL');
        $this->username = env('MTN_USER_NAME');
        $this->password = env('MTN_PASSWORD');
        $this->wsdl = array('trace' => 1, 'cache_wsdl' => 'WSDL_CACHE_NONE', 'location' => 'http://68.169.57.64:8080/transflow_webclient/services/InvoicingService.InvoicingServiceHttpSoap11Endpoint', 'connection_timeout' => 10);
        $this->vendorID = env('MTN_VENDOR_ID');
        $this->apiKey = env('MTN_API_KEY');
    }


    /**
     * @return array
     */
    public function getFunctions()
    {
        $client = new SoapClient($this->url);
        $response = $client->__getFunctions();
        return $response;
    }

    /**
     * @param Payment $payment
     * @return int
     */
    public function debit(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment->authorization_code = '001101';
        $this->payment->save();

        $expiry = new DateTime('tomorrow');
        $expiry = $expiry->format('Y-m-d');
        $params = array
        (
            'mesg' => "Payment Procedure\nDial *170#\nEnter 2 for Pay bill\nEnter 6 for general payment\nEnter {inv} as payment code\nEnter ".($this->payment->getAmountAttribute())." as amount\nEnter MM PIN to authenticate\nEnter 1 to complete payment",
            'expiry' => $expiry,
            'username' => $this->username,
            'password' => $this->password,
            'name' => 'Tekpulse',
            'info' => "Tekpulse",
            'amt' => $this->payment->getAmountAttribute(),
            'mobile' => '+233'.substr($this->payment->account_number, 1),
            'billprompt' => '3',
            'thirdpartyID' => $this->payment->stan
        );

        $client = new SoapClient($this->url, $this->wsdl);
        try {
            $response = $client->__soapCall('postInvoice', array($params));
            $response = get_object_vars($response);
            $response = get_object_vars($response['return']);

            $this->payment->authorization_code = substr($this->payment->authorization_code, 0, 3). $response['responseCode'];
            $this->payment->external_id = $response['invoiceNo'];
            $this->payment->save();

            $this->responseCode = $response['responseCode'];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->responseCode = 9000;
        }

        return $this->getResponse();

    }

    public function credit(Transfer $transfer)
    {
        $this->transfer = $transfer;
        $this->transfer->authorization_code = '001101';
        $this->transfer->save();

        $this->url = 'http://68.169.59.49:8080/vpova/services/vpovaservice?wsdl';
        $params = array
        (
            'vendorID' => $this->vendorID,
            'subscriberID' => $this->transfer->account_number,
            'thirdpartyTransactionID' => $this->transfer->stan,
            'amount' => $this->transfer->getAmountAttribute(),
            'apiKey' => $this->apiKey
        );

        $client   = new SoapClient($this->url);

        try {
            $response = $client->__soapCall('DepositToWallet', array($params));

            $response           = get_object_vars($response);
            $this->response     = get_object_vars($response['return']);
            $this->responseCode = $this->response['responseCode'];

            Log::debug($response);

            Log::info($this->response['responseMessage']);

            $this->transfer->authorization_code = substr($this->transfer->authorization_code, 0, 3). $this->responseCode;
            $this->transfer->narration = $this->response['responseMessage'];
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
        switch ($this->responseCode) {
            case "0000":
                // payment request sent
                $code = 2001;
                break;

            case "13SY":
                // no set up data found
                $code = 9001;
                break;

            case "01":
                // transaction
                $code = 2000;
                break;

            case "527":
                // non MOMO number
                $code = 4003;
                break;

            case "682":
                // External Server Error
                $code = 5005;
                break;

            case '515':
                // Unregistered Mobile Number
                return 4000;
                break;

            case '04':
                // Insufficient Funds in Merchant Wallet
                return 5006;
                break;

            case 9000:
                // external error
                $code = $this->responseCode;
                break;
        }

        return $code;
    }
}
