<?php

// app/Http/Controllers/Owner/DiscountController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    public function index()
    {
        $store = Auth::user()->stores;
        $discounts = Discount::where('store_id', $store->id)->get();
        return response()->json($discounts);
    }

  public function store(Request $request)
{
    $request->validate([
        'discount_percentage' => 'required|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'description' => 'nullable|string',
    ]);

    $store = Auth::user()->stores;

    $activeDiscount = Discount::where('store_id', $store->id)
        ->whereDate('start_date', '<=', $request->start_date)
        ->whereDate('end_date', '>=', $request->start_date)
        ->first();

    if ($activeDiscount) {
        return response()->json([
            'message' => 'An active discount already exists. You cannot add a new overlapping discount.'
        ], 409); 
    }

    $discount = Discount::create([
        'store_id' => $store->id,
        'discount_percentage' => $request->discount_percentage,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'description' => $request->description,
    ]);

    return response()->json(['message' => 'Discount created', 'discount' => $discount]);
}


    public function show($id)
    {
        $store = Auth::user()->stores;
        $discount = Discount::findOrFail($id);

        if ($discount->store_id !== $store->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($discount);
    }
public function update(Request $request, $id)
{
    $discount = Discount::findOrFail($id);

    // Get the store from the discount
    $store = $discount->store;

    // Check if the authenticated user owns the store
    if (!$store || $store->owner_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Validate the request
    $request->validate([
        'discount_percentage' => 'required|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'description' => 'nullable|string',
    ]);

    // Check for overlapping discounts (excluding current discount)
    $overlapping = Discount::where('store_id', $store->id)
        ->where('id', '!=', $discount->id)
        ->where(function ($query) use ($request) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                  ->orWhere(function ($q) use ($request) {
                      $q->where('start_date', '<=', $request->start_date)
                        ->where('end_date', '>=', $request->end_date);
                  });
        })
        ->first();

    if ($overlapping) {
        return response()->json([
            'message' => 'An active discount already exists in this period. Dates cannot overlap.'
        ], 422);
    }

    // Update the discount
    $discount->update($request->only([
        'discount_percentage', 'start_date', 'end_date', 'description'
    ]));

    return response()->json(['message' => 'Discount updated', 'discount' => $discount]);
}


    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $store = Auth::user()->stores->first();

        if ($discount->store_id !== $store->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $discount->delete();

        return response()->json(['message' => 'Discount deleted']);
    }
}

