<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - SmartCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">{{ Auth::user()->name }}'s Shopping Cart</h1>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if($cartItems->isEmpty())
                    <div class="alert alert-info">
                        Your cart is empty. <a href="{{ route('products.index') }}">Continue shopping</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(isset($item->attributes['image']))
                                                    <img src="{{ $item->attributes['image'] }}" alt="{{ $item->name }}" class="img-thumbnail me-3" style="max-width: 80px;">
                                                @endif
                                                <div>
                                                    <h5 class="mb-0">{{ $item->name }}</h5>
                                                    @if(isset($item->attributes['slug']))
                                                        <small class="text-muted">
                                                            <a href="{{ route('products.show', $item->attributes['slug']) }}">View product</a>
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>
                                            <form action="{{ route('user.cart.update') }}" method="POST" class="d-flex align-items-center">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width: 70px;">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">Update</button>
                                            </form>
                                        </td>
                                        <td>${{ number_format($item->getPriceSum(), 2) }}</td>
                                        <td>
                                            <a href="{{ route('user.cart.remove', $item->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this item?')">Remove</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @php
                                    $userId = Auth::id();
                                @endphp
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Cart Subtotal:</td>
                                    <td>${{ number_format(Cart::session($userId)->getSubTotal(), 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tax:</td>
                                    <td>${{ number_format(Cart::session($userId)->getTotal() - Cart::session($userId)->getSubTotal(), 2) }}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold">${{ number_format(Cart::session($userId)->getTotal(), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Continue Shopping</a>
                        <div>
                            <a href="{{ route('user.cart.clear') }}" class="btn btn-outline-danger me-2" onclick="return confirm('Are you sure you want to empty your cart?')">Empty Cart</a>
                            <a href="{{ route('user.checkout.index') }}" class="btn btn-success">Proceed to Checkout</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 