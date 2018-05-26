<?php

namespace App;

use App\Jobs\CreateMerchant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Merchant extends Model
{
    private $route;

    protected $fillable = [
        'name',
        'email',
        'ses_money_id',
        'api_user',
        'api_key',
        'password',
        'merchant_id',
        'address',
        'phone_number',
        'is_active',
        'limit'
    ];

    protected $hidden = ['password'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    public function getNameAttribute()
    {
        return ucwords($this->attributes['name']);
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function getEmailAttribute()
    {
        return $this->attributes['email'];
    }

    public function setSesMoneyId($value)
    {
        $this->attributes['ses_money_id'] = strtoupper($value);
    }

    public function setPhoneNumberAttribute($telephone)
    {
        $this->attributes['phone_number'] = $telephone;
    }

    public function getPhoneNumberAttribute()
    {
        return $this->attributes['phone_number'];
    }

    public function setAddressAttribute($address)
    {
        $this->attributes['address'] = $address;
    }

    public function getAddressAttribute()
    {
        return $this->attributes['address'];
    }

    public function setAccountIssuerAttribute($value)
    {
        $this->attributes['account_issuer'] = $value;
    }

    public function setAccountNumberAttribute($value)
    {
        $this->attributes['account_number'] = $value;
    }

    public function setAccountBranchAttribute($value)
    {
        $this->attributes['account_branch'] = $value;
    }

    public function setRoute($path = 'Merchant Registration')
    {
        $this->route = $path;
    }

    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return HasMany
     */
    public function terminals(): HasMany
    {
        return $this->hasMany(Terminal::class);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getTerminalIds(): Collection
    {
        return $this->terminals()->pluck('ses_money_id');
    }

    /**
     * @param string $terminal_id
     * @return bool
     */
    public function hasTerminal(string $terminal_id): bool
    {
        return $this->getTerminalIds()->search($terminal_id);
    }
}
