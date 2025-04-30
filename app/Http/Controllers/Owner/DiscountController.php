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
        $store = Auth::user()->stores->first();
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

        $store = Auth::user()->stores->first();

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
        $store = Auth::user()->stores->first();
        $discount = Discount::findOrFail($id);

        if ($discount->store_id !== $store->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($discount);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);
        $store = Auth::user()->stores->first();

        if ($discount->store_id !== $store->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $discount->update($request->all());

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

