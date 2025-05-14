<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class UserProductController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $query = Product::with('store'); 

        if ($filter === 'new') {
            $query->where('isNew', true);
        } elseif ($filter === 'best') {
            $query->where('isBestSeller', true);
        } elseif ($filter === 'sale') {
            $query->where('isSale', true);
        }

        $products = $query->get();

        return response()->json($products);
    }

       public function show($id)
{
    $product = Product::with('category')->findOrFail($id);
    return response()->json($product);
}
    public function related($categoryId, $storeId, $productId)
    {
        $relatedProducts = Product::where('category_id', $categoryId)
            ->where('store_id', $storeId)
            ->where('id', '!=', $productId) 
            ->latest()
            ->take(6)
            ->get();
    
        return response()->json($relatedProducts);
    }
    

}
