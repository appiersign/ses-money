<?php

namespace App;

use App\Http\Requests\CreatePaymentRequest;
use Illuminate\Database\Eloquent\Model;

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
}
