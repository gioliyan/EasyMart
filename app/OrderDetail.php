<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
    
    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Product', 'transaction_id');
    }
}