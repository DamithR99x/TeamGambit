<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image_path',
        'status',
    ];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the subcategories for the category.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getAllProducts()
    {
        // Get products directly associated with this category
        $products = $this->products()->get();
        
        // Get products from subcategories
        $childrenIds = $this->children()->pluck('id')->toArray();
        if (!empty($childrenIds)) {
            $childrenProducts = Product::whereHas('categories', function($query) use ($childrenIds) {
                $query->whereIn('categories.id', $childrenIds);
            })->get();
            
            // Merge the collections
            $products = $products->merge($childrenProducts);
        }
        
        return $products->unique('id');
    }
}
