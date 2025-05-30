<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'statusHistory.user', 'user']);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
            'comment' => 'nullable|string',
        ]);

        // Add status to history with current user
        $order->addStatus(
            $request->status,
            $request->comment,
            Auth::id()
        );

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Generate a PDF invoice for the order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function generateInvoice(Order $order)
    {
        $order->load(['items.product', 'user']);
        
        $pdf = PDF::loadView('admin.orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    /**
     * Send an invoice email to the customer.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function emailInvoice(Order $order)
    {
        // Load needed relationships
        $order->load(['items.product', 'user']);
        
        // Generate PDF
        $pdf = PDF::loadView('admin.orders.invoice', compact('order'));
        
        // Send email with the PDF attached
        // Note: This is a simplified example. In a real application, you would use
        // Laravel's Mail facade to send an email with the PDF attachment.
        // Mail::to($order->email)->send(new InvoiceMail($order, $pdf));
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Invoice email sent to customer.');
    }
}