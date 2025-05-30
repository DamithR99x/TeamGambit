@extends('layouts.frontend')

@section('title', 'Home')

@section('content')
    <!-- Hero Carousel -->
    <div id="heroCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner rounded shadow">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1200x400/007bff/ffffff?text=Latest+Electronics" class="d-block w-100" alt="Latest Electronics">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Latest Electronics</h2>
                    <p>Discover the newest gadgets and technologies</p>
                    <a href="{{ route('shop.category', 'electronics') }}" class="btn btn-light">Shop Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1200x400/dc3545/ffffff?text=Fashion+Collection" class="d-block w-100" alt="Fashion Collection">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Fashion Collection</h2>
                    <p>Trendy outfits for every season</p>
                    <a href="{{ route('shop.category', 'clothing') }}" class="btn btn-light">Shop Now</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/1200x400/28a745/ffffff?text=Home+and+Kitchen" class="d-block w-100" alt="Home and Kitchen">
                <div class="carousel-caption d-none d-md-block">
                    <h2>Home & Kitchen</h2>
                    <p>Everything you need for your home</p>
                    <a href="{{ route('shop.category', 'home-kitchen') }}" class="btn btn-light">Shop Now</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Featured Categories -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0">Shop by Category</h2>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row">
            @foreach(\App\Models\Category::whereNull('parent_id')->take(6)->get() as $category)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-{{ getCategoryIcon($category->name) }} fa-4x mb-3 text-primary"></i>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text">{{ $category->description }}</p>
                            <a href="{{ route('shop.category', $category->slug) }}" class="btn btn-sm btn-outline-primary">Browse Products</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0">Featured Products</h2>
            <a href="{{ route('shop.index', ['featured' => 1]) }}" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row">
            @foreach(\App\Models\Product::where('featured', true)->where('status', 'active')->take(8)->get() as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100">
                        @if($product->discount_price)
                            <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                        @endif
                        
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <div class="mb-2">
                                @if($product->discount_price)
                                    <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                    <span class="text-danger ms-2">${{ number_format($product->discount_price, 2) }}</span>
                                @else
                                    <span>${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('shop.product', $product->slug) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-cart">
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

    <!-- New Arrivals -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="m-0">New Arrivals</h2>
            <a href="{{ route('shop.index', ['sort' => 'newest']) }}" class="btn btn-outline-primary">View All</a>
        </div>
        <div class="row">
            @foreach(\App\Models\Product::where('status', 'active')->latest()->take(4)->get() as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card product-card h-100">
                        @if($product->discount_price)
                            <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                        @endif
                        
                        @if($product->primaryImage)
                            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <div class="mb-2">
                                @if($product->discount_price)
                                    <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                    <span class="text-danger ms-2">${{ number_format($product->discount_price, 2) }}</span>
                                @else
                                    <span>${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('shop.product', $product->slug) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-cart">
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

    <!-- Features -->
    <section class="mb-5">
        <div class="row text-center">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-truck-fast fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Fast Delivery</h5>
                        <p class="card-text text-muted">Free shipping on orders over $50</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-undo-alt fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Easy Returns</h5>
                        <p class="card-text text-muted">30-day return policy</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">Secure Payment</h5>
                        <p class="card-text text-muted">100% secure payment</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-headset fa-3x mb-3 text-primary"></i>
                        <h5 class="card-title">24/7 Support</h5>
                        <p class="card-text text-muted">Dedicated support team</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Initialize carousel
    document.addEventListener('DOMContentLoaded', function() {
        var myCarousel = new bootstrap.Carousel(document.getElementById('heroCarousel'), {
            interval: 5000,
            wrap: true
        });
    });
</script>
@endsection

@php
function getCategoryIcon($categoryName) {
    $icons = [
        'electronics' => 'laptop',
        'clothing' => 'tshirt',
        'men' => 'user-tie',
        'women' => 'female',
        'kids' => 'child',
        'home & kitchen' => 'home',
        'appliances' => 'blender',
        'kitchenware' => 'utensils',
        'furniture' => 'couch',
        'accessories' => 'headphones',
        'smartphones' => 'mobile-alt',
        'laptops' => 'laptop'
    ];
    
    $normalizedName = strtolower($categoryName);
    
    return $icons[$normalizedName] ?? 'tag';
}
@endphp
