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


    public function getFunctions()
    {
        $client = new SoapClient($this->url);
        $response = $client->__getFunctions();
        return $response;
    }

    public function debit(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment->authorization_code = '001101';
        $this->payment->save();

        $expiry = new DateTime('tomorrow');
        $expiry = $expiry->format('Y-m-d');
        $params = array
        (
            'mesg' => "Payment Procedure\nDial *170#\nEnter 2 for Pay bill\nEnter 6 for general payment\nEnter {inv} as payment code\nEnter ".$this->payment->amount." as amount\nEnter MM PIN to authenticate\nEnter 1 to complete payment",
            'expiry' => $expiry,
            'username' => $this->username,
            'password' => $this->password,
            'name' => 'PaySwitch Company Ltd.',
            'info' => $this->payment->description,
            'amt' => (int) $this->payment->amount / 100,
            'mobile' => '+233'.substr($this->payment->account_number, 1),
            'billprompt' => 3,
            'thirdpartyID' => $this->payment->stan
        );

//        $this->mtn_debit = new Mtn();
//        $this->mtn_debit->username = self::masked($this->mtn_debit['username']);
//        $this->mtn_debit->password = self::masked($this->mtn_debit['password']);
//        $this->mtn_debit->mesg = 'Request to pay for bill is being processed. Your invoice number is {inv}.';
//        $this->mtn_debit->expiry = $expiry;
//        $this->mtn_debit->name = 'PaySwitch Company Ltd.';
//        $this->mtn_debit->info = $serviceName;
//        $this->mtn_debit->amt = $amt;
//        $this->mtn_debit->mobile = $number;
//        $this->mtn_debit->billprompt = '2';
//        $this->mtn_debit->thirdpartyID = $thirdpartyID;
//        $this->mtn_debit->save();

        $client = new SoapClient($this->url, $this->wsdl);
        try {
            $response = $client->__soapCall('postInvoice', array($params));
            $response = get_object_vars($response);
            $response = get_object_vars($response['return']);

            $this->payment->authorization_code = substr($this->payment->authorization_code, 0, 3). $response['responseCode'];
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
        $this->url = 'http://68.169.59.49:8080/vpova/services/vpovaservice?wsdl';
        $params = array
        (
            'vendorID' => $this->vendorID,
            'subscriberID' => $transfer->account_number,
            'thirdpartyTransactionID' => $transfer->stan,
            'amount' => (int) $transfer->amount / 100,
            'apiKey' => $this->apiKey
        );

        $client   = new SoapClient($this->url);
        $response = $client->__soapCall('DepositToWallet', array($params));

        $response = get_object_vars($response);
        $response = $response['return'];
        $response = get_object_vars($response);


        $response = $response['responseCode'];

    }

    public function getResponse()
    {
        switch ($this->responseCode) {
            case "0000":
                // payment request sent
                $code = 2001;
                break;

            case "13SY":
                // no set up data found
                $code = 9001;
                break;
        }

        return $code;
    }
}
