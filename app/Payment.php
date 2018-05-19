<?php

namespace App;

use App\Http\Requests\CreatePaymentRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
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

    /**
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $response): JsonResponse
    {
        $payment        = Payment::where('external_id', $response['external_id'])->first();
        $transaction    = new Transaction();
        $transaction->setPayment($payment);

        if ($response['provider'] === 'mtn') {
            $payment->reference_id = $response['transaction_id'];
            $payment->authorization_code = substr($payment->authorization_code, 0, 3) . $response['response_code'];
            $payment->save();

            $mtn = new Mtn();
            $_response = $transaction->setResponse($mtn->getResponse($response['response_code']))->getResponse();
        }

        Transaction::postResponse($payment->transaction_id, $payment->response_status, $payment->response_code, $payment->response_message);
        return response()->json($_response);
    }
}
