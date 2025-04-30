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
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Customer registered', 'token' => $token, 'user' => $user ]);
    }

    
    public function registerOwner(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|unique:users',
            'password' => 'required|min:6',
            'store_name' => 'required|string',
            'description' => 'nullable|string',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'new_categories' => 'nullable|string', // comma-separated string
        ]);
    
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 'owner',
        ]);
    
        $store = Store::create([
            'store_name' => $request->store_name,
            'description' => $request->description,
            'owner_id' => $user->id,
        ]);
    
        if ($request->has('category_ids')) {
            $store->categories()->attach($request->category_ids);
        }
    
        if ($request->filled('new_categories')) {
            $newCategories = explode(',', $request->new_categories);
            foreach ($newCategories as $catName) {
                $catName = trim($catName);
                if ($catName) {
                    $category = Category::firstOrCreate(['name' => $catName]);
                    $store->categories()->attach($category->id);
                }
            }
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

