@extends('layouts.frontend')

@section('title', $product->name)

@section('styles')
<style>
    .product-gallery img {
        width: 100%;
        height: 400px;
        object-fit: contain;
    }
    
    .product-thumbnails {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .product-thumbnails img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid #dee2e6;
        padding: 3px;
        border-radius: 0.25rem;
    }
    
    .product-thumbnails img.active {
        border-color: #007bff;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
    }
    
    .quantity-control button {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .quantity-control input {
        width: 50px;
        text-align: center;
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
</style>
@endsection

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
            @if($product->categories->isNotEmpty())
                <li class="breadcrumb-item">
                    <a href="{{ route('shop.category', $product->categories->first()->slug) }}">
                        {{ $product->categories->first()->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="row mb-5">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div class="product-gallery">
                @if($product->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" id="main-product-image" class="img-fluid rounded" alt="{{ $product->name }}">
                    
                    @if($product->images->count() > 1)
                        <div class="product-thumbnails">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="thumbnail {{ $image->is_primary ? 'active' : '' }}"
                                     onclick="changeImage('{{ asset('storage/' . $image->image_path) }}', this)">
                            @endforeach
                        </div>
                    @endif
                @else
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded" alt="{{ $product->name }}">
                @endif
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                <span class="badge bg-{{ $product->inStock() ? 'success' : 'danger' }}">
                    {{ $product->inStock() ? 'In Stock' : 'Out of Stock' }}
                </span>
                
                @foreach($product->categories as $category)
                    <a href="{{ route('shop.category', $category->slug) }}" class="badge bg-light text-dark text-decoration-none">{{ $category->name }}</a>
                @endforeach
                
                @if($product->featured)
                    <span class="badge bg-primary">Featured</span>
                @endif
            </div>
            
            <div class="mb-3">
                @if($product->discount_price)
                    <span class="text-muted text-decoration-line-through fs-5">${{ number_format($product->price, 2) }}</span>
                    <span class="text-danger fs-3 ms-2">${{ number_format($product->discount_price, 2) }}</span>
                    <span class="badge bg-danger ms-2">
                        {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF
                    </span>
                @else
                    <span class="fs-3">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            
            <div class="mb-4">
                <p>{{ $product->description }}</p>
            </div>
            
            <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <div class="quantity-control">
                        <button type="button" class="btn-quantity" data-action="decrease">-</button>
                        <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock_quantity }}" value="1" class="form-control">
                        <button type="button" class="btn-quantity" data-action="increase">+</button>
                    </div>
                    <small class="text-muted">Available: {{ $product->stock_quantity }}</small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" {{ !$product->inStock() ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                    </button>
                </div>
            </form>
            
            @auth
            <form action="{{ route('favorites.toggle') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-heart me-2"></i> 
                    {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'Remove from Favorites' : 'Add to Favorites' }}
                </button>
            </form>
            @endauth
            
            <div class="mb-3">
                <strong>SKU:</strong> {{ $product->sku }}<br>
                <strong>Categories:</strong>
                @foreach($product->categories as $category)
                    <a href="{{ route('shop.category', $category->slug) }}" class="text-decoration-none">{{ $category->name }}</a>{{ !$loop->last ? ', ' : '' }}
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Product Description Tabs -->
    <div class="card mb-5">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="product-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="false">Details</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="product-tabs-content">
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <p>{{ $product->description }}</p>
                </div>
                <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">SKU</th>
                                <td>{{ $product->sku }}</td>
                            </tr>
                            <tr>
                                <th>Categories</th>
                                <td>
                                    @foreach($product->categories as $category)
                                        {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Stock</th>
                                <td>{{ $product->stock_quantity }} units</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                    <h5>Shipping Information</h5>
                    <p>We offer free shipping on orders over $50. Standard shipping takes 3-5 business days.</p>
                    
                    <h5>Return Policy</h5>
                    <p>If you're not satisfied with your purchase, you can return it within 30 days for a full refund. Items must be unused and in the original packaging.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <section class="mb-5">
        <h3 class="mb-4">You May Also Like</h3>
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-card h-100">
                        @if($relatedProduct->discount_price)
                            <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                        @endif
                        
                        @if($relatedProduct->primaryImage)
                            <img src="{{ asset('storage/' . $relatedProduct->primaryImage->image_path) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $relatedProduct->name }}">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            <div class="mb-2">
                                @if($relatedProduct->discount_price)
                                    <span class="text-muted text-decoration-line-through">${{ number_format($relatedProduct->price, 2) }}</span>
                                    <span class="text-danger ms-2">${{ number_format($relatedProduct->discount_price, 2) }}</span>
                                @else
                                    <span>${{ number_format($relatedProduct->price, 2) }}</span>
                                @endif
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('shop.product', $relatedProduct->slug) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-cart" {{ !$relatedProduct->inStock() ? 'disabled' : '' }}>
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity control
        const quantityInput = document.getElementById('quantity');
        const maxQuantity = {{ $product->stock_quantity }};
        
        document.querySelectorAll('.btn-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.dataset.action;
                let currentValue = parseInt(quantityInput.value);
                
                if (action === 'increase' && currentValue < maxQuantity) {
                    quantityInput.value = currentValue + 1;
                } else if (action === 'decrease' && currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
        });
        
        // Prevent manually entering invalid quantities
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            } else if (value > maxQuantity) {
                this.value = maxQuantity;
            }
        });
    });
    
    // Image gallery
    function changeImage(src, thumbnail) {
        document.getElementById('main-product-image').src = src;
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
</script>
@endsection 