<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with(['owner', 'categories'])->get();
        return response()->json($stores);
    }
    


    public function show($id)
    {
        $store = Store::with([
            'owner',
            'categories',
            'products.reviews', 
            'reviews.user' 
        ])->find($id);
    
        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }
    
        $store->products->transform(function ($product) {
            $reviews = $product->reviews;
            $reviewsCount = $reviews->count();
    
            $averageRating = $reviewsCount > 0 
                ? round($reviews->sum('rating') / $reviewsCount, 2)
                : 0;
    
            $product->average_rating = $averageRating;
            $product->reviews_count = $reviewsCount;
    
            return $product;
        });
    
        return response()->json($store);
    }
    
    
public function store(Request $request)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|string',
        'password' => 'required|min:6',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'role' => 'required|in:admin,customer,owner',
        'status' => 'required|in:active,inactive',
        'categories' => 'nullable|array', 
       'categories.*' => 'integer|exists:categories,id',

    ]);

    $image_path = null;
    if ($request->hasFile('profile_picture')) {
        $image_path = uniqid() . '-' . $request->name . '.' . $request->profile_picture->extension();
        $request->profile_picture->move(public_path('storage/profile'), $image_path);
    }

    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status,
        'profile_picture' => $image_path,
        'shipping_address' => $request->shipping_address ?? null,
    ]);

    $storeCategories = [];
    if ($request->categories) {
        foreach ($request->categories as $categoryName) {
            // إذا كانت الفئة جديدة، يتم إنشاؤها أولاً
            $category = Category::firstOrCreate(['name' => $categoryName]);
            $storeCategories[] = $category->id;
        }
    }

    // إذا كان المستخدم هو مالك المتجر
    if ($user->role === 'owner') {
        // تحقق من صحة البيانات الخاصة بالمتجر
        $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // تخزين شعار المتجر إذا كان موجوداً
        $logoPath = null;
        if ($request->hasFile('logo_url')) {
            $logoPath = uniqid() . '-' . $request->name . '.' . $request->logo_url->extension();
            $request->logo_url->move(public_path('storage/logo'), $logoPath);
        }
        $store = Store::create([
            'store_name' => $request->store_name,
            'description' => $request->description,
            'logo_url' => $logoPath,
            'owner_id' => $user->id,
        ]);

        if ($storeCategories) {
            $store->categories()->attach($storeCategories);
        }
    }

    return response()->json([
        'message' => 'User created successfully',
        'user' => $user,
    ], 201);
}
 public function update(Request $request, $id)
{
    $store = Store::findOrFail($id);

    $validated = $request->validate([
        'store_name' => 'sometimes|string|max:255',
        'description' => 'nullable|string',
        'logo_url' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'categories' => 'nullable|array',
        'categories.*' => 'integer|exists:categories,id',

        'full_name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|max:255',
        'phone_number' => 'sometimes|string|max:20',
        'role' => 'sometimes|string|in:owner,admin',
        'status' => 'sometimes|string|in:active,inactive',
    ]);

    if ($request->hasFile('logo_url')) {
        if ($store->logo_url && file_exists(public_path('storage/logo/' . $store->logo_url))) {
            unlink(public_path('storage/logo/' . $store->logo_url));
        }

        $logoPath = uniqid() . '-' . $store->store_name . '.' . $request->logo_url->extension();
        $request->logo_url->move(public_path('storage/logo'), $logoPath);
        $validated['logo_url'] = $logoPath;
    }

    // تحديث بيانات المتجر بدون الحقول الخاصة بالمالك (owner)
    $storeFields = collect($validated)->only(['store_name', 'description', 'status', 'logo_url'])->toArray();
    $store->update($storeFields);

    // تحديث الفئات إن وجدت
    if ($request->has('categories')) {
        $store->categories()->sync($request->categories);
    }

    // تحديث بيانات المالك (Owner)
    $owner = $store->owner;
    if ($owner) {
        $owner->full_name = $request->input('full_name', $owner->full_name);
        $owner->email = $request->input('email', $owner->email);
        $owner->phone_number = $request->input('phone_number', $owner->phone_number);
        $owner->role = $request->input('role', $owner->role);
        $owner->save();
    }

    return response()->json([
        'message' => 'Store updated successfully',
        'store' => $store,
    ]);
}


    public function destroy($id)
    {
        $store = Store::with('owner')->findOrFail($id);

        $store->delete();

        if ($store->owner) {
            $store->owner->delete();
        }

        return response()->json(['message' => 'Store and owner deleted (soft delete).']);
    }

    public function publicIndex()
{
    $stores = Store::with(['owner', 'categories', 'products'])
                   ->whereNull('deleted_at')
                   ->get();
    return response()->json($stores);
}

}
