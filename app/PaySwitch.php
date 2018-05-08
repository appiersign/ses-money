<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaySwitch extends Model
{
    private $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }
}
