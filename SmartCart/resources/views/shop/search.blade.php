@extends('layouts.frontend')

@section('title', 'Search Results: ' . $query)

@section('content')
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('shop.search') }}" method="GET" id="filter-form">
                        <input type="hidden" name="query" value="{{ $query }}">
                        
                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Categories</h6>
                            @foreach(\App\Models\Category::whereNull('parent_id')->get() as $category)
                                <div class="mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}" 
                                            {{ (request()->has('categories') && in_array($category->id, request('categories'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                    
                                    @if($category->children->count() > 0)
                                        <div class="ms-3">
                                            @foreach($category->children as $child)
                                                <div class="form-check">
                                                    <input class="form-check-input filter-checkbox" type="checkbox" name="categories[]" value="{{ $child->id }}" id="category{{ $child->id }}" 
                                                        {{ (request()->has('categories') && in_array($child->id, request('categories'))) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category{{ $child->id }}">
                                                        {{ $child->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Price Range</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="min_price" class="form-label">Min</label>
                                        <input type="number" class="form-control form-control-sm" id="min_price" name="min_price" value="{{ request('min_price') }}" min="0">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="max_price" class="form-label">Max</label>
                                        <input type="number" class="form-control form-control-sm" id="max_price" name="max_price" value="{{ request('max_price') }}" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Other Filters -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Other Filters</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="in_stock" value="1" id="inStock" 
                                    {{ request()->has('in_stock') ? 'checked' : '' }}>
                                <label class="form-check-label" for="inStock">
                                    In Stock Only
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="on_sale" value="1" id="onSale" 
                                    {{ request()->has('on_sale') ? 'checked' : '' }}>
                                <label class="form-check-label" for="onSale">
                                    On Sale
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="featured" value="1" id="featured" 
                                    {{ request()->has('featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">
                                    Featured Products
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            <a href="{{ route('shop.search', ['query' => $query]) }}" class="btn btn-outline-secondary">Clear Filters</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Search Header -->
            <div class="mb-4">
                <h1>Search Results for "{{ $query }}"</h1>
                <p>{{ $products->total() }} products found</p>
            </div>
            
            <!-- Top Bar with Sort and Display Options -->
            <div class="card mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <span class="me-2">{{ $products->total() }} Products Found</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <label for="sort" class="me-2">Sort by:</label>
                        <select class="form-select form-select-sm" id="sort" name="sort">
                            <option value="default" {{ request('sort') == 'default' || !request('sort') ? 'selected' : '' }}>Default</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularity</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="row">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
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
                                    <div class="small">
                                        @foreach($product->categories as $category)
                                            <span class="badge bg-light text-dark me-1">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                
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
                                        <div class="d-flex">
                                            @auth
                                            <form action="{{ route('favorites.toggle') }}" method="POST" class="me-1">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="btn btn-sm btn-favorite {{ Auth::user()->favorites->contains('product_id', $product->id) ? 'active' : '' }}">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </form>
                                            @endauth
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm btn-cart" {{ !$product->inStock() ? 'disabled' : '' }}>
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products found matching your search query. Try using different keywords or browse our categories.
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">Browse All Products</a>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle sort change
        document.getElementById('sort').addEventListener('change', function() {
            const form = document.getElementById('filter-form');
            const sortInput = document.createElement('input');
            sortInput.type = 'hidden';
            sortInput.name = 'sort';
            sortInput.value = this.value;
            form.appendChild(sortInput);
            form.submit();
        });
        
        // Auto-submit form on checkbox change
        document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                document.getElementById('filter-form').submit();
            });
        });
    });
</script>
@endsection 