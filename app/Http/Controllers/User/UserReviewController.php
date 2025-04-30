<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\OrderDetail;
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

    // Check if user purchased the product to allow review
    // Check if user purchased the product with completed order to allow review
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

    // $hasBought = OrderDetail::where('product_id', $request->product_id)
    // ->whereHas('order', function ($q) use ($user) {
    //     $q->where('user_id', $user->id)
    //       ->where('status', 'complete');
    // })
    // ->exists();

    // if (!$hasBought) {
    //     return response()->json(['message' => 'You cannot review this product.'], 403);
    // }

    $review = ProductReview::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
        'rating' => $request->rating,
        'review_text' => $request->review_text,
    ]);

    return response()->json(['message' => 'Review submitted successfully.', 'review' => $review], 201);
}

}

