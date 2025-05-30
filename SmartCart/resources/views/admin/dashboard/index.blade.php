@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4">Dashboard</h1>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Product::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Categories</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Category::count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Active Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Product::where('status', 'active')->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2 stats-card">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Featured Products</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Product::where('featured', true)->count() }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Product::latest()->take(5)->get() as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        @if($product->discount_price)
                                            <span class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</span>
                                            <span class="text-danger">${{ number_format($product->discount_price, 2) }}</span>
                                        @else
                                            ${{ number_format($product->price, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $product->status === 'active' ? 'success' : 'warning' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">View All Products</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get data for the chart
        const categories = [
            @foreach(\App\Models\Category::whereNull('parent_id')->get() as $category)
                '{{ $category->name }}',
            @endforeach
        ];

        const productCounts = [
            @foreach(\App\Models\Category::whereNull('parent_id')->get() as $category)
                {{ $category->products->count() }},
            @endforeach
        ];

        // Set up the chart
        const ctx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: categories,
                datasets: [{
                    data: productCounts,
                    backgroundColor: [
                        '#4e73df',
                        '#1cc88a',
                        '#36b9cc',
                        '#f6c23e',
                        '#e74a3b',
                        '#6f42c1',
                        '#fd7e14',
                        '#20c997',
                        '#e83e8c'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    });
</script>
@endsection 