<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Discount;
use App\Models\Store;
use Carbon\Carbon;


class CartController extends Controller
{
    public function checkStoreMatch($productId)
    {
        $user = Auth::user();
        $productToAdd = Product::findOrFail($productId);
        $storeIdToAdd = $productToAdd->store_id;

        $cart = $user->cart;

        if (!$cart || $cart->cartProducts->isEmpty()) {
            return response()->json(['match' => true]);
        }

        $existingStoreId = $cart->cartProducts->first()->product->store_id;

        if ($existingStoreId != $storeIdToAdd) {
            return response()->json(['match' => false]);
        }

        return response()->json(['match' => true]);
    }

    public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'design_request_id' => 'nullable|exists:design_requests,id',
    ]);

    $user = Auth::user();
    $product = Product::findOrFail($request->product_id);

    $cart = $user->cart ?? Cart::create(['user_id' => $user->id, 'added_at' => now()]);

    $existing = $cart->cartProducts()
        ->where('product_id', $product->id)
        ->where('design_request_id', $request->design_request_id) 
        ->first();

    if ($existing) {
        $existing->quantity += $request->quantity;
        $existing->save();
    } else {
        $cart->cartProducts()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'design_request_id' => $request->design_request_id,
        ]);
    }

    return response()->json(['message' => 'Product added to cart']);
}


    public function clearCart()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart) {
            $cart->cartProducts()->delete();
        }

        return response()->json(['message' => 'Cart cleared']);
    }

    public function updateQuantity(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $user = auth()->user();
    $cart = Cart::where('user_id', $user->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'Cart not found'], 404);
    }

    $cartProduct = $cart->cartProducts()->where('product_id', $request->product_id)->first();

    if (!$cartProduct) {
        return response()->json(['message' => 'Product not found in cart'], 404);
    }

    $cartProduct->quantity = $request->quantity;
    $cartProduct->save();

    return response()->json(['message' => 'Quantity updated']);
}


    

    public function getCart()
{
    $user = auth()->user();

    $cart = Cart::with('cartProducts.product')->where('user_id', $user->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'Cart is empty', 'cart' => []]);
    }

    return response()->json([
        'cart' => $cart
    ]);
}


public function removeFromCart($productId)
{
    $user = auth()->user();

    $cart = Cart::where('user_id', $user->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'Cart not found'], 404);
    }

    $cartProduct = $cart->cartProducts()->where('product_id', $productId)->first();

    if (!$cartProduct) {
        return response()->json(['message' => 'Product not found in cart'], 404);
    }

    $cartProduct->delete();

    return response()->json(['message' => 'Product removed from cart']);
}
public function getCartCount()
    {
        $user = auth()->user();

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json(['count' => 0]);
        }

        $count = $cart->cartProducts()->sum('quantity'); 

        return response()->json(['count' => $count]);
    }
public function checkStoreDiscount($storeId)
{
    $now = Carbon::now();

    $discount = Discount::where('store_id', $storeId)
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->first();

    if ($discount) {
        return response()->json([
            'active' => true,
            'discount_percentage' => $discount->discount_percentage
        ]);
    }

    return response()->json(['active' => false]);
}


}
