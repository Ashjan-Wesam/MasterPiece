<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    // ✅ Get all products
    public function index()
    {
        $products = Product::with(['store', 'category'])->latest()->get();
        return response()->json($products);
    }

    // ✅ Get single product
    public function show($id)
    {
        $product = Product::with(['store', 'category'])->findOrFail($id);
        return response()->json($product);
    }

    // ✅ Create new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string|max:500',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'store_id'       => 'required|exists:stores,id',
            'category_id'    => 'required|exists:categories,id',
            'image_url'      => 'nullable|string',
            'request'        => 'nullable|in:yes,no',
        ]);

        $product = Product::create($validated);
        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    // ✅ Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string|max:500',
            'price'          => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'store_id'       => 'required|exists:stores,id',
            'category_id'    => 'required|exists:categories,id',
            'image_url'      => 'nullable|string',
            'request'        => 'nullable|in:yes,no',
        ]);

        $product->update($validated);
        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    // ✅ Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
