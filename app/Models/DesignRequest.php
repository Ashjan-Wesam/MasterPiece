<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'design_details',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function cartProduct()
{
    return $this->hasOne(CartProduct::class);
}

public function orderDetail()
{
    return $this->hasOne(OrderDetail::class);
}

}
