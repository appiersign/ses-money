<?php

namespace App;

use App\Http\Requests\CreatePaymentRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    private $request;
    protected $fillable = [
        'merchant_id',
        'stan',
        'account_number',
        'transaction_id',
        'provider',
        'amount',
        'description',
        'authorization_code',
        'response_code',
        'response_status',
        'response_message',
        'response_url'
    ];

    public function getAmountAttribute()
    {
        return (int) $this->attributes['amount'] / 100;
    }

    public function setReferenceIdAttribute($id)
    {
        $this->attributes['reference_id'] = $id;
    }

    public function setAuthorizationCode($code)
    {
        $this->attributes['authorization_code'] = $code;
    }

    /**
     * @param CreatePaymentRequest $request
     * @return array
     */
    public function process(CreatePaymentRequest $request): array
    {
        $this->request = $request;
        if (in_array($this->request['provider'], ['MAS', 'VIS'])) {
            $transaction = new Transaction($this->request['cvv'], $this->request['expiry_month'], $this->request['expiry_year']);
        } else {
            $transaction = new Transaction();
        }

        $payment = Payment::where('merchant_id', $this->request->merchant_id)->where('transaction_id', $this->request->transaction_id)->first();

        return $transaction->debit($payment);
    }

    public function response($response)
    {
        if ($response['provider'] === 'mtn') {
            $payment = Payment::where('external_id', $response['invoiceNo'])->first();
            $payment->reference_id = $response['transactionId'];
            $payment->authorization_code = substr($payment->authorization_code, 0, 3) . $response['responseCode'];
            if ($response['responseCode'] === '01') {
                $payment->response_code = 2000;
                $payment->response_status = 'approved';
                $payment->response_message = 'payment successful';
            }
            $payment->save();

            Transaction::postResponse($payment->transaction_id, $payment->response_status, $payment->response_code, $payment->response_message);
        }
    }
}
