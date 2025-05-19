<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Store;
use App\Models\Category;

class AuthController extends Controller
{
   public function registerCustomer(Request $request)
{
    $request->validate([
        'full_name' => 'required|string',
        'email' => 'required|email|unique:users',
        'phone_number' => 'required|unique:users',
        'password' => [
            'required',
            'min:8',
            'regex:/^[A-Z][A-Za-z\d@$!%*?&]{7,}$/', 
            'regex:/[!@#$%^&*(),.?":{}|<>]/' 
        ],
    ], [
        'full_name.required' => 'Full name is required.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already taken.',
        'phone_number.required' => 'Phone number is required.',
        'phone_number.unique' => 'This phone number is already taken.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.regex' => 'Password must start with a capital letter and include at least one special character.',
    ]);

    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'role' => 'customer',
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Customer registered',
        'token' => $token,
        'user' => $user
    ]);
}


    
public function registerOwner(Request $request)
{
    $request->validate([
        'full_name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|unique:users,phone_number',
        'password' => 'required|min:6',
        'store_name' => 'required|string|unique:stores,store_name',
        'description' => 'nullable|string',
        'category_ids' => 'nullable|array',
        'category_ids.*' => 'exists:categories,id',
        'new_categories' => 'nullable|array',
        'new_categories.*' => 'string',
        'logo_url' => 'required|image',
    ]);

    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'role' => 'owner',
    ]);

    if ($request->hasFile('logo_url')) {
        $logoPath = $request->file('logo_url')->store('store_logos', 'public');
    } else {
        $logoPath = null; 
    }

    $store = Store::create([
        'store_name' => $request->store_name,
        'description' => $request->description,
        'owner_id' => $user->id,
        'logo_url' => $logoPath,
    ]);

    $categoryIds = $request->category_ids ?? [];

    if ($request->filled('new_categories')) {
        foreach ($request->new_categories as $catName) {
            $catName = trim($catName);
            if ($catName !== '') {
                $category = Category::firstOrCreate(['name' => $catName]);
                $categoryIds[] = $category->id;
            }
        }
    }

    $uniqueCategoryIds = array_unique($categoryIds);

    if (!empty($uniqueCategoryIds)) {
        $store->categories()->sync($uniqueCategoryIds);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Owner registered',
        'token' => $token,
        'user' => $user,
        'store' => $store,
    ]);
}

    


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
    
        
        $store = null;
        if ($user->role === 'owner') {
            $store = \App\Models\Store::where('owner_id', $user->id)->first();
        }
    
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'role' => $user->role,
            ],
            'store' => $store,
        ]);
    }
    

    public function profile()
    {
        return response()->json(['user' => Auth::user()]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}

