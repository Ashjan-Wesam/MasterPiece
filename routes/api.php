<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController; 

// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\AdminDiscountController;


// Owner Controllers
use App\Http\Controllers\Owner\CategoryController;
use App\Http\Controllers\Owner\DiscountController;
use App\Http\Controllers\Owner\ProductController;
use App\Http\Controllers\Owner\OwnerOrderController;
use App\Http\Controllers\Owner\OwnerSiteReviewController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\StoreAndProductReviews;
use App\Http\Controllers\Owner\StoreEditController;

// User Controllers
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\DesignRequestController;
use App\Http\Controllers\User\UserReviewController;
use App\Http\Controllers\User\UserProductController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\UserProfileController;



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

Route::middleware('auth:sanctum')->get('/profile', [UserProfileController::class, 'show']);
Route::middleware('auth:sanctum')->post('/profile/update', [UserProfileController::class, 'update']);
Route::middleware('auth:sanctum')->post('/profile/change-password', [UserProfileController::class, 'changePassword']);
Route::middleware('auth:sanctum')->post('/profile/verify-password', [UserProfileController::class, 'verifyCurrentPassword']);

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
    
   Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::get('/categories/{id}', [AdminCategoryController::class, 'show']);
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy']);

    //Products Routes
     Route::apiResource('products', ProductAdminController::class);

    // Discount Routes
    Route::apiResource('discounts', AdminDiscountController::class); 

    // Reviews Routes
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::get('/reviews/product', [ReviewController::class, 'showProductReviews']);
    Route::get('/reviews/store', [ReviewController::class, 'showStoreReviews']);
    Route::get('/reviews/site', [ReviewController::class, 'showSiteReviews']);
    Route::delete('/reviews/{type}/{id}', [ReviewController::class, 'destroy']);
});

Route::get('/store-categories/{storeId}', [HomeController::class, 'getStoreCategories']);



// Owner Routes

 // General Routes
    Route::get('/owner/dashboard-stats', [DashboardController::class, 'index']);
    Route::get('/all-categories',  [CategoryController::class, 'index']);
    Route::get('/stores', [StoreController::class, 'publicIndex']);
    Route::get('/single-product/{productId}',  [UserProductController::class, 'show']);
    Route::get('/stores/{id}', [StoreController::class, 'show']);

 // Owner Protected Routes
 Route::middleware('auth:sanctum')->prefix('owner')->group(function () {
    

    // Dashboard Route
       Route::get('/dashboard/{storeId}', [OWnerDashboardController::class, 'index']);

    // Store Edit Routes
       Route::get('/stores-info', [StoreEditController::class, 'show']);
       Route::post('/stores/{id}/update', [StoreEditController::class, 'update']);

    // Categories Management Routes
       Route::get('/categories/{id}', [CategoryController::class, 'show']);
       Route::put('/categories/{id}', [CategoryController::class, 'update']);
       Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
       Route::get('/my-categories',  [CategoryController::class, 'myCategories']);

    // Products Management Routes
        Route::get('/products',  [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Orders Management Routes
       Route::get('/orders', [OwnerOrderController::class, 'index']);
       Route::post('/design-requests/{id}/update', [OwnerOrderController::class, 'updateDesignStatus']);
       Route::post('/orders/{id}/complete', [OwnerOrderController::class, 'markOrderCompleted']);
       Route::get('/orders-with-designs', [OwnerOrderController::class, 'designRequestsOnly']);

    //Discounts Management Routes
      Route::get('/discounts',  [DiscountController::class, 'index']);
      Route::get('/discounts/{id}', [DiscountController::class, 'show']);
      Route::post('/discounts', [DiscountController::class, 'store']);
      Route::put('/discounts/{id}', [DiscountController::class, 'update']);
      Route::delete('/discounts/{id}', [DiscountController::class, 'destroy']);

    //Reviews Management Routes
      Route::post('/site-reviews', [OwnerSiteReviewController::class, 'store']);
      Route::get('/site-reviews', [OwnerSiteReviewController::class, 'index']);
      Route::get('/store-reviews', [StoreAndProductReviews::class, 'getStoreReviews']);
      Route::get('/product-reviews', [StoreAndProductReviews::class, 'getProductReviews']);

});



































 Route::get('/cart/check-discount/{storeId}', [CartController::class, 'checkStoreDiscount']);




 Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/cart/check-store/{productId}', [CartController::class, 'checkStoreMatch']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity']);
   
    

    


    


});

Route::get('/categories', [AdminCategoryController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);




























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



Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
    Route::post('/orders', [AdminOrderController::class, 'store']);
    Route::put('/orders/{id}', [AdminOrderController::class, 'update']);
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->get('/my-design-requests', [DesignRequestController::class, 'userRequests']);
Route::middleware('auth:sanctum')->post('/design-requests', [DesignRequestController::class, 'store']);
Route::put('/design-requests/{designRequest}/status', [DesignRequestController::class, 'updateStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reviews', [UserReviewController::class, 'store']);
    Route::get('/can-review/{productId}', [UserReviewController::class, 'canReview']);
    
    Route::get('/', [\App\Http\Controllers\User\UserProfileController::class, 'getProfile']);
    Route::post('/update', [\App\Http\Controllers\User\UserProfileController::class, 'updateProfile']);
});

Route::get('/reviews/{productId}', [UserReviewController::class, 'index']);
Route::get('/related-products/{categoryId}/{storeId}/{productId}', [UserProductController::class, 'related']);


use App\Http\Controllers\User\WishlistController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index']);          
    Route::post('/wishlist', [WishlistController::class, 'store']);         
    Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy']); 
});






Route::get('/stores/{storeId}/reviews', [UserReviewController::class, 'storeReviews']);
Route::get('/stores/{storeId}/stat', [UserReviewController::class, 'stats']);

Route::get('/stores/{storeId}/can-review', [UserReviewController::class, 'canReviewStore'])->middleware('auth:sanctum');

Route::post('/stores/reviews', [UserReviewController::class, 'submitStoreReview'])->middleware('auth:sanctum');
