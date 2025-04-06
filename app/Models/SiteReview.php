<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'rating',
        'review_text',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'owner_id');
    }
}
