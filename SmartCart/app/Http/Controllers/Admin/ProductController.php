<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Auth middleware is already applied at the route level
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('categories', 'primaryImage')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:100|unique:products',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'status' => 'required|in:active,inactive',
            'featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create product
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'featured' => $request->featured ? true : false,
            'status' => $request->status,
        ]);

        // Attach categories
        $product->categories()->attach($request->categories);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $isPrimary = true; // First image will be primary
            
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                ]);
                
                $isPrimary = false; // Only the first image is primary
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('categories', 'images')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with('categories', 'images')->findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products')->ignore($product->id),
            ],
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'status' => 'required|in:active,inactive',
            'featured' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update product
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'featured' => $request->featured ? true : false,
            'status' => $request->status,
        ]);

        // Sync categories
        $product->categories()->sync($request->categories);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary,
                ]);
                
                $hasPrimary = true; // Set after first image
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        // Delete product images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Delete product and related data
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
    
    /**
     * Delete a product image.
     */
    public function destroyImage(string $id)
    {
        $image = ProductImage::findOrFail($id);
        $productId = $image->product_id;
        
        // Delete image from storage
        Storage::disk('public')->delete($image->image_path);
        
        // If it was a primary image, set another image as primary
        if ($image->is_primary) {
            $otherImage = ProductImage::where('product_id', $productId)
                ->where('id', '!=', $id)
                ->first();
                
            if ($otherImage) {
                $otherImage->update(['is_primary' => true]);
            }
        }
        
        // Delete image record
        $image->delete();
        
        return redirect()->route('admin.products.edit', $productId)
            ->with('success', 'Product image deleted successfully.');
    }
}
