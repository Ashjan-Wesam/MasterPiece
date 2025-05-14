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
    $messages = [
        'full_name.required' => 'Full name is required.',
        'full_name.string' => 'Full name must be a string.',
        'email.required' => 'Email is required.',
        'email.email' => 'Email format is invalid.',
        'email.unique' => 'This email is already taken.',
        'phone_number.required' => 'Phone number is required.',
        'phone_number.regex' => 'Phone number must start with 077, 078, or 079 and be exactly 10 digits.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 6 characters long.',
        'password.regex' => 'Password must start with an uppercase letter and include at least one special symbol.',
        'role.required' => 'Role is required.',
        'role.in' => 'Role must be either admin, owner, or customer.',
        'status.required' => 'Status is required.',
        'status.in' => 'Status must be either active or inactive.',
        'profile_picture.image' => 'Profile picture must be an image.',
        'profile_picture.mimes' => 'Profile picture must be a file of type: jpeg, png, jpg, gif.',
        'logo_url.image' => 'Logo must be an image.',
        'logo_url.mimes' => 'Logo must be a file of type: jpeg, png, jpg, gif.',
    ];

    $rules = [
        'full_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'phone_number' => ['required', 'regex:/^(077|078|079)[0-9]{7}$/'],
        'password' => [
            'required',
            'string',
            'min:6',
            'regex:/^[A-Z][A-Za-z0-9!@#$%^&*()_+=\-{}\[\]:;"\'<>,.?\/\\|`~]{5,}$/',
            'regex:/[!@#$%^&*()_+=\-{}\[\]:;"\'<>,.?\/\\|`~]/'
        ],
        'role' => ['required', 'in:admin,owner,customer'],
        'status' => ['required', 'in:active,inactive'],
        'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
    ];

    if ($request->role === 'owner') {
        $rules = array_merge($rules, [
            'store_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo_url' => ['required', 'image', 'mimes:jpeg,png,jpg,gif'],
        ]);
    }

    $request->validate($rules, $messages);

    $profilePicturePath = null;
    if ($request->hasFile('profile_picture')) {
        $profilePicturePath = uniqid() . '-' . $request->profile_picture->getClientOriginalName();
        $request->profile_picture->move(public_path('storage/profile'), $profilePicturePath);
    }

    $user = User::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status,
        'profile_picture' => $profilePicturePath,
        'shipping_address' => $request->shipping_address ?? null,
    ]);

    if ($user->role === 'owner') {
        $logoPath = null;

        if ($request->hasFile('logo_url')) {
            $logoPath = uniqid() . '-' . $request->logo_url->getClientOriginalName();
            $request->logo_url->move(public_path('storage/logo'), $logoPath);
        }

        Store::create([
            'store_name' => $request->store_name,
            'description' => $request->description,
            'logo_url' => $logoPath,
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
    
        $messages = [
            'full_name.required' => 'Full name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email format is invalid.',
            'email.unique' => 'This email is already taken.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'Phone number must start with 077, 078, or 079 and be exactly 10 digits.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password.regex' => 'Password must start with an uppercase letter and include at least one special symbol.',
            'role.required' => 'Role is required.',
            'role.in' => 'Role must be either admin, owner, or customer.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either active or inactive.',
            'profile_picture.image' => 'Profile picture must be an image.',
            'profile_picture.mimes' => 'Profile picture must be a file of type: jpeg, png, jpg, gif.',
            'logo_url.image' => 'Logo must be an image.',
            'logo_url.mimes' => 'Logo must be a file of type: jpeg, png, jpg, gif.',
        ];
    
        $rules = [
            'full_name' => 'sometimes|required|string|max:255',
            'email' => "sometimes|required|email|unique:users,email,{$id}",
            'phone_number' => 'sometimes|required|string|regex:/^(077|078|079)[0-9]{7}$/',
            'password' => [
                'nullable',
                'string',
                'min:6',
                'regex:/^[A-Z][A-Za-z0-9!@#$%^&*()_+=\-{}\[\]:;"\'<>,.?\/\\|`~]{5,}$/',
                'regex:/[!@#$%^&*()_+=\-{}\[\]:;"\'<>,.?\/\\|`~]/'
            ],
            'role' => 'sometimes|required|in:admin,customer,owner',
            'status' => 'sometimes|required|in:active,inactive',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'logo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ];
    
        $request->validate($rules, $messages);
    
        $profilePicturePath = $user->profile_picture; 
        if ($request->hasFile('profile_picture')) {
           
            if ($user->profile_picture && file_exists(public_path('storage/profile/' . $user->profile_picture))) {
                unlink(public_path('storage/profile/' . $user->profile_picture));
            }
           
            $profilePicturePath = uniqid() . '-' . $request->profile_picture->getClientOriginalName();
            $request->profile_picture->move(public_path('storage/profile'), $profilePicturePath);
        }
    
        $store = $user->role === 'owner' ? $user->store : null;
        $logoPath = $store?->logo_url; 
    
        if ($request->hasFile('logo_url') && $store) {
            
            if ($store->logo_url && file_exists(public_path('storage/logo/' . $store->logo_url))) {
                unlink(public_path('storage/logo/' . $store->logo_url));
            }
        
            $logoPath = uniqid() . '-' . $request->logo_url->getClientOriginalName();
            $request->logo_url->move(public_path('storage/logo'), $logoPath);
    
      
            $store->update([
                'logo_url' => $logoPath,
            ]);
        }
    
        $user->update([
            'full_name' => $request->full_name ?? $user->full_name,
            'email' => $request->email ?? $user->email,
            'phone_number' => $request->phone_number ?? $user->phone_number,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role ?? $user->role,
            'status' => $request->status ?? $user->status,
            'profile_picture' => $profilePicturePath,
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
