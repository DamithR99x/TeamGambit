<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $orders = Auth::user()->orders()->with('status')->latest()->paginate(10);
        
        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $order->load(['items.product', 'status']);
        
        return view('orders.show', compact('order'));
    }
} 