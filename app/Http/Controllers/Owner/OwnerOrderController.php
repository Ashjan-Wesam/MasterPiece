<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DesignRequest;
use Illuminate\Http\Request;

class OwnerOrderController extends Controller
{
    public function index(Request $request)
{
    $store = auth()->user()->stores;

    if (!$store) {
        return response()->json(['message' => 'Owner has no store.'], 403);
    }

    $orders = Order::with([
            'user',
            'orderDetails.product',
            'orderDetails.designRequest' 
        ])
        ->where('store_id', $store->id)
        ->get();

    return response()->json($orders);
}


    public function updateDesignStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $design = DesignRequest::findOrFail($id);
        $design->status = $request->status;
        $design->save();

        return response()->json(['message' => 'Design status updated successfully']);
    }

    public function markOrderCompleted($id)
    {
        $order = Order::findOrFail($id);

        if ($order->store_id !== auth()->user()->stores->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->status = 'completed';
        $order->save();

        return response()->json(['message' => 'Order marked as completed']);
    }

    public function designRequestsOnly()
    {
        $store = auth()->user()->stores;  
    
        $designRequests = $store->products->flatMap(function ($product) {
          
            return $product->designRequests->map(function ($designRequest) use ($product) {
                return [
                    'design_request' => $designRequest,
                    'product' => $product,
                ];
            });
        });
    
        if ($designRequests->isEmpty()) {
            return response()->json(['message' => 'لا توجد طلبات تصميم حالياً'], 404);
        }
    
        return response()->json($designRequests);
    }
    



}
