@extends('layouts.admin')

@section('title', 'Category Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Category Details: {{ $category->name }}</h1>
    <div>
        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Image</h5>
            </div>
            <div class="card-body text-center">
                @if($category->image_path)
                    <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" class="img-fluid rounded">
                @else
                    <div class="bg-light p-5 rounded">
                        <i class="fas fa-image fa-4x text-muted"></i>
                        <p class="mt-3">No image available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Category Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th style="width: 200px">ID</th>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $category->name }}</td>
                        </tr>
                        <tr>
                            <th>Slug</th>
                            <td>{{ $category->slug }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $category->description ?? 'No description' }}</td>
                        </tr>
                        <tr>
                            <th>Parent Category</th>
                            <td>
                                @if($category->parent)
                                    <a href="{{ route('admin.categories.show', $category->parent->id) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                @else
                                    None
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $category->status === 'active' ? 'success' : 'warning' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $category->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $category->updated_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Subcategories</h5>
            </div>
            <div class="card-body">
                @if($category->children->count() > 0)
                    <div class="list-group">
                        @foreach($category->children as $child)
                            <a href="{{ route('admin.categories.show', $child->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                {{ $child->name }}
                                <span class="badge bg-{{ $child->status === 'active' ? 'success' : 'warning' }} rounded-pill">
                                    {{ ucfirst($child->status) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">No subcategories found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 