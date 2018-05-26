<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $fillable = ['name', 'type' ,'merchant_id', 'ses_money_id', 'pin'];

    protected $hidden = ['pin'];

    public function setSesMoneyIdAttribute($id)
    {
        $this->attributes['ses_money_id'] = 'SES-' . str_pad($id, 8, '0', STR_PAD_LEFT);
    }

    public function register(array $data)
    {
        $terminal = new Terminal();
        $terminal->save($data);
        $terminal->setSesMoneyIdAttribute($terminal->id);
        $terminal->save();
        return $terminal;
    }
}
