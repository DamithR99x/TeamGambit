@extends('layouts.frontend')

@section('title', 'Order #' . $order->order_number)

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->order_number }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Order #{{ $order->order_number }}</h1>
                <span class="badge bg-{{ getStatusColor($order->status->name) }} fs-5">
                    {{ $order->status->name }}
                </span>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Order Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td>{{ $order->payment_method }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order Status:</th>
                                    <td>
                                        <span class="badge bg-{{ getStatusColor($order->status->name) }}">
                                            {{ $order->status->name }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Shipping Information</h5>
                        </div>
                        <div class="card-body">
                            <address>
                                <strong>{{ $order->shipping_name }}</strong><br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
                                {{ $order->shipping_country }}<br>
                                <strong>Phone:</strong> {{ $order->shipping_phone }}<br>
                                <strong>Email:</strong> {{ $order->shipping_email }}
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="py-3 ps-4">Product</th>
                                    <th scope="col" class="py-3 text-center">Price</th>
                                    <th scope="col" class="py-3 text-center">Quantity</th>
                                    <th scope="col" class="py-3 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="py-3 ps-4">
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->primaryImage)
                                                    <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product_name }}" class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <img src="https://via.placeholder.com/60x60?text=No+Image" alt="{{ $item->product_name }}" class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                                <div class="ms-3">
                                                    @if($item->product)
                                                        <h6 class="mb-1"><a href="{{ route('shop.product', $item->product->slug) }}" class="text-decoration-none text-dark">{{ $item->product_name }}</a></h6>
                                                    @else
                                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                                    @endif
                                                    <p class="text-muted small mb-0">SKU: {{ $item->product_sku }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-center align-middle">${{ number_format($item->price, 2) }}</td>
                                        <td class="py-3 text-center align-middle">{{ $item->quantity }}</td>
                                        <td class="py-3 text-center align-middle">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end pe-3"><strong>Subtotal:</strong></td>
                                    <td class="text-center">${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end pe-3"><strong>Tax:</strong></td>
                                    <td class="text-center">${{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end pe-3"><strong>Shipping:</strong></td>
                                    <td class="text-center">${{ number_format($order->shipping, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end pe-3"><strong>Total:</strong></td>
                                    <td class="text-center"><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="text-center mb-4">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Back to My Orders
                </a>
                
                <!-- Only show reorder button if order is delivered -->
                @if($order->status->name === 'Delivered')
                    <button type="button" class="btn btn-primary ms-2" onclick="reorderItems({{ $order->id }})">
                        <i class="fas fa-redo me-2"></i> Reorder
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function reorderItems(orderId) {
        // Show loading indicator
        Swal.fire({
            title: 'Adding items to cart...',
            html: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Make AJAX request to reorder endpoint
        fetch(`/orders/${orderId}/reorder`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    showCancelButton: true,
                    confirmButtonText: 'Go to Cart',
                    cancelButtonText: 'Continue Shopping'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route("cart.index") }}';
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred. Please try again.'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred. Please try again.'
            });
            console.error('Reorder error:', error);
        });
    }
</script>
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