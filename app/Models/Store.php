<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Store extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'store_name',
        'description',
        'logo_url',
        'owner_id',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'store_category');
    }

    
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(StoreReview::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function designRequests()
{
    return $this->hasManyThrough(
        \App\Models\DesignRequest::class, 
        \App\Models\Product::class,       
        'store_id',                      
        'product_id',                   
        'id',  
        'id'
    );
}

}
