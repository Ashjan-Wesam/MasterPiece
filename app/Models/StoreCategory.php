<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StoreCategory extends Pivot
{
    use HasFactory;

    protected $table = 'store_category';

    protected $fillable = [
        'store_id',
        'category_id',
    ];
}
