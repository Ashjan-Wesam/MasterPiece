<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register-customer', [AuthController::class, 'registerCustomer']);
Route::post('/register-owner', [AuthController::class, 'registerOwner']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/sendOTP', [ResetPasswordController::class , 'sendOTP']);
Route::post('/verify', [ResetPasswordController::class , 'verifyOtp']);
Route::post('/resetPassword', [ResetPasswordController::class , 'resetPassword']);

// Routes protected by auth
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Only owners
    Route::middleware('owner')->group(function () {
        Route::get('/owner/dashboard', fn () => response()->json(['message' => 'Welcome, Owner']));
    });

    // Only admins
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', fn () => response()->json(['message' => 'Welcome, Admin']));
    });
});

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->put('/profile', function (Request $request) {
    $user = $request->user();
    $user->update($request->only('name', 'email', 'phone'));
    return response()->json(['message' => 'Profile updated successfully']);
});




Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});


Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/stores', [StoreController::class, 'index']);
    Route::get('/stores/{id}', [StoreController::class, 'show']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::put('/stores/{id}', [StoreController::class, 'update']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);
});
