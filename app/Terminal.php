<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $fillable = ['name', 'type' ,'merchant_id', 'ses_money_id', 'pin'];

    public function setSesMoneyIdAttribute($id)
    {
        $this->attributes['ses_money_id'] = 'SES-' . str_pad($id, 8, '0', STR_PAD_LEFT);
    }
}
