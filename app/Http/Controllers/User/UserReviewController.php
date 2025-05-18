<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Store;
use App\Models\StoreReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReviewController extends Controller
{
    // Get all reviews for a product
    public function index($productId)
    {
        $reviews = ProductReview::with('user')
            ->where('product_id', $productId)
            ->latest()
            ->get();

        return response()->json($reviews);
    }

public function canReview($productId)
{
    $user = Auth::user();

    $hasBought = \App\Models\OrderDetail::where('product_id', $productId)
        ->whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->where('status', 'completed'); 
        })
        ->exists();

    return response()->json(['allowed' => $hasBought]);
}


    // Store a new review
    public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|integer|min:1|max:5',
        'review_text' => 'nullable|string|max:1000',
    ]);
    $user = $request->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $user = Auth::user();

    $review = ProductReview::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'rating' => $request->rating,
        'review_text' => $request->review_text,
    ]);

    return response()->json(['message' => 'Review submitted successfully.', 'review' => $review], 201);
}
public function storeReviews($storeId)
{
    $reviews = StoreReview::with('user')
        ->where('store_id', $storeId)
        ->latest()
        ->get();

    return response()->json($reviews);
}

// Check if user can review a store (if they bought from it before)
public function canReviewStore($storeId)
{
    $user = Auth::user();

    $hasBought = Order::where('user_id', $user->id)
        ->where('status', 'completed')
        ->whereHas('orderDetails.product', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })
        ->exists();

    return response()->json(['allowed' => $hasBought]);
}

// Submit a review for a store
public function submitStoreReview(Request $request)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
        'rating' => 'required|integer|min:1|max:5',
        'review_text' => 'nullable|string|max:1000',
    ]);

    $user = Auth::user();

    // Optional: Check again if user is allowed
    $hasBought = Order::where('user_id', $user->id)
        ->where('status', 'completed')
        ->whereHas('orderDetails.product', function ($query) use ($request) {
            $query->where('store_id', $request->store_id);
        })
        ->exists();

    if (!$hasBought) {
        return response()->json(['error' => 'You are not allowed to review this store.'], 403);
    }

    $review = StoreReview::create([
        'user_id' => $user->id,
        'store_id' => $request->store_id,
        'rating' => $request->rating,
        'review_text' => $request->review_text,
    ]);

    return response()->json(['message' => 'Store review submitted successfully.', 'review' => $review], 201);
}
public function stats($storeId)
{
    $store = Store::find($storeId);
    
    if (!$store) {
        return response()->json(['message' => 'Store not found'], 404);
    }

    $productsCount = Product::where('store_id', $storeId)->count();

    $completedOrdersCount = Order::where('store_id', $storeId)
                                ->where('status', 'completed')
                                ->count();

    $storeReviews = StoreReview::where('store_id', $storeId)->get();
    $storeReviewsCount = $storeReviews->count();

    $storeAverageRating = $storeReviewsCount > 0 
        ? round($storeReviews->sum('rating') / $storeReviewsCount, 2) 
        : 0;

    $productIds = Product::where('store_id', $storeId)->pluck('id');

    $productReviews = ProductReview::whereIn('product_id', $productIds)->get();
    $productReviewsCount = $productReviews->count();

    $productAverageRating = $productReviewsCount > 0 
        ? round($productReviews->sum('rating') / $productReviewsCount, 2)
        : 0;

    return response()->json([
        'products_count' => $productsCount,
        'completed_orders_count' => $completedOrdersCount,
        'reviews_count' => $storeReviewsCount,
        'average_rating' => $storeAverageRating,
        'product_reviews_count' => $productReviewsCount,
        'product_average_rating' => $productAverageRating,
    ]);
}

public function topRatedStores()
{
    $stores = Store::with(['reviews', 'categories'])->get();

    $topStores = $stores->filter(function ($store) {
        $reviewsCount = $store->reviews->count();
        $averageRating = $reviewsCount > 0
            ? round($store->reviews->sum('rating') / $reviewsCount, 2)
            : 0;

        return $averageRating >= 4 && $reviewsCount >= 5;
    })->map(function ($store) {
        $reviewsCount = $store->reviews->count();
        $averageRating = $reviewsCount > 0
            ? round($store->reviews->sum('rating') / $reviewsCount, 2)
            : 0;

        return [
            'id' => $store->id,
            'name' => $store->store_name,
            'logo' => $store->logo_url,
            'average_rating' => $averageRating,
            'reviews_count' => $reviewsCount,
            'categories' => $store->categories->pluck('name'), 
        ];
    })->values();

    return response()->json($topStores);
}




}

