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
        return response()->json(Store::all());
    }

    public function show($id)
    {
        $store = Store::findOrFail($id);
        return response()->json($store);
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'owner_full_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'owner_password' => 'required|min:6',
        ]);

        $owner = User::create([
            'full_name' => $request->owner_full_name,
            'email' => $request->owner_email,
            'password' => Hash::make($request->owner_password),
            'role' => 'owner',
            'status' => 'active', 
        ]);

        $store = Store::create([
            'store_name' => $request->store_name,
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'owner_id' => $owner->id,
        ]);

        return response()->json(['message' => 'Store and owner created successfully', 'store' => $store], 201);
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        $request->validate([
            'store_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
        ]);

        $store->update([
            'store_name' => $request->store_name ?? $store->store_name,
            'description' => $request->description ?? $store->description,
            'logo_url' => $request->logo_url ?? $store->logo_url,
        ]);

        return response()->json(['message' => 'Store updated successfully', 'store' => $store]);
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);

        $owner = $store->owner;
        $store->delete();

        if ($owner) {
            $owner->delete();
        }

        return response()->json(['message' => 'Store and associated owner deleted successfully']);
    }
}
