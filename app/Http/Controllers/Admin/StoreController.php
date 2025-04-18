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
        $stores = Store::with('owner')->get();
        return response()->json($stores);
    }

    public function show($id)
    {
        $store = Store::with('owner')->findOrFail($id);
        return response()->json($store);
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

        \Log::info('Request Data:', $request->all());


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
        $stores = Store::with('owner')->whereNull('deleted_at')->get();
        return response()->json($stores);
    }
}
