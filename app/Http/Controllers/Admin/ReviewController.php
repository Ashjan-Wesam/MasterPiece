<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\SiteReview;
use App\Models\StoreReview;

class ReviewController extends Controller
{
    public function index()
    {
        $productReviews = ProductReview::with(['user', 'product'])->latest()->get();
        $storeReviews = StoreReview::with(['user', 'store'])->latest()->get();
        $siteReviews = SiteReview::with('store')->latest()->get();

        return response()->json([
            'product_reviews' => $productReviews,
            'store_reviews' => $storeReviews,
            'site_reviews' => $siteReviews,
        ]);
    }

    public function showProductReviews()
    {
        $reviews = ProductReview::with(['user', 'product'])->latest()->get();
        return response()->json($reviews);
    }

    public function showStoreReviews()
    {
        $reviews = StoreReview::with(['user', 'store'])->latest()->get();
        return response()->json($reviews);
    }

    public function showSiteReviews()
    {
        $reviews = SiteReview::with('store')->latest()->get();
        return response()->json($reviews);
    }

    public function destroy($type, $id)
    {
        switch ($type) {
            case 'product':
                $review = ProductReview::findOrFail($id);
                break;
            case 'store':
                $review = StoreReview::findOrFail($id);
                break;
            case 'site':
                $review = SiteReview::findOrFail($id);
                break;
            default:
                return response()->json(['message' => 'Invalid review type'], 400);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
