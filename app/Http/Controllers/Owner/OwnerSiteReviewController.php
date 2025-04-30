<?php

namespace App\Http\Controllers\Owner;

use App\Models\SiteReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OwnerSiteReviewController extends Controller
{
    // إنشاء ريفيو جديد
    public function store(Request $request)
    {
        // تحقق من البيانات القادمة
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review_text' => 'required|string',
        ]);

        // الحصول على الـ user الذي قام بالتسجيل (من خلال التوكن)
        $user = Auth::user();

        // الحصول على الـ owner_id (يجب أن يكون هذا متاح من الـ user)
        $store = $user->stores; // جلب المتجر
if (!$store) {
    return response()->json(['message' => 'No store found for this user'], 404);
}

$owner_id = $store->id;


        // إنشاء المراجعة
        $review = new SiteReview();
        $review->owner_id = $owner_id;  // قم بربط المراجعة بالـ owner_id
        $review->rating = $request->rating;
        $review->review_text = $request->review_text;
        $review->save();

        return response()->json(['message' => 'Review submitted successfully!']);
    }
    // عرض جميع الريفيوهات (اختياري لو بدك)
    public function index()
    {
        $reviews = SiteReview::with('store')->latest()->get();

        return response()->json($reviews);
    }
}
