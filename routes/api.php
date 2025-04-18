<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\ProductController;

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


Route::get('/stores', [StoreController::class, 'publicIndex']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/owner/products', [ProductController::class, 'store']);
    Route::get('/owner/categories/{id}', [CategoryController::class, 'show']);
    
    Route::put('/owner/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/owner/categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/owner/products',  [ProductController::class, 'index']);
    Route::get('/owner/products/{id}', [ProductController::class, 'show']);
   
    Route::put('/owner/products/{id}', [ProductController::class, 'update']);
    Route::delete('/owner/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/owner/my-categories',  [CategoryController::class, 'myCategories']);


});
Route::post('/owner/categories', [CategoryController::class, 'store']);
Route::get('/owner/categories',  [CategoryController::class, 'index']);

Route::post('/owner/categories/attach', [CategoryController::class, 'attachToMyStore']); 

