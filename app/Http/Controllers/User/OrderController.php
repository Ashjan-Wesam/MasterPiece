<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Store;
use Carbon\Carbon;

class OrderController extends Controller
{

public function checkout(Request $request)
{
    $user = auth()->user();
    $cart = $user->cart()->with('cartProducts.product')->first();

    if (!$cart || $cart->cartProducts->isEmpty()) {
        return response()->json(['message' => 'Cart is empty'], 400);
    }

    $now = Carbon::now();

    $storeId = $cart->cartProducts->first()->product->store_id;
    $discount = Discount::where('store_id', $storeId)
        ->where('start_date', '<=', $now)
        ->where('end_date', '>=', $now)
        ->first();

    $cartTotal = $cart->cartProducts->sum(fn($item) => $item->price * $item->quantity);

   
    if ($discount) {
        $discountAmount = ($cartTotal * $discount->discount_percentage) / 100;
        $cartTotal -= $discountAmount;  
    }

    DB::beginTransaction();

    try {

        $order = Order::create([
            'user_id' => $user->id,
            'store_id' => $storeId,
            'total_price' => $cartTotal,  
            'status' => 'pending',
        ]);

       foreach ($cart->cartProducts as $item) {
    OrderDetail::create([
        'order_id' => $order->id,
        'product_id' => $item->product_id,
        'quantity' => $item->quantity,
        'unit_price' => $item->price,
        'total_price' => $item->quantity * $item->price,
        'design_request_id' => $item->design_request_id, 
    ]);
}


        Payment::create([
            'order_id' => $order->id,
            'method' => $request->payment_method,
            'status' => 'pending',
        ]);

        $cart->cartProducts()->delete();

        DB::commit();

        return response()->json(['message' => 'Order placed successfully', 'order_id' => $order->id]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json(['message' => 'Failed to place order', 'error' => $e->getMessage()], 500);
    }
}


public function index()
{
    $user = auth()->user();

    $orders = Order::with([
        'orderDetails.product.store', 
        'payment'
    ])
    ->where('user_id', $user->id)
    ->get();

    return response()->json(['orders' => $orders]);
}


public function show($id)
{
    $user = auth()->user();

    $order = Order::with([
        'orderDetails.product.store',
        'orderDetails.designRequest',
        'payment'
    ])
    ->where('id', $id)
    ->where('user_id', $user->id)
    ->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    return response()->json(['order' => $order]);
}



public function update(Request $request, $id)
{
    $user = auth()->user();
    $order = Order::with('payment')->where('user_id', $user->id)->findOrFail($id);

    if ($order->status !== 'pending' || $order->payment->method !== 'cod') {
        return response()->json(['message' => 'Cannot update this order'], 403);
    }

    $order->update($request->only(['status'])); 

    return response()->json(['message' => 'Order updated successfully']);
}

public function destroy($id)
{
    $user = auth()->user();
    $order = Order::with('payment')->where('user_id', $user->id)->findOrFail($id);

    if ($order->status !== 'pending' || $order->payment->method !== 'cod') {
        return response()->json(['message' => 'Cannot delete this order'], 403);
    }

    $order->delete();
    return response()->json(['message' => 'Order deleted successfully']);
}
}