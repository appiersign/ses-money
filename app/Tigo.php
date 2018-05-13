<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Tigo extends Model
{
    private $userName;
    private $password;
    private $consumerID;
    private $webUser;
    private $wPassword;
    private $msisdn;
    private $response;
    private $responseCode;
    private $payment;
    private $transfer;
    public $transactionID;
    private $url;

    public function __construct ()
    {
        $this->userName = env('TGO_USER_NAME');
        $this->password = env('TGO_PASSWORD');
        $this->consumerID = env('TGO_CONSUMER_ID');
        $this->webUser = env('TGO_WEB_USER');
        $this->wPassword = env('TGO_WPASSWORD');
        $this->msisdn = env('TGO_MSISDN');
        $this->externalCategory = env('TGO_EXTERNAL_CATEGORY');
        $this->externalChannel = env('TGO_EXTERNAL_CHANNEL');
    }

    function debit( $number, $amount, $serviceName, $transactionID  )
    {
        // $item is required and should be generated dynamically, it describes what the payment is for
        $this->url = "https://accessgw.tigo.com.gh:8443/live/PurchaseInitiate";
        $data = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v1="http://xmlns.tigo.com/MFS/PurchaseInitiateRequest/V1" xmlns:v2="http://xmlns.tigo.com/ParameterType/V2" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3">
			<SOAP-ENV:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
			<cor:debugFlag xmlns:cor="http://soa.mic.co.af/coredata_1">true</cor:debugFlag>
			<wsse:Security>
			<wsse:UsernameToken>
			<wsse:Username>'.$this->userName.'</wsse:Username>
			<wsse:Password>'.$this->password.'</wsse:Password>
			</wsse:UsernameToken>
			</wsse:Security>
			</SOAP-ENV:Header>
			<SOAP-ENV:Body>
			<v1:PurchaseInitiateRequest>
			<v3:RequestHeader>
			<v3:GeneralConsumerInformation>
			<v3:consumerID>'.$this->consumerID.'</v3:consumerID>
			<v3:transactionID>Pay001</v3:transactionID>
			<v3:country>GHA</v3:country>
			<v3:correlationID>Pay01</v3:correlationID>
			</v3:GeneralConsumerInformation>
			</v3:RequestHeader>
			<v1:requestBody>
			<v1:customerAccount>
			<v1:msisdn>233'.substr($number, 1).'</v1:msisdn>
			</v1:customerAccount>
			<v1:initiatorAccount>
			<v1:msisdn>233276203025</v1:msisdn>
			</v1:initiatorAccount>
			<v1:paymentReference>'.$transactionID.'</v1:paymentReference>
			<v1:externalCategory>'.$this->externalCategory.'</v1:externalCategory>
			<v1:externalChannel>'.$this->externalChannel.'</v1:externalChannel>
			<v1:webUser>'.$this->webUser.'</v1:webUser>
			<v1:webPassword>'.$this->wPassword.'</v1:webPassword>
			<v1:merchantName>TekPulse</v1:merchantName>
			<v1:itemName>TekPulse</v1:itemName>
			<v1:amount>'.$amount.'</v1:amount>
			<v1:minutesToExpire>2</v1:minutesToExpire>
			<v1:notificationChannel>2</v1:notificationChannel>
			</v1:requestBody>
			</v1:PurchaseInitiateRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>';
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $this->url );
        curl_setopt(
            $curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/xml',
                'SOAPaction: http://xmlns.tigo.com/Service/PurchaseInitiate/V1/PurchaseInitiatePortType/PurchaseInitiateRequest'
            )
        );
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, "$data" );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $curl, CURLOPT_SSLCERT, '/etc/pki/tls/certs/ag_partner.crt.pem' );
        curl_setopt( $curl, CURLOPT_SSLCERTTYPE, 'PEM' );
        curl_setopt( $curl, CURLOPT_SSLKEY, '/etc/pki/tls/certs/ag_partner.key.pem' );
        curl_setopt( $curl, CURLOPT_SSLCERTPASSWD, 'tigo123!' );
        curl_setopt( $curl, CURLOPT_SSLKEYPASSWD, 'tigo123!' );

        try {
            // Prepare and write the request data to our messages_logs.txt file
            xml_parse_into_struct( xml_parser_create( ), $data, $one, $two );

            $response = curl_exec( $curl );

            // Prepare and write the request data to our messages_logs.txt file
            xml_parse_into_struct( xml_parser_create( ), $response, $array1, $array2 );

            if (isset($array1[ 10 ][ "value" ]) && $array1[ 10 ][ "value" ] === "purchaseinitiate-3022-0001-S"){
                $this->responseCode = $array1[ 10 ][ "value" ];
            } elseif (isset($array1[ 14 ][ "value" ])) {
                $this->responseCode = $array1[14]["value"];
            } else {
                $this->responseCode = 'purchase-3008-3017-F';
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->responseCode = 9000;
        }

        return $this->getResponseCode();
    }

    public function credit( $tigoNumber, $amount, $transactionID )
    {
        $this->url = "https://accessgw.tigo.com.gh:8443/live/Purchase?wsdl";
        $data ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:v2="http://xmlns.tigo.com/MFS/PurchaseRequest/V2" xmlns:v3="http://xmlns.tigo.com/RequestHeader/V3" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:v21="http://xmlns.tigo.com/ParameterType/V2">
			<soapenv:Header  xmlns:cor="http://soa.mic.co.af/coredata_1">
			<cor:debugFlag>true</cor:debugFlag>
			<wsse:Security>
			<wsse:UsernameToken>
			<wsse:Username>'.$this->userName.'</wsse:Username>
			<wsse:Password>'.$this->password.'</wsse:Password>
			</wsse:UsernameToken>
			</wsse:Security>
			</soapenv:Header>
			<soapenv:Body>
			<v2:PurchaseRequest>
			<v3:RequestHeader>
			<v3:GeneralConsumerInformation>
			<v3:consumerID>'.$this->consumerID.'</v3:consumerID>
			<!--Optional:-->
			<v3:transactionID>'.$transactionID.'</v3:transactionID>
			<v3:country>GHA</v3:country>
			<v3:correlationID>'.$transactionID.'</v3:correlationID>
			</v3:GeneralConsumerInformation>
			</v3:RequestHeader>
			<v2:requestBody>
			<v2:sourceWallet>
			<!--You have a CHOICE of the next 2 items at this level-->
			<v2:msisdn>'.$this->msisdn.'</v2:msisdn>
			</v2:sourceWallet>
			<!--Optional:-->
			<v2:targetWallet>
			<!--You have a CHOICE of the next 2 items at this level-->
			<v2:msisdn>233'.substr($tigoNumber, 1).'</v2:msisdn>
			</v2:targetWallet>
			<v2:password>3025</v2:password>
			<v2:amount>'.$amount.'</v2:amount>
			<v2:internalSystem>Yes</v2:internalSystem>
			<v2:additionalParameters>
			<!--Zero or more repetitions:-->
			<v21:ParameterType>
			<v21:parameterName>ExternalChannel</v21:parameterName>
			<v21:parameterValue>default</v21:parameterValue>
			</v21:ParameterType>
			<v21:ParameterType>
			<v21:parameterName>ExternalCategory</v21:parameterName>
			<v21:parameterValue>default</v21:parameterValue>
			</v21:ParameterType>
			<v21:ParameterType>
			<v21:parameterName>WebUser</v21:parameterName>
			<v21:parameterValue>'.$this->webUser.'</v21:parameterValue>
			</v21:ParameterType>
			<v21:ParameterType>
			<v21:parameterName>WebPassword</v21:parameterName>
			<v21:parameterValue>'.$this->wPassword.'</v21:parameterValue>
			</v21:ParameterType>
			</v2:additionalParameters>
			</v2:requestBody>
			</v2:PurchaseRequest>
			</soapenv:Body>
			</soapenv:Envelope>';
        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $this->url );
        curl_setopt
        (
            $curl, CURLOPT_HTTPHEADER, array
            (
                'Content-Type: application/xml',
                'SOAPaction: http://xmlns.tigo.com/Service/PurchaseInitiate/V1/PurchaseInitiatePortType/PurchaseInitiateRequest'
            )
        );
        curl_setopt( $curl, CURLOPT_POST, 1 );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, "$data" );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $curl, CURLOPT_SSLCERT, getcwd().'/key.pem' );
        curl_setopt( $curl, CURLOPT_SSLCERTTYPE, 'PEM' );
