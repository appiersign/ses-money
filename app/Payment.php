<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'merchant_id',
        'stan',
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
}
