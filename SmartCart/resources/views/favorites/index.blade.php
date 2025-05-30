@extends('layouts.frontend')

@section('title', 'My Favorites')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Favorites</h1>
            
            @if($favorites->count() > 0)
                <div class="row">
                    @foreach($favorites as $favorite)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card product-card h-100">
                                @if($favorite->product->discount_price)
                                    <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                                @endif
                                
                                @if($favorite->product->primaryImage)
                                    <img src="{{ asset('storage/' . $favorite->product->primaryImage->image_path) }}" class="card-img-top" alt="{{ $favorite->product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $favorite->product->name }}">
                                @endif
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $favorite->product->name }}</h5>
                                    
                                    <div class="mb-2">
                                        <div class="small">
                                            @foreach($favorite->product->categories as $category)
                                                <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        @if($favorite->product->discount_price)
                                            <span class="text-muted text-decoration-line-through">${{ number_format($favorite->product->price, 2) }}</span>
                                            <span class="text-danger ms-2">${{ number_format($favorite->product->discount_price, 2) }}</span>
                                        @else
                                            <span>${{ number_format($favorite->product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('shop.product', $favorite->product->slug) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                            <div class="d-flex">
                                                <form action="{{ route('favorites.remove', $favorite->id) }}" method="POST" class="me-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to remove this from favorites?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $favorite->product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-sm btn-cart" {{ !$favorite->product->inStock() ? 'disabled' : '' }}>
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-heart fa-4x mb-3 text-muted"></i>
                        <h3>No favorites yet</h3>
                        <p class="mb-4">You haven't added any products to your favorites yet.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-primary">Browse Products</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection 