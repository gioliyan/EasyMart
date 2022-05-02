<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'purchaseprice',
        'sellingprice', 
        'description'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function productImages()
    {
        return $this->hasMany('App\ProductImage');
    }

    public function Transactions()
    {
        return $this->hasMany('App\Transactions');
    }
    
}