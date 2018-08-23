<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    protected $fillable = ['name', 'type' ,'merchant_id', 'ses_money_id', 'pin'];

    protected $hidden = ['pin'];

    public function setSesMoneyIdAttribute()
    {
        $this->attributes['ses_money_id'] = 'SES-' . str_pad($this->attributes['id'], 8, '0', STR_PAD_LEFT);
        return $this;
    }

    public function register(array $data)
    {
        $terminal = Terminal::create($data);
        $terminal->setSesMoneyIdAttribute($terminal->id);
        $terminal->save();
        return $terminal;
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
