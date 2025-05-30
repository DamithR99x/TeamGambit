@extends('layouts.frontend')

@section('title', 'Shopping Cart')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Shopping Cart</h1>
            
            @if(isset($cart) && $cart->items->count() > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Cart Items ({{ $cart->getTotalItems() }})</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="py-3 ps-4">Product</th>
                                                <th scope="col" class="py-3 text-center">Price</th>
                                                <th scope="col" class="py-3 text-center">Quantity</th>
                                                <th scope="col" class="py-3 text-center">Total</th>
                                                <th scope="col" class="py-3 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cart->items as $item)
                                                <tr>
                                                    <td class="py-3 ps-4">
                                                        <div class="d-flex align-items-center">
                                                            @if($item->product->primaryImage)
                                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" alt="{{ $item->product->name }}" class="img-fluid rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                                            @else
                                                                <img src="https://via.placeholder.com/70x70?text=No+Image" alt="{{ $item->product->name }}" class="img-fluid rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                                            @endif
                                                            <div class="ms-3">
                                                                <h6 class="mb-1"><a href="{{ route('shop.product', $item->product->slug) }}" class="text-decoration-none text-dark">{{ $item->product->name }}</a></h6>
                                                                <p class="text-muted small mb-0">SKU: {{ $item->product->sku }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 text-center align-middle">
                                                        @if($item->product->discount_price)
                                                            <del class="text-muted small">${{ number_format($item->product->price, 2) }}</del>
                                                            <div>${{ number_format($item->product->discount_price, 2) }}</div>
                                                        @else
                                                            <div>${{ number_format($item->product->price, 2) }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 text-center align-middle">
                                                        <form action="{{ route('cart.update') }}" method="POST" class="d-inline cart-update-form">
                                                            @csrf
                                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                                            <div class="input-group" style="width: 120px;">
                                                                <button type="button" class="btn btn-outline-secondary btn-quantity" data-action="decrease">-</button>
                                                                <input type="number" name="quantity" class="form-control text-center quantity-input" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}">
                                                                <button type="button" class="btn btn-outline-secondary btn-quantity" data-action="increase">+</button>
                                                            </div>
                                                        </form>
                                                    </td>
                                                    <td class="py-3 text-center align-middle">
                                                        ${{ number_format($item->getSubtotal(), 2) }}
                                                    </td>
                                                    <td class="py-3 text-center align-middle">
                                                        <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove this item?')">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                                </a>
                                <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?')">
                                        <i class="fas fa-trash-alt me-2"></i> Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Order Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($cart->getSubtotal(), 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping</span>
                                    <span>$0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Tax (7%)</span>
                                    <span>${{ number_format($cart->getSubtotal() * 0.07, 2) }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <strong>Total</strong>
                                    <strong>${{ number_format($cart->getSubtotal() + ($cart->getSubtotal() * 0.07), 2) }}</strong>
                                </div>
                                
                                <form action="{{ route('checkout.index') }}" method="GET">
                                    <div class="mb-3">
                                        <label for="coupon" class="form-label">Coupon Code</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="coupon" name="coupon" placeholder="Enter coupon code">
                                            <button class="btn btn-outline-secondary" type="button">Apply</button>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-lock me-2"></i> Proceed to Checkout
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h6 class="mb-3">We Accept</h6>
                                <div class="d-flex gap-3">
                                    <i class="fab fa-cc-visa fa-2x text-muted"></i>
                                    <i class="fab fa-cc-mastercard fa-2x text-muted"></i>
                                    <i class="fab fa-cc-amex fa-2x text-muted"></i>
                                    <i class="fab fa-cc-paypal fa-2x text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x mb-3 text-muted"></i>
                        <h3>Your cart is empty</h3>
                        <p class="mb-4">Looks like you haven't added any products to your cart yet.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity control
        document.querySelectorAll('.btn-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.dataset.action;
                const inputGroup = this.closest('.input-group');
                const quantityInput = inputGroup.querySelector('.quantity-input');
                const form = this.closest('.cart-update-form');
                let currentValue = parseInt(quantityInput.value);
                const max = parseInt(quantityInput.getAttribute('max'));
                
                if (action === 'increase' && currentValue < max) {
                    quantityInput.value = currentValue + 1;
                } else if (action === 'decrease' && currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
                
                // Auto-submit the form
                form.submit();
            });
        });
        
        // Prevent manually entering invalid quantities
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                let value = parseInt(this.value);
                const max = parseInt(this.getAttribute('max'));
                
                if (isNaN(value) || value < 1) {
                    this.value = 1;
                } else if (value > max) {
                    this.value = max;
                }
                
                // Auto-submit the form
                this.closest('form').submit();
            });
        });
    });
</script>
@endsection 