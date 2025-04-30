<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\Controller;
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\DiscountController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\DesignRequestController;
use App\Http\Controllers\User\UserReviewController;
use App\Http\Controllers\User\UserProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Owner\OwnerOrderController;
use App\Http\Controllers\Owner\OwnerSiteReviewController;
use App\Http\Controllers\User\HomeController;



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


// Admin Routes


Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {

    // User Routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    // Store Routes
    Route::get('/stores', [StoreController::class, 'index']);
    Route::post('/stores', [StoreController::class, 'store']);
    
    Route::put('/stores/{id}', [StoreController::class, 'update']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);

    // Category Routes
    
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{id}', [StoreController::class, 'show']);
    Route::put('/stores/{id}', [StoreController::class, 'update']);
    Route::delete('/stores/{id}', [StoreController::class, 'destroy']);
});
Route::get('/stores/{id}', [StoreController::class, 'show']);
Route::get('/store-categories/{storeId}', [HomeController::class, 'getStoreCategories']);

// Owner Routes
 Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/cart/check-store/{productId}', [CartController::class, 'checkStoreMatch']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity']);
    
    
    Route::get('/owner/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/owner/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/owner/categories/{id}', [CategoryController::class, 'destroy']);
    Route::get('/owner/my-categories',  [CategoryController::class, 'myCategories']);

    Route::get('/owner/products',  [ProductController::class, 'index']);
    Route::get('/owner/products/{id}', [ProductController::class, 'show']);
    Route::post('/owner/products', [ProductController::class, 'store']);
    Route::put('/owner/products/{id}', [ProductController::class, 'update']);
    Route::delete('/owner/products/{id}', [ProductController::class, 'destroy']);
    Route::post('/owner/products/apply-discounts', [ProductController::class, 'applyDiscounts']);
    Route::get('/owner/discount-status', [ProductController::class, 'checkDiscountStatus']);


    Route::get('/owner/discounts',  [DiscountController::class, 'index']);
    Route::get('/owner/discounts/{id}', [DiscountController::class, 'show']);
    Route::post('/owner/discounts', [DiscountController::class, 'store']);
    Route::put('/owner/discounts/{id}', [DiscountController::class, 'update']);
    Route::delete('/owner/discounts/{id}', [DiscountController::class, 'destroy']);

    Route::post('/owner/site-reviews', [OwnerSiteReviewController::class, 'store']);
    Route::get('/owner/site-reviews', [OwnerSiteReviewController::class, 'index']);


    


});

Route::get('/categories', [AdminCategoryController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
























Route::get('/stores', [StoreController::class, 'publicIndex']);


Route::post('/owner/categories', [CategoryController::class, 'store']);
Route::get('/owner/categories',  [CategoryController::class, 'index']); 
Route::get('/owner/products/{id}', [ProductController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::delete('/cart/remove/{productId}', [CartController::class, 'removeFromCart']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    Route::get('/cart/count', [CartController::class, 'getCartCount']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);

});


Route::get('/owner/dashboard-stats', [DashboardController::class, 'index']);


Route::get('/user/products', [ProductController::class, 'index']);

use App\Http\Controllers\Admin\AdminOrderController;

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
    Route::post('/orders', [AdminOrderController::class, 'store']);
    Route::put('/orders/{id}', [AdminOrderController::class, 'update']);
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy']);

    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::get('/reviews/product', [ReviewController::class, 'showProductReviews']);
    Route::get('/reviews/store', [ReviewController::class, 'showStoreReviews']);
    Route::get('/reviews/site', [ReviewController::class, 'showSiteReviews']);
    Route::delete('/reviews/{type}/{id}', [ReviewController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/my-design-requests', [DesignRequestController::class, 'userRequests']);
Route::middleware('auth:sanctum')->post('/design-requests', [DesignRequestController::class, 'store']);
Route::put('/design-requests/{designRequest}/status', [DesignRequestController::class, 'updateStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [UserReviewController::class, 'store']);
    Route::get('/can-review/{productId}', [UserReviewController::class, 'canReview']);
    Route::get('/related-products/{categoryId}/{storeId}', [UserProductController::class, 'related']);
    Route::get('/', [\App\Http\Controllers\User\UserProfileController::class, 'getProfile']);
    Route::post('/update', [\App\Http\Controllers\User\UserProfileController::class, 'updateProfile']);
});

Route::get('/reviews/{productId}', [UserReviewController::class, 'index']);
Route::get('/related-products/{categoryId}/{storeId}', [UserProductController::class, 'related']);










// Owner Routes
use App\Http\Controllers\Owner\OwnerDashboardController;
Route::middleware('auth:sanctum')->prefix('owner')->group(function () {

    // Orders Management Routes

    Route::get('/orders', [OwnerOrderController::class, 'index']);
    Route::post('/design-requests/{id}/update', [OwnerOrderController::class, 'updateDesignStatus']);
    Route::post('/orders/{id}/complete', [OwnerOrderController::class, 'markOrderCompleted']);
    Route::get('/orders-with-designs', [OwnerOrderController::class, 'designRequestsOnly']);
    
     Route::get('/dashboard/{storeId}', [OWnerDashboardController::class, 'index']);

});


