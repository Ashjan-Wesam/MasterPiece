<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'password',
        'profile_picture',
        'shipping_address',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'role' => 'string',
        'status' => 'string',
    ];

    public function stores()
    {
        return $this->hasOne(Store::class, 'owner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function designRequests()
    {
        return $this->hasMany(DesignRequest::class);
    }

    public function storeReviews()
    {
        return $this->hasMany(StoreReview::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
