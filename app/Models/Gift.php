<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'recipient_name',
        'recipient_address',
        'gift_message',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
