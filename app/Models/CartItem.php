<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    protected $table = "cart_item";
    protected $fillable = ['product_id', 'cart_id', 'quantity'];
    public $timestamps = true;

    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
