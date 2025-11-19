<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function shop(Request $request)
    {
        // Get products with category information
        $products = Product::with('category')
            ->where('status', 1)  // Assuming products table has status column
            ->paginate(12);

        return view('shop.shop', compact('products'));
    }

    public function categories(Request $request)
    {
        // Get active categories (no parent_id in schema, so just get top-level)
        $categories = Category::where('active', true)
            ->orderBy('sort', 'asc')
            ->get();

        return view('shop.categories', compact('categories'));
    }

    public function stores(Request $request)
    {
        // For now, return a view for stores page
        return view('shop.stores');
    }

    public function detail($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 1)
            ->with('category')
            ->firstOrFail();

        // Get related products (same category)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 1)
            ->limit(4)
            ->get();

        return view('shop.product-detail', compact('product', 'relatedProducts'));
    }

    public function checkout(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to continue with checkout.');
        }

        // Get cart items from session or database
        $cartItems = session()->get('cart', []);
        
        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('shop.checkout', compact('cartItems', 'total'));
    }

    public function payment(Request $request)
    {
        // Process payment - this would integrate with Stripe/PayPal
        // For now, return a response indicating payment processing
        return response()->json(['status' => 'success', 'message' => 'Payment processed successfully']);
    }

    public function addWishlist(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to add items to wishlist.');
        }

        $productId = $request->get('product_id');

        // Add product to wishlist
        $userId = Auth::id();
        
        // Check if product already in wishlist
        $existingWishlist = \DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$existingWishlist) {
            \DB::table('wishlists')->insert([
                'user_id' => $userId,
                'product_id' => $productId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['status' => 'success', 'message' => 'Product added to wishlist!']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Product is already in your wishlist!']);
        }
    }
}