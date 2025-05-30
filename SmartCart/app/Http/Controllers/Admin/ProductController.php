<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['categories', 'images' => function ($query) {
            $query->where('is_primary', true);
        }])->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products',
            'status' => 'required|in:active,inactive,draft',
            'featured' => 'boolean',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the product
        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'status' => $request->status,
            'featured' => $request->featured ?? false,
        ]);

        // Attach categories
        $product->categories()->attach($request->categories);

        // Handle images
        if ($request->hasFile('images')) {
            $isPrimary = true; // First image is primary
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => 0,
                ]);
                
                $isPrimary = false; // Set subsequent images as non-primary
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'images']);
        
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load(['categories', 'images']);
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'status' => 'required|in:active,inactive,draft',
            'featured' => 'boolean',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the product
        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'stock_quantity' => $request->stock_quantity,
            'sku' => $request->sku,
            'status' => $request->status,
            'featured' => $request->featured ?? false,
        ]);

        // Sync categories
        $product->categories()->sync($request->categories);

        // Handle new images
        if ($request->hasFile('images')) {
            $hasExistingImages = $product->images()->exists();
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => !$hasExistingImages, // First image is primary only if no existing images
                    'sort_order' => $product->images()->max('sort_order') + 1,
                ]);
                
                $hasExistingImages = true; // Mark that we now have images
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Delete the product (will cascade delete images through relationship)
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Set an image as the primary image for a product.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductImage  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        // Make sure the image belongs to the product
        if ($image->product_id !== $product->id) {
            return redirect()->route('admin.products.edit', $product)
                ->with('error', 'The image does not belong to this product.');
        }
        
        // Remove primary flag from all other images
        $product->images()->update(['is_primary' => false]);
        
        // Set this image as primary
        $image->update(['is_primary' => true]);
        
        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Primary image updated successfully.');
    }

    /**
     * Remove an image from a product.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductImage  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage(Product $product, ProductImage $image)
    {
        // Make sure the image belongs to the product
        if ($image->product_id !== $product->id) {
            return redirect()->route('admin.products.edit', $product)
                ->with('error', 'The image does not belong to this product.');
        }
        
        // Check if this is the primary image
        $isPrimary = $image->is_primary;
        
        // Delete the image from storage
        Storage::disk('public')->delete($image->image_path);
        
        // Delete the image record
        $image->delete();
        
        // If we deleted the primary image, make another image primary
        if ($isPrimary) {
            $newPrimaryImage = $product->images()->first();
            if ($newPrimaryImage) {
                $newPrimaryImage->update(['is_primary' => true]);
            }
        }
        
        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Image deleted successfully.');
    }
} 