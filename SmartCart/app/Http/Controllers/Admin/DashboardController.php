<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Auth middleware is already applied at the route level
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Count total orders
        $totalOrders = Order::count();
        
        // Calculate total revenue
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        
        // Count total customers (users with role 'customer')
        $totalCustomers = User::where('role', 'customer')->count();
        
        // Count total products
        $totalProducts = Product::count();
        
        // Get recent orders
        $recentOrders = Order::latest()->take(10)->get();
        
        // Get top selling products
        $topProducts = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                return [
                    'name' => $product ? $product->name : 'Unknown Product',
                    'quantity' => $item->total_quantity,
                ];
            });
        
        // Get monthly sales data for the chart
        $monthlyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $sales = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', '!=', 'cancelled')
                ->sum('total');
            
            $monthlyData[] = [
                'month' => $month->format('M'),
                'sales' => $sales,
            ];
        }
        
        return view('admin.dashboard.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalCustomers',
            'totalProducts',
            'recentOrders',
            'topProducts',
            'monthlyData'
        ));
    }
}
