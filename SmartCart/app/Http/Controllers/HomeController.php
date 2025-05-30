<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Homepage should be accessible to all users
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::where('featured', true)
            ->where('status', 'active')
            ->take(8)
            ->get();
        
        // Get new arrivals
        $newArrivals = Product::where('status', 'active')
            ->latest()
            ->take(4)
            ->get();
        
        // Get top categories
        $categories = Category::whereNull('parent_id')
            ->take(6)
            ->get();
        
        return view('home', compact('featuredProducts', 'newArrivals', 'categories'));
    }
}
