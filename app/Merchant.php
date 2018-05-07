<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    private $route;

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

    public function setRoute($path = 'Merchant Registration')
    {
        $this->route = $path;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function create()
    {
        return $this->getNameAttribute();
    }
}
