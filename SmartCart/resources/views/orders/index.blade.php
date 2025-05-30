@extends('layouts.frontend')

@section('title', 'My Orders')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Orders</h1>
            
            @if($orders->count() > 0)
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="py-3 ps-4">Order #</th>
                                        <th scope="col" class="py-3">Date</th>
                                        <th scope="col" class="py-3">Total</th>
                                        <th scope="col" class="py-3">Status</th>
                                        <th scope="col" class="py-3 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="py-3 ps-4">
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none">
                                                    #{{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td class="py-3">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="py-3">${{ number_format($order->total, 2) }}</td>
                                            <td class="py-3">
                                                <span class="badge bg-{{ getStatusColor($order->status->name) }}">
                                                    {{ $order->status->name }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-center">
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-bag fa-4x mb-3 text-muted"></i>
                        <h3>No orders yet</h3>
                        <p class="mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@php
function getStatusColor($status) {
    $colors = [
        'Pending' => 'warning',
        'Processing' => 'info',
        'Shipped' => 'primary',
        'Delivered' => 'success',
        'Cancelled' => 'danger',
        'Refunded' => 'secondary',
    ];
    
    return $colors[$status] ?? 'secondary';
}
@endphp 