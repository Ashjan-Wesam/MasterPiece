<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
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
    $store = Store::with('owner', 'categories', 'products')->find($id);

    if (!$store) {
        return response()->json(['message' => 'Store not found'], 404);
    }

    return response()->json($store);
}

public function store(Request $request)
{
    // تحقق من صحة البيانات
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|string',
        'password' => 'required|min:6',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'role' => 'required|in:admin,customer,owner',
        'status' => 'required|in:active,inactive',
        'categories' => 'nullable|array', // التحقق من أن الفئات مصفوفة
        'categories.*' => 'string|distinct|exists:categories,name', // التحقق من أن الفئات موجودة
    ]);

    // تخزين صورة الملف الشخصي إذا كانت موجودة
    $image_path = null;
    if ($request->hasFile('profile_picture')) {
        $image_path = uniqid() . '-' . $request->name . '.' . $request->profile_picture->extension();
        $request->profile_picture->move(public_path('storage/profile'), $image_path);
    }

    // إنشاء المستخدم
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

    // التعامل مع الفئات إذا تم إرسالها
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

        // إنشاء المتجر
        $store = Store::create([
            'store_name' => $request->store_name,
            'description' => $request->description,
            'logo_url' => $logoPath,
            'owner_id' => $user->id,
        ]);

        // ربط الفئات بالمتجر
        if ($storeCategories) {
            $store->categories()->attach($storeCategories);
        }
    }

    // إرجاع الاستجابة بنجاح
    return response()->json([
        'message' => 'User created successfully',
        'user' => $user,
    ], 201);
}
    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'store_name' => 'sometimes|string',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
        ]);

        $store->update($validated);

        return response()->json($store);
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
