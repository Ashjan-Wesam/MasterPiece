<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ProductController extends Controller
{
    
    public function index()
{
    $store = Auth::user()->stores;

    $products = Product::where('store_id', $store->id)
                        ->with('category') 
                        ->get();

    return response()->json($products);
}

   public function show($id)
{
    $product = Product::with('category')->findOrFail($id);
    return response()->json($product);
}


     public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'required|numeric',
            'stock_quantity'  => 'required|integer',
            'category_id'     => 'required|exists:categories,id',
            'image_url'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'request' => 'nullable|in:yes,no',    
        ]);


        if ($request->hasFile('image_url')) {
            $image_path = uniqid() . '-' . $request->name . '.' . $request->image_url->extension();
            $request->image_url->move(public_path('storage/products'), $image_path);
            $data['image_url'] = 'storage/products/' . $image_path; 

        }

        $store= Auth::user()->stores;
        $data['store_id'] = $store->id;

        // $data['request']  = $request->filled('is_custom_request') ? 'yes' : 'no';

        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        if ($product->store_id != Auth::user()->stores->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $data = $request->validate([
            'name'            => 'sometimes|required|string|max:255',
            'description'     => 'nullable|string',
            'price'           => 'sometimes|required|numeric',
            'stock_quantity'  => 'sometimes|required|integer',
            'category_id'     => 'sometimes|required|exists:categories,id',
            'image_url'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'request'         => 'nullable|in:yes,no'
        ]);
    
        if ($request->hasFile('image_url')) {
            $image_path = uniqid() . '-' . $request->name . '.' . $request->image_url->extension();
            $request->image_url->move(public_path('storage/products'), $image_path);
            $data['image_url'] = 'storage/products/' . $image_path;
        }
    
        $data['request'] = $request->input('request', 'no');
    
        $product->update($data);
    
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }
    

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->store_id != Auth::user()->stores->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

   
    
    public function applyDiscounts()
{
    $stores = Auth::user()->stores;

    foreach ($stores as $store) {
        $storeId = $store->id;

        // الحصول على الخصم الفعال
        $discount = Discount::where('store_id', $storeId)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

        if ($discount) {
            // يمكن هنا أن تعود بيانات الخصم مع المنتجات
            $products = Product::where('store_id', $storeId)->get();

            // إرسال المنتجات مع معلومات الخصم
            $store->products = $products->map(function ($product) use ($discount) {
                $product->discount_percentage = $discount->discount_percentage;
                return $product;
            });
        }
    }

    return response()->json($stores);
}

  
    public function checkDiscountStatus(Request $request)
    {
        $store = $request->user()->stores()->first();

        // تحقق إذا كان هناك خصم فعال
        $activeDiscount = Discount::where('store_id', $store->id)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->first();

        // إرجاع الحالة للمستخدم
        if ($activeDiscount) {
            return response()->json([
                'discount_active' => true,
                'discount_percentage' => $activeDiscount->discount_percentage,
            ]);
        }

        return response()->json([
            'discount_active' => false,
        ]);
    }
}

