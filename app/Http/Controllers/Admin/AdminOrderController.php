<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'store']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('store') && $request->store !== '') {
            $query->whereHas('store', function ($q) use ($request) {
                $q->where('store_name', 'LIKE', '%' . $request->store . '%');
            });
        }

        if ($request->has('customer') && $request->customer !== '') {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer . '%');
            });
        }

        $orders = $query->latest()->get();

        return response()->json($orders);
    }


    public function show($id)
    {
        $order = Order::with(['user', 'store', 'orderDetails.product'])->findOrFail($id);

        return response()->json($order);
    }

    

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'status' => 'nullable|string',
            'total_price' => 'nullable|numeric',
        ]);

        $order->update($data);

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
