@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Product Details: {{ $product->name }}</h1>
    <div>
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Images</h5>
            </div>
            <div class="card-body p-0">
                @if($product->images->count() > 0)
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="{{ $product->name }}">
                                    @if($image->is_primary)
                                        <div class="carousel-caption d-none d-md-block">
                                            <span class="badge bg-success">Primary Image</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($product->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                    <div class="p-3">
                        <p class="mb-0"><small class="text-muted">{{ $product->images->count() }} image(s) available</small></p>
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-image fa-4x text-muted"></i>
                        <p class="mt-3">No images available</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Categories</h5>
            </div>
            <div class="card-body">
                @if($product->categories->count() > 0)
                    <div class="list-group">
                        @foreach($product->categories as $category)
                            <a href="{{ route('admin.categories.show', $category->id) }}" class="list-group-item list-group-item-action">
                                @if($category->parent)
                                    <small class="text-muted">{{ $category->parent->name }} &raquo;</small>
                                @endif
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No categories assigned.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th style="width: 200px">ID</th>
                            <td>{{ $product->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td>{{ $product->slug }}</td>
                        </tr>
                        <tr>
                            <th>SKU</th>
                            <td>{{ $product->sku }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>${{ number_format($product->price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Discount Price</th>
                            <td>
                                @if($product->discount_price)
                                    ${{ number_format($product->discount_price, 2) }}
                                    <span class="badge bg-danger ms-2">
                                        {{ round((1 - $product->discount_price / $product->price) * 100) }}% OFF
                                    </span>
                                @else
                                    <span class="text-muted">No discount</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Stock Quantity</th>
                            <td>
                                {{ $product->stock_quantity }}
                                @if($product->stock_quantity <= 5)
                                    <span class="badge bg-warning ms-2">Low Stock</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                                @if($product->featured)
                                    <span class="badge bg-info ms-1">Featured</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $product->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $product->updated_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Description</h5>
            </div>
            <div class="card-body">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    </div>
</div>
@endsection 