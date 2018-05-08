<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    private $data;

    public function __construct(array $data = [])
    {
//        parent::__construct($data);
        $this->data = $data;
    }

    public function process()
    {
        if ($this->data['action'] === 'debit') {
//            pass to debit function
        } elseif ($this->data['action'] === 'credit') {
//            pass to credit functions
        } elseif ($this->data['action'] === 'airtime') {
//            pass to airtime function
        } else {
            return [
                'status' => 'error',
                'code' => 4000,
                'reason' => 'unknown action'. $this->data['action']
            ];
        }
    }

    private function debit()
    {
//        check for the provider and route accordingly
    }

    private function credit()
    {
//        check for the provider and route accordingly
    }

    private function airtime()
    {
//        check for the network and route accordingly
    }

    public function postCurlRequest(string $url, array $headers, array $body)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        try {
            $response = curl_exec($curl);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function response($code)
    {
//        switch and return
    }
}
