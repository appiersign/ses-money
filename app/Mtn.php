<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use SoapClient;

class Mtn extends Model
{
    private $response = [];

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

    public function debit($number, $amount, $info, $transaction_id)
    {
        $expiry = new DateTime('tomorrow');
        $expiry = $expiry->format('Y-m-d');
        $params = array
        (
            'mesg' => 'Request to pay for bill is being processed. Your invoice number is {inv}.',
            'expiry' => $expiry,
            'username' => $this->username,
            'password' => $this->password,
            'name' => 'PaySwitch Company Ltd.',
            'info' => $info,
            'amt' => $amount,
            'mobile' => $number,
            'billprompt' => 3,
            'thirdpartyID' => $transaction_id
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
            $this->response = get_object_vars($response['return']);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->response['code'] = 9000;
        }

    }
}
