<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); 
        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id); 
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => "sometimes|required|string|max:255|unique:categories,name,{$id}", 
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name ?? $category->name,
            'description' => $request->description ?? $category->description,
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id); 
        $category->delete(); 
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
