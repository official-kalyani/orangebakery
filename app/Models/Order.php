<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function store()
    {
    	return $this->hasOne('App\Shop');
    }
}
