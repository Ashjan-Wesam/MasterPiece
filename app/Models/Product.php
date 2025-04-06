<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'store_id',
        'category_id',
        'image_url',
        'request',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function designRequests()
    {
        return $this->hasMany(DesignRequest::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlistUsers()
    {
        return $this->belongsToMany(User::class, 'wishlist')->withTimestamps();
    }

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
