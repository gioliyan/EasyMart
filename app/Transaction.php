<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'product_id',
        'batch_id',
        'user_id',
        'type',
        'amount',
        'initial_amount',
        'margin'
    ];

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function user()
    {
        return $this->hasMany('App\User','user_id');
    }

    public function restock()
    {
        return $this->hasOne('App\RestockBatch', 'restockbatch_id');
    }
}