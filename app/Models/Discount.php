<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'discount_percentage',
        'start_date',
        'end_date',
        'description',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
