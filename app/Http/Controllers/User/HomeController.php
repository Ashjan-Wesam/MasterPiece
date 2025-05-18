<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Models\Order;
use App\Models\SiteReview; 
use App\Models\StoreReview; 
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestProducts = Product::orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $storesWithDiscount = Store::whereHas('discounts', function ($query) {
            $query->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now());
        })->get();

        $topStores = Store::whereHas('orders', function ($query) {
                $query->where('status', 'completed');  
            })
            ->withCount(['orders as completed_orders_count' => function ($query) {
                $query->where('status', 'completed');  
            }])
            ->orderByDesc('completed_orders_count')
            ->take(5)
            ->get();
        
        $siteReviews = SiteReview::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'latest_products' => $latestProducts,
            'stores_with_discount' => $storesWithDiscount,
            'top_stores' => $topStores,
            'site_reviews' => $siteReviews, 
        ]);
    }

    public function getStoreCategories($storeId)
{
    $store = Store::findOrFail($storeId);
    $categories = $store->categories()->get();

    return response()->json([
        'categories' => $categories
    ]);
}





}
