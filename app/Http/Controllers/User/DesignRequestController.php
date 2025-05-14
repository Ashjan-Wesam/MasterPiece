<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DesignRequest;

class DesignRequestController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'design_details' => 'required|string',
        ]);

        $requestData = DesignRequest::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'design_details' => $request->design_details,
        ]);

        return response()->json($requestData, 201);
    }

    public function updateStatus(Request $request, DesignRequest $designRequest)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $designRequest->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated.']);
    }

 public function userRequests()
{
    $designIdsInCart = \App\Models\CartProduct::whereNotNull('design_request_id')
        ->pluck('design_request_id')
        ->toArray();

    $designIdsInOrders = \App\Models\OrderDetail::whereNotNull('design_request_id')
        ->pluck('design_request_id')
        ->toArray();

    $excludedDesignIds = array_merge($designIdsInCart, $designIdsInOrders);

    $requests = \App\Models\DesignRequest::with('product')
        ->where('user_id', auth()->id())
        ->whereNotIn('id', $excludedDesignIds)
        ->latest()
        ->get();

    return response()->json($requests);
}

    
}
