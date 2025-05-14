<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    // public function getProfile()
    // {
    //     $user = Auth::user();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Profile fetched successfully',
    //         'data' => $user,
    //     ]);
    // }
    // public function updateProfile(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'full_name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'phone_number' => 'nullable|string|max:20',
    //         'shipping_address' => 'nullable|string|max:500',
    //         'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     $user->full_name = $request->full_name;
    //     $user->email = $request->email;
    //     $user->phone_number = $request->phone_number;
    //     $user->shipping_address = $request->shipping_address;

    //     if ($request->hasFile('profile_picture')) {
    //         if ($user->profile_picture) {
    //             Storage::disk('public')->delete($user->profile_picture);
    //         }
    //         $path = $request->file('profile_picture')->store('profile_pictures', 'public');
    //         $user->profile_picture = $path;
    //     }

    //     $user->save();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Profile updated successfully',
    //         'data' => $user,
    //     ]);
    // }

    public function show(Request $request)
    {
        return response()->json($request->user());
    }

   public function update(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
       'phone_number' => [
    'required',
    'string',
    'regex:/^(078|077|079)[0-9]{7}$/',
    'max:10'
],

        'shipping_address' => 'required|string|max:500',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user->full_name = $request->full_name;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;
    $user->shipping_address = $request->shipping_address;

    if ($request->hasFile('profile_picture')) {

        if ($user->profile_picture && file_exists(public_path('storage/profile/' . $user->profile_picture))) {
            unlink(public_path('storage/profile/' . $user->profile_picture));
        }

        $fileName = uniqid() . '-' . $request->file('profile_picture')->getClientOriginalName();

        $request->file('profile_picture')->move(public_path('storage/profile'), $fileName);

        $user->profile_picture = $fileName;
    }

    $user->save();

    return response()->json([
        'message' => 'Profile updated successfully.',
        'user' => $user,
    ]);
}

public function changePassword(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'current_password' => 'required',
       'new_password' => [
    'required',
    'min:8',
    'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]).+$/'
],

    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'The current password is incorrect.',
        ], 403);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json([
        'message' => 'Password changed successfully.',
    ]);
}

public function verifyCurrentPassword(Request $request)
{
    $user = $request->user();

    $validator = Validator::make($request->all(), [
        'current_password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $validator->errors(),
        ], 422);
    }

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'The current password is incorrect.',
        ], 403);
    }

    return response()->json([
        'message' => 'The current password is correct.',
        'success' => true,
    ]);
}

}