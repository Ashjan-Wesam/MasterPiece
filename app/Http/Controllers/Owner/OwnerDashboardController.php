<?php

namespace App\Http\Controllers\Owner;

use App\Models\Order;
use App\Models\Product;
use App\Models\StoreReview;
use App\Models\DesignRequest;
use App\Models\OrderDetail; 
use App\Models\Category; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OwnerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->user()->stores;
    
        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }
    
        $storeId = $store->id;
    
        $productsCount = Product::where('store_id', $storeId)->count();
    
        $completedOrdersCount = Order::where('store_id', $storeId)
                                    ->where('status', 'completed')
                                    ->count();
    
        $pendingOrdersCount = Order::where('store_id', $storeId)
                                  ->where('status', 'pending')
                                  ->count();
    
        // عدد المراجعات
        $reviewsCount = StoreReview::where('store_id', $storeId)->count();
    
        // عدد الكاتيجوريز المرتبطة بالمتجر
        $categoriesCount = $store->categories()->count();
    
        // عدد طلبات التصميم الخاصة بالـ owner
        $owner = $request->user();
        $designRequestsCount = DesignRequest::whereHas('product', function ($query) use ($storeId) {
            $query->where('store_id', $storeId);
        })->count();
        
        // حساب المنتجات المباعة
        $totalItemsSold = OrderDetail::whereHas('order', function ($query) use ($storeId) {
            $query->where('store_id', $storeId)->where('status', 'completed');
        })->sum('quantity');
        
        $totalRevenue = OrderDetail::whereHas('order', function ($query) use ($storeId) {
            $query->where('store_id', $storeId)->where('status', 'completed');
        })->sum('total_price');

        $topSellingProducts = OrderDetail::whereHas('order', function ($q) use ($storeId) {
            $q->where('store_id', $storeId)->where('status', 'completed');
        })
        ->selectRaw('product_id, SUM(quantity) as total_sold')
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->with('product')
        ->take(5)
        ->get()
        ->map(function ($item) {
            return [
                'name' => $item->product->name,
                'sold' => $item->total_sold,
            ];
        });
        
        $recentOrders = Order::where('store_id', $storeId)
            ->latest()
            ->take(5)
            ->get(['id', 'status', 'created_at']);
        
        $productsPerCategory = Category::withCount(['products' => function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            }])
            ->whereHas('products', function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->get(['name', 'products_count']);
    
        return response()->json([
            'products_count' => $productsCount,
            'completed_orders_count' => $completedOrdersCount,
            'pending_orders_count' => $pendingOrdersCount,
            'reviews_count' => $reviewsCount,
            'categories_count' => $categoriesCount,
            'design_requests_count' => $designRequestsCount,
            'sold_products_count' => $totalItemsSold, 
            'revenue' => $totalRevenue, 
            'top_selling_products' => $topSellingProducts,
            'recent_orders' => $recentOrders,
            'products_per_category' => $productsPerCategory,
        ]);
    }
}
