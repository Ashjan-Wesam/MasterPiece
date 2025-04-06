<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('stores')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required|string',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,customer,owner',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'profile_picture' => $request->profile_picture ?? null,
            'shipping_address' => $request->shipping_address ?? null,
        ]);

        if ($user->role === 'owner') {
            $request->validate([
                'store_name' => 'required|string',
                'description' => 'nullable|string',
                'logo_url' => 'nullable|string',
            ]);

            Store::create([
                'store_name' => $request->store_name,
                'description' => $request->description,
                'logo_url' => $request->logo_url,
                'owner_id' => $user->id,
            ]);
        }

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function show($id)
    {
        $user = User::with('stores')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'email' => "sometimes|required|email|unique:users,email,{$id}",
            'phone_number' => 'sometimes|required|string',
            'password' => 'nullable|min:6',
            'role' => 'sometimes|required|in:admin,customer,owner',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $user->update([
            'full_name' => $request->full_name ?? $user->full_name,
            'email' => $request->email ?? $user->email,
            'phone_number' => $request->phone_number ?? $user->phone_number,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role ?? $user->role,
            'status' => $request->status ?? $user->status,
            'profile_picture' => $request->profile_picture ?? $user->profile_picture,
            'shipping_address' => $request->shipping_address ?? $user->shipping_address,
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'owner') {
            $user->stores()->delete();
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
