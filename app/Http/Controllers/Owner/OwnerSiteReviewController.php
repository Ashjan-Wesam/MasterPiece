<?php

namespace App\Http\Controllers\Owner;

use App\Models\SiteReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OwnerSiteReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review_text' => 'required|string',
        ]);

        $user = Auth::user();

        $store = $user->stores; 
if (!$store) {
    return response()->json(['message' => 'No store found for this user'], 404);
}

$owner_id = $store->id;


        $review = new SiteReview();
        $review->owner_id = $owner_id;  
        $review->rating = $request->rating;
        $review->review_text = $request->review_text;
        $review->save();

        return response()->json(['message' => 'Review submitted successfully!']);
    }
public function index()
{
    $user = Auth::user();

    $store = $user->stores; 

    if (!$store) {
        return response()->json([]);
    }

    $reviews = SiteReview::where('owner_id', $store->id)
                ->latest()
                ->get();

    return response()->json($reviews);
}



}
