<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $wishlist = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($wishlist);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($exists) {
            return response()->json(['message' => 'Product already in wishlist'], 409);
        }

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'added_at' => now(),
        ]);

        return response()->json(['message' => 'Product added to wishlist', 'wishlist' => $wishlist]);
    }

    public function destroy($productId)
    {
        $user = Auth::user();

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlist) {
            return response()->json(['message' => 'Product not found in wishlist'], 404);
        }

        $wishlist->delete();

        return response()->json(['message' => 'Product removed from wishlist']);
    }
}
