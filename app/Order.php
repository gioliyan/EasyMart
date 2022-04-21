<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'no_order',
        'total',
        'transaction_id',
        'payment',
        'change'
    ];
    
    public function orderDetails()
    {
        return $this->hasMany('App\OrderDetail', 'order_id');
    }
}
