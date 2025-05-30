<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('status', 'active');

        // Apply category filter
        if ($request->has('categories')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

        // Apply price range filter
        if ($request->has('min_price') && $request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply stock filter
        if ($request->has('in_stock')) {
            $query->where('stock_quantity', '>', 0);
        }

        // Apply sale filter
        if ($request->has('on_sale')) {
            $query->whereNotNull('discount_price');
        }

        // Apply featured filter
        if ($request->has('featured')) {
            $query->where('featured', true);
        }

        // Apply search query
        if ($request->has('query') && !empty($request->query)) {
            $searchTerm = $request->query;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'popularity':
                    $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            // Default sorting
            $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12);

        return view('shop.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        
        // Get related products from the same categories
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('status', 'active')
            ->whereHas('categories', function($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->take(4)
            ->get();
        
        return view('shop.product', compact('product', 'relatedProducts'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $query = Product::where('status', 'active')
            ->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category->id)
                  ->orWhere('categories.parent_id', $category->id);
            });
        
        $products = $query->paginate(12);
        
        return view('shop.category', compact('category', 'products'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $products = Product::where('status', 'active')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->paginate(12);
        
        return view('shop.search', compact('products', 'query'));
    }
} 