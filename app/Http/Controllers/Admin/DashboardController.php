<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\SiteReview; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $orders = Order::count();
        $revenue = Order::sum('total_price');
        $customers = User::where('role', 'customer')->count();
        $owners = User::where('role', 'owner')->count(); 
        $reviews = SiteReview::count(); 

        $topProducts = Product::join('order_details', 'products.id', '=', 'order_details.product_id')
                       ->select('products.id', 'products.name')
                       ->selectRaw('SUM(order_details.quantity) as sales')
                       ->groupBy('products.id', 'products.name')
                       ->orderByDesc('sales')
                       ->limit(5)
                       ->get();

        $recentOrders = Order::latest()
            ->take(5)
            ->get(['id', 'total_price as amount']);

        $chartData = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                DB::raw("SUM(total_price) as sales")
            )
            ->groupBy('month')
            ->orderByRaw("STR_TO_DATE(month, '%b')")
            ->get();

        return response()->json([
            'products' => $products,
            'orders' => $orders,
            'revenue' => $revenue,
            'customers' => $customers,
            'owners' => $owners, 
            'reviews' => $reviews, 
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'chartData' => $chartData,
        ]);
    }
}