//        curl_setopt( $curl, CURLOPT_SSLKEY, '/etc/pki/tls/certs/ag_partner.key.pem' );
        curl_setopt( $curl, CURLOPT_SSLCERTPASSWD, 'tigo123!' );
//        curl_setopt( $curl, CURLOPT_SSLKEYPASSWD, 'tigo123!' );

        try {
            // Prepare and write the request data to our all.txt file
            xml_parse_into_struct( xml_parser_create( ), $data, $one, $two );

            // an int of value 100 is required if request is approved successfully, else 300 will be returned
            $response = curl_exec( $curl );

            xml_parse_into_struct( xml_parser_create( ), $response, $array1, $array2 );
            $response = $array1;
            // Prepare and write the request data to our all.txt file


            if ( isset( $response[ 10 ][ 'value' ] ) && $response[ 10 ][ 'value' ] === "purchase-3008-0000-S" ){
                $this->responseCode = 'purchase-3008-0000-S';
            } else {
                $this->responseCode = $response[ 14 ][ 'value' ];
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->responseCode = 9000;
        }

        return $this->getResponseCode();
    }

    private function getResponseCode()
    {
        switch ( $this->responseCode )
        {
            // successful transaction
            case 'purchase-3008-0000-S':
                return 2000;
                break;
            // insufficient funds
            case 'purchase-3008-3017-E':
                return 4003;
                break;
            // Unregistered number for debiting
            case 'purchase-3008-4501-V':
                return 4000;
                break;
            // Unregistered number for crediting
            case 'purchase-3008-3037-E':
                return 4000;
                break;
            // wrong PIN or time out
            case 'purchase-3008-3017-F':
                return 4001;
                break;
            // Invalid amount or general failure
            case 'purchaseinitiate-3022-4501-V':
                return 5001;
                break;
            // Invalid amount or general failure
            case 'purchaseinitiate-3022-3002-E':
                return 5001;
                break;
            default:
                return 9000;
                break;
        }
    }
}
