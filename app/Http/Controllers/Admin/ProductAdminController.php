<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    public function index()
    {
        $products = Product::with(['store', 'category'])->latest()->get();
        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['store', 'category'])->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
{
    $rules = [
        'name'           => 'required|string|max:100',
        'description'    => 'nullable|string|max:500',
        'price'          => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'store_id'       => 'required|exists:stores,id',
        'category_id'    => 'required|exists:categories,id',
        'image_url'      => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'request'        => 'nullable|in:yes,no',
    ];

    $messages = [
        'name.required'           => 'Product name is required.',
        'name.string'             => 'Product name must be a string.',
        'name.max'                => 'Product name may not be greater than 100 characters.',
        'description.string'      => 'Description must be a string.',
        'description.max'         => 'Description may not be greater than 500 characters.',
        'price.required'          => 'Price is required.',
        'price.numeric'           => 'Price must be a number.',
        'price.min'               => 'Price must be at least 0.',
        'stock_quantity.required' => 'Stock quantity is required.',
        'stock_quantity.integer'  => 'Stock quantity must be an integer.',
        'stock_quantity.min'      => 'Stock quantity must be at least 0.',
        'store_id.required'       => 'Store is required.',
        'store_id.exists'         => 'The selected store is invalid.',
        'category_id.required'    => 'Category is required.',
        'category_id.exists'      => 'The selected category is invalid.',
        'image_url.required'      => 'Product image is required.',
        'image_url.file'          => 'The file must be a valid image.',
        'image_url.image'         => 'The file must be an image.',
        'image_url.mimes'         => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        'image_url.max'           => 'The image size may not be greater than 2MB.',
        'request.in'              => 'The request field must be either "yes" or "no".',
    ];

    $validated = $request->validate($rules, $messages);

    if ($request->hasFile('image_url')) {
        $image = $request->file('image_url');
        $imageName = uniqid() . '-' . preg_replace('/\s+/', '-', $request->name) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/products'), $imageName);
        $validated['image_url'] = 'storage/products/' . $imageName;
    }

    $product = Product::create($validated);

    return response()->json([
        'message' => 'Product created successfully',
        'product' => $product
    ], 201);
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $rules = [
        'name'           => 'required|string|max:100',
        'description'    => 'nullable|string|max:500',
        'price'          => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'store_id'       => 'required|exists:stores,id',
        'category_id'    => 'required|exists:categories,id',
        'image_url'      => 'nullable|file|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'request'        => 'nullable|in:yes,no',
    ];

    $messages = [
        'name.required'           => 'Product name is required.',
        'name.string'             => 'Product name must be a string.',
        'name.max'                => 'Product name may not be greater than 100 characters.',
        'description.string'      => 'Description must be a string.',
        'description.max'         => 'Description may not be greater than 500 characters.',
        'price.required'          => 'Price is required.',
        'price.numeric'           => 'Price must be a number.',
        'price.min'               => 'Price must be at least 0.',
        'stock_quantity.required' => 'Stock quantity is required.',
        'stock_quantity.integer'  => 'Stock quantity must be an integer.',
        'stock_quantity.min'      => 'Stock quantity must be at least 0.',
        'store_id.required'       => 'Store is required.',
        'store_id.exists'         => 'The selected store is invalid.',
        'category_id.required'    => 'Category is required.',
        'category_id.exists'      => 'The selected category is invalid.',
        'image_url.file'          => 'The file must be a valid image.',
        'image_url.image'         => 'The file must be an image.',
        'image_url.mimes'         => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        'image_url.max'           => 'The image size may not be greater than 2MB.',
        'request.in'              => 'The request field must be either "yes" or "no".',
    ];

    $validated = $request->validate($rules, $messages);

    if ($request->hasFile('image_url')) {
        $image = $request->file('image_url');
        $imageName = uniqid() . '-' . preg_replace('/\s+/', '-', $request->name) . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/products'), $imageName);
        $validated['image_url'] = 'storage/products/' . $imageName;
    }

    $product->update($validated);

    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product
    ]);
}


    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
