<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    use HasFactory;


    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'design_request_id',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

public function designRequest()
{
    return $this->belongsTo(DesignRequest::class);
}

}
