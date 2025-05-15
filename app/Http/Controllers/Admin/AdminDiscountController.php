<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminDiscountController extends Controller
{
    
    public function index()
    {
        $discounts = Discount::with('store')->paginate(10);
        return response()->json($discounts);
    }

    public function create()
    {
        $stores = Store::all();
        return response()->json($stores);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        $discount = Discount::create($validated);

        return response()->json([
            'message' => 'Discount created successfully',
            'discount' => $discount
        ], 201);
    }

    public function show($id)
    {
        $discount = Discount::with('store')->findOrFail($id);
        return response()->json($discount);
    }
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        $stores = Store::all();
        return response()->json([
            'discount' => $discount,
            'stores' => $stores,
        ]);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'discount_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
        ]);

        $discount->update($validated);

        return response()->json([
            'message' => 'Discount updated successfully',
            'discount' => $discount
        ]);
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

        return response()->json(['message' => 'Discount deleted successfully']);
    }
}
