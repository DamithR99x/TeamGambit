<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the sales report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function sales(Request $request)
    {
        // Get date range from request or use default (last 30 days)
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $category = $request->input('category');
        $customer = $request->input('customer');

        // Base query
        $query = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        // Filter by customer if provided
        if ($customer) {
            $query->where('user_id', $customer);
        }

        // Filter by category if provided (more complex, requires joining tables)
        if ($category) {
            $query->whereHas('items.product.categories', function ($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }

        // Get summary data
        $totalSales = $query->sum('total');
        $orderCount = $query->count();
        $averageOrderValue = $orderCount > 0 ? $totalSales / $orderCount : 0;

        // Get sales data for chart
        $salesChart = clone $query;
        $salesByDay = $salesChart->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get orders for the table
        $orders = $query->with('user')
            ->latest()
            ->paginate(10);

        // Get available filter options
        $customers = User::where('role', 'customer')->get();
        $categories = \App\Models\Category::all();

        return view('admin.reports.sales', compact(
            'orders',
            'totalSales',
            'orderCount',
            'averageOrderValue',
            'salesByDay',
            'startDate',
            'endDate',
            'category',
            'customer',
            'customers',
            'categories'
        ));
    }

    /**
     * Display the inventory report.
     *
     * @return \Illuminate\View\View
     */
    public function inventory()
    {
        // Get inventory status data
        $lowStock = Product::where('stock_quantity', '<', 10)
            ->where('stock_quantity', '>', 0)
            ->with('categories')
            ->orderBy('stock_quantity')
            ->get();

        $outOfStock = Product::where('stock_quantity', 0)
            ->with('categories')
            ->latest()
            ->get();

        $allProducts = Product::with('categories')
            ->orderBy('name')
            ->get();

        // Group products by stock status for chart
        $stockSummary = [
            'In Stock (>10)' => Product::where('stock_quantity', '>', 10)->count(),
            'Low Stock (1-10)' => $lowStock->count(),
            'Out of Stock' => $outOfStock->count()
        ];

        return view('admin.reports.inventory', compact(
            'lowStock',
            'outOfStock',
            'allProducts',
            'stockSummary'
        ));
    }

    /**
     * Display the customer report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function customers(Request $request)
    {
        // Get date range from request or use default (last 365 days)
        $startDate = $request->input('start_date', now()->subYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // New customers over time
        $newCustomers = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers by order value
        $topCustomers = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->having('orders_count', '>', 0)
            ->orderByDesc('orders_sum_total')
            ->take(10)
            ->get();

        // Customer statistics
        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomersCount = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
        $customerWithOrders = User::where('role', 'customer')
            ->whereHas('orders')
            ->count();
        $customerWithoutOrders = $totalCustomers - $customerWithOrders;

        return view('admin.reports.customers', compact(
            'newCustomers',
            'topCustomers',
            'totalCustomers',
            'newCustomersCount',
            'customerWithOrders',
            'customerWithoutOrders',
            'startDate',
            'endDate'
        ));
    }
} 