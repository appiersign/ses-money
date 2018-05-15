<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
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

    public function getAccountNumberAttribute()
    {
        return '233'.substr($this->attributes['account_number'], 1);
    }

    public function getAmountAttribute()
    {
        return (int) $this->attributes['amount'] / 100;
    }
}
