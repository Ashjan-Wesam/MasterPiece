<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{

public function checkout(Request $request)
{
    $user = auth()->user();
    $cart = $user->cart()->with('cartProducts.product')->first();

    if (!$cart || $cart->cartProducts->isEmpty()) {
        return response()->json(['message' => 'Cart is empty'], 400);
    }

    DB::beginTransaction();

    try {
        $order = Order::create([
            'user_id' => $user->id,
            'store_id' => $cart->cartProducts->first()->product->store_id, // أو حسب المنطق اللي بدك ياه
            'total_price' => $cart->cartProducts->sum(fn($item) => $item->price * $item->quantity),
            'status' => 'pending',
        ]);

        foreach ($cart->cartProducts as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
                'total_price' => $item->quantity * $item->price,
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
    $orders = Order::with(['orderDetails.product', 'payment'])
        ->where('user_id', $user->id)
        ->get();

        return response()->json(['orders' => $orders]);

}

public function show($id)
{
    $user = auth()->user();

    $order = Order::with(['orderDetails.product', 'payment'])
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