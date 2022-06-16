<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'no_order',
        'total',
        'payment',
        'change',
        'phone_number',
        'token',
        'payment_type',
        'transaction_status',
        'settlement_time',
    ];
    
    public function orderDetails()
    {
        return $this->hasMany('App\OrderDetail');
    }
}