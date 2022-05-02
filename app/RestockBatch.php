<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RestockBatch extends Model
{
    protected $fillable = [
        'product_id',
        'amount',
        'purchaseprice',
        'sellingprice',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function transaction()
    {
        return $this->hasOne('App\Product', 'transaction_id');
    }
}