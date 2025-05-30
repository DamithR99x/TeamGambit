<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        'price',
        'discount_price',
        'stock_quantity',
        'sku',
        'featured',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'featured' => 'boolean',
    ];

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the primary image for the product.
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get the categories for the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the users who favorited this product.
     */
    public function favoritedBy()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Check if product is in stock.
     */
    public function inStock()
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Get current price (discount price if available, otherwise regular price).
     */
    public function getCurrentPrice()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Get the primary image for the product.
     *
     * @return \App\Models\ProductImage|null
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() 
            ?? $this->images()->first();
    }
}
