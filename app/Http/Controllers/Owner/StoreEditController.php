<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Store;

class StoreEditController extends Controller
{

   public function show()
{
    $user = Auth::user(); 
    $store = Store::with([
        'owner',
        'categories',
        'products.reviews',
        'reviews.user'
    ])->where('owner_id', $user->id) 
      ->first(); 

    if (!$store) {
        return response()->json(['message' => 'Store not found'], 404);
    }

    $store->products->transform(function ($product) {
        $reviews = $product->reviews;
        $reviewsCount = $reviews->count();

        $averageRating = $reviewsCount > 0 
            ? round($reviews->sum('rating') / $reviewsCount, 2)
            : 0;

        $product->average_rating = $averageRating;
        $product->reviews_count = $reviewsCount;

        return $product;
    });

    return response()->json($store);
}
public function update(Request $request, $id)
{
    $store = Store::findOrFail($id);

    if (auth()->id() !== $store->owner_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'store_name' => 'required|string|max:255',
        'description' => 'required|string',
        'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

   if ($request->hasFile('logo')) {

        if ($store->logo_url && file_exists(public_path('storage/logo/' . $store->logo_url))) {
            unlink(public_path('storage/logo/' . $store->logo_url));
        }

        $fileName = uniqid() . '-' . $request->file('logo')->getClientOriginalName();

        $request->file('logo')->move(public_path('storage/logo'), $fileName);

        $store->logo_url = $fileName;
    }

    $store->store_name = $validated['store_name'];
    $store->description = $validated['description'];
    $store->save();

    return response()->json([
        'message' => 'Store updated successfully.',
        'store' => $store,
    ]);
}

}