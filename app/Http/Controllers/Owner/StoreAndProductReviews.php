<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StoreReview;
use App\Models\ProductReview;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class StoreAndProductReviews extends Controller
{
    public function getStoreReviews()
{
    $owner = Auth::user();

    $store = Store::where('owner_id', $owner->id)->first();

    if (!$store) {
        return response()->json(['message' => 'Store not found.'], 404);
    }

    $storeReviews = StoreReview::with('user') 
        ->where('store_id', $store->id)
        ->get();

    return response()->json(['store_reviews' => $storeReviews]);
}
public function getProductReviews()
{
    $owner = Auth::user();

    $store = Store::where('owner_id', $owner->id)->first();

    if (!$store) {
        return response()->json(['message' => 'Store not found.'], 404);
    }

    $productIds = $store->products()->pluck('id');

    $productReviews = ProductReview::with(['user', 'product']) 
        ->whereIn('product_id', $productIds)
        ->get();

    return response()->json(['product_reviews' => $productReviews]);
}


}

