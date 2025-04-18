<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); 
        return response()->json($categories);
    }



    public function show(Request $request, $id)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id'
    ]);

    $category = Category::with(['products' => function ($query) use ($request) {
        $query->where('store_id', $request->store_id);
    }])->findOrFail($id);

    return response()->json($category);
}




    
    public function store(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.name' => 'required|string|max:255',
            'store_id' => 'required|exists:stores,id',
        ]);
    
        $existing = [];
        $created = [];
    
        $categoryIds = [];
    
        foreach ($request->categories as $cat) {
            $existingCategory = Category::where('name', $cat['name'])->first();
    
            if ($existingCategory) {
                $existing[] = $existingCategory;
                $categoryIds[] = $existingCategory->id;
            } else {
                $newCategory = Category::create([
                    'name' => $cat['name'],
                    'description' => $cat['description'] ?? null,
                ]);
                $created[] = $newCategory;
                $categoryIds[] = $newCategory->id;
            }
        }
    
        $store = Store::find($request->store_id);
        $store->categories()->syncWithoutDetaching($categoryIds);
    
        return response()->json([
            'message' => 'Categories processed and linked to the store.',
            'created' => $created,
            'existing' => $existing,
        ]);
    }
    

    


    
public function update(Request $request, $categoryId)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
        'new_category_id' => 'required|exists:categories,id',
    ]);

    $store = Store::findOrFail($request->store_id);

    $store->categories()->detach($categoryId);
    $store->categories()->attach($request->new_category_id);

    Product::where('store_id', $store->id)
        ->where('category_id', $categoryId)
        ->update(['category_id' => $request->new_category_id]);

    return response()->json([
        'message' => 'Category updated in pivot table and related products updated successfully',
    ]);
}




public function destroy(Request $request, $categoryId)
{
    $request->validate([
        'store_id' => 'required|exists:stores,id',
    ]);

    $store = Store::findOrFail($request->store_id);

    $store->categories()->detach($categoryId);

    \App\Models\Product::where('store_id', $store->id)
        ->where('category_id', $categoryId)
        ->delete();

    return response()->json(['message' => 'Category unlinked and related products deleted successfully']);
}




    public function myCategories(Request $request)
    {
        $user = auth()->user();
    
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
    
        $store = $user->stores;
    
        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }
    
        $categories = $store->categories;
    
        return response()->json($categories);
    }
    
    

}
