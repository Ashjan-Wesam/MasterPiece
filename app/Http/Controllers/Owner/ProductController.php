<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $ownerId = auth()->user()->id;
        $products = Product::where('store_id', $ownerId)->get(); 
        return response()->json($products);
    }

    public function show($id)
    {
        $ownerId = auth()->user()->id;
        $product = Product::where('store_id', $ownerId)->findOrFail($id); 
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|string',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'image_url' => $request->image_url,
            'store_id' => auth()->user()->store->id, 
        ]);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $ownerId = auth()->user()->id;
        $product = Product::where('store_id', $ownerId)->findOrFail($id); 

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'stock_quantity' => 'sometimes|required|integer',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image_url' => 'nullable|string',
        ]);

        $product->update([
            'name' => $request->name ?? $product->name,
            'description' => $request->description ?? $product->description,
            'price' => $request->price ?? $product->price,
            'stock_quantity' => $request->stock_quantity ?? $product->stock_quantity,
            'category_id' => $request->category_id ?? $product->category_id,
            'image_url' => $request->image_url ?? $product->image_url,
        ]);

        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    public function destroy($id)
    {
        $ownerId = auth()->user()->id;
        $product = Product::where('store_id', $ownerId)->findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
