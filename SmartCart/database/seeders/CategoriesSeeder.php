<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main categories
        $electronics = $this->createCategory([
            'name' => 'Electronics',
            'description' => 'Latest electronics and gadgets',
            'image_path' => 'categories/electronics.jpg',
        ]);

        $clothing = $this->createCategory([
            'name' => 'Clothing',
            'description' => 'Fashion and apparel for all ages',
            'image_path' => 'categories/clothing.jpg',
        ]);

        $homeKitchen = $this->createCategory([
            'name' => 'Home & Kitchen',
            'description' => 'Everything for your home',
            'image_path' => 'categories/home-kitchen.jpg',
        ]);
        
        // Electronics subcategories
        $this->createCategory([
            'name' => 'Smartphones',
            'description' => 'Latest smartphones from top brands',
            'image_path' => 'categories/smartphones.jpg',
            'parent_id' => $electronics->id,
        ]);

        $this->createCategory([
            'name' => 'Laptops',
            'description' => 'Powerful laptops for work and play',
            'image_path' => 'categories/laptops.jpg',
            'parent_id' => $electronics->id,
        ]);

        $this->createCategory([
            'name' => 'Accessories',
            'description' => 'Essential accessories for your devices',
            'image_path' => 'categories/accessories.jpg',
            'parent_id' => $electronics->id,
        ]);
        
        // Clothing subcategories
        $this->createCategory([
            'name' => 'Men',
            'description' => 'Clothing for men',
            'image_path' => 'categories/men.jpg',
            'parent_id' => $clothing->id,
        ]);

        $this->createCategory([
            'name' => 'Women',
            'description' => 'Clothing for women',
            'image_path' => 'categories/women.jpg',
            'parent_id' => $clothing->id,
        ]);

        $this->createCategory([
            'name' => 'Kids',
            'description' => 'Clothing for kids',
            'image_path' => 'categories/kids.jpg',
            'parent_id' => $clothing->id,
        ]);
        
        // Home & Kitchen subcategories
        $this->createCategory([
            'name' => 'Appliances',
            'description' => 'Home appliances for modern living',
            'image_path' => 'categories/appliances.jpg',
            'parent_id' => $homeKitchen->id,
        ]);

        $this->createCategory([
            'name' => 'Kitchenware',
            'description' => 'Essential tools for your kitchen',
            'image_path' => 'categories/kitchenware.jpg',
            'parent_id' => $homeKitchen->id,
        ]);

        $this->createCategory([
            'name' => 'Furniture',
            'description' => 'Stylish furniture for every room',
            'image_path' => 'categories/furniture.jpg',
            'parent_id' => $homeKitchen->id,
        ]);
    }
    
    /**
     * Create a category and return it.
     */
    private function createCategory(array $data): Category
    {
        return Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
            'image_path' => $data['image_path'],
            'parent_id' => $data['parent_id'] ?? null,
            'status' => 'active',
        ]);
    }
}
