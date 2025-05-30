<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $electronics = Category::where('name', 'Electronics')->first();
        $clothing = Category::where('name', 'Clothing')->first();
        $home = Category::where('name', 'Home & Kitchen')->first();
        
        // Get subcategories
        $smartphones = Category::where('name', 'Smartphones')->first();
        $laptops = Category::where('name', 'Laptops')->first();
        $mens = Category::where('name', 'Men')->first();
        $womens = Category::where('name', 'Women')->first();
        $kitchen = Category::where('name', 'Kitchenware')->first();
        $furniture = Category::where('name', 'Furniture')->first();

        // Electronics - Smartphones
        $this->createProduct([
            'name' => 'iPhone 13 Pro',
            'description' => 'Apple iPhone 13 Pro with 6.1-inch Super Retina XDR display, A15 Bionic chip, and Pro camera system.',
            'price' => 999.99,
            'discount_price' => 899.99,
            'stock_quantity' => 50,
            'sku' => 'IPHONE-13-PRO',
            'featured' => true,
            'status' => 'active',
            'categories' => [$electronics->id, $smartphones->id],
            'images' => [
                ['image_path' => 'products/iphone13pro.jpg', 'is_primary' => true],
                ['image_path' => 'products/iphone13pro_2.jpg', 'is_primary' => false],
            ]
        ]);

        $this->createProduct([
            'name' => 'Samsung Galaxy S22',
            'description' => 'Samsung Galaxy S22 with Dynamic AMOLED 2X display, Exynos 2200 processor, and 108MP camera.',
            'price' => 799.99,
            'discount_price' => null,
            'stock_quantity' => 35,
            'sku' => 'SAMSUNG-S22',
            'featured' => true,
            'status' => 'active',
            'categories' => [$electronics->id, $smartphones->id],
            'images' => [
                ['image_path' => 'products/galaxys22.jpg', 'is_primary' => true],
            ]
        ]);

        $this->createProduct([
            'name' => 'Google Pixel 6',
            'description' => 'Google Pixel 6 with 6.4-inch FHD+ display, Google Tensor processor, and dual camera system.',
            'price' => 599.99,
            'discount_price' => 549.99,
            'stock_quantity' => 20,
            'sku' => 'GOOGLE-PIXEL-6',
            'featured' => false,
            'status' => 'active',
            'categories' => [$electronics->id, $smartphones->id],
            'images' => [
                ['image_path' => 'products/pixel6.jpg', 'is_primary' => true],
            ]
        ]);

        // Electronics - Laptops
        $this->createProduct([
            'name' => 'MacBook Pro 14"',
            'description' => 'Apple MacBook Pro 14" with M1 Pro chip, 16GB RAM, 512GB SSD, and Liquid Retina XDR display.',
            'price' => 1999.99,
            'discount_price' => null,
            'stock_quantity' => 15,
            'sku' => 'MACBOOK-PRO-14',
            'featured' => true,
            'status' => 'active',
            'categories' => [$electronics->id, $laptops->id],
            'images' => [
                ['image_path' => 'products/macbookpro14.jpg', 'is_primary' => true],
            ]
        ]);

        $this->createProduct([
            'name' => 'Dell XPS 15',
            'description' => 'Dell XPS 15 with 11th Gen Intel Core i7, 16GB RAM, 1TB SSD, and 15.6-inch FHD+ display.',
            'price' => 1599.99,
            'discount_price' => 1499.99,
            'stock_quantity' => 10,
            'sku' => 'DELL-XPS-15',
            'featured' => false,
            'status' => 'active',
            'categories' => [$electronics->id, $laptops->id],
            'images' => [
                ['image_path' => 'products/dellxps15.jpg', 'is_primary' => true],
            ]
        ]);

        // Clothing - Men
        $this->createProduct([
            'name' => 'Slim Fit Jeans',
            'description' => 'Classic slim fit jeans for men, made with premium denim material.',
            'price' => 49.99,
            'discount_price' => null,
            'stock_quantity' => 100,
            'sku' => 'MENS-JEANS-01',
            'featured' => false,
            'status' => 'active',
            'categories' => [$clothing->id, $mens->id],
            'images' => [
                ['image_path' => 'products/mens_jeans.jpg', 'is_primary' => true],
            ]
        ]);

        $this->createProduct([
            'name' => 'Casual Button-Down Shirt',
            'description' => 'Comfortable cotton casual button-down shirt for men, perfect for everyday wear.',
            'price' => 39.99,
            'discount_price' => 29.99,
            'stock_quantity' => 75,
            'sku' => 'MENS-SHIRT-01',
            'featured' => true,
            'status' => 'active',
            'categories' => [$clothing->id, $mens->id],
            'images' => [
                ['image_path' => 'products/mens_shirt.jpg', 'is_primary' => true],
            ]
        ]);

        // Clothing - Women
        $this->createProduct([
            'name' => 'Summer Floral Dress',
            'description' => 'Beautiful floral print summer dress for women, made with lightweight fabric.',
            'price' => 59.99,
            'discount_price' => 49.99,
            'stock_quantity' => 60,
            'sku' => 'WOMENS-DRESS-01',
            'featured' => true,
            'status' => 'active',
            'categories' => [$clothing->id, $womens->id],
            'images' => [
                ['image_path' => 'products/womens_dress.jpg', 'is_primary' => true],
            ]
        ]);

        $this->createProduct([
            'name' => 'High-Waisted Jeans',
            'description' => 'Stylish high-waisted jeans for women, made with stretch denim for comfort.',
            'price' => 54.99,
            'discount_price' => null,
            'stock_quantity' => 85,
            'sku' => 'WOMENS-JEANS-01',
            'featured' => false,
            'status' => 'active',
            'categories' => [$clothing->id, $womens->id],
            'images' => [
                ['image_path' => 'products/womens_jeans.jpg', 'is_primary' => true],
            ]
        ]);

        // Home & Kitchen - Kitchenware
        $this->createProduct([
            'name' => 'Stainless Steel Cookware Set',
            'description' => '10-piece stainless steel cookware set including pots and pans with glass lids.',
            'price' => 199.99,
            'discount_price' => 169.99,
            'stock_quantity' => 25,
            'sku' => 'KITCHEN-SET-01',
            'featured' => true,
            'status' => 'active',
            'categories' => [$home->id, $kitchen->id],
            'images' => [
                ['image_path' => 'products/cookware_set.jpg', 'is_primary' => true],
            ]
        ]);

        $this->createProduct([
            'name' => 'Professional Knife Set',
            'description' => '15-piece professional knife set with wooden block, made with high-carbon stainless steel.',
            'price' => 129.99,
            'discount_price' => null,
            'stock_quantity' => 30,
            'sku' => 'KNIFE-SET-01',
            'featured' => false,
            'status' => 'active',
            'categories' => [$home->id, $kitchen->id],
            'images' => [
                ['image_path' => 'products/knife_set.jpg', 'is_primary' => true],
            ]
        ]);

        // Home & Kitchen - Furniture
        $this->createProduct([
            'name' => 'Modern Sectional Sofa',
            'description' => 'Modern L-shaped sectional sofa with chaise lounge, upholstered in premium fabric.',
            'price' => 899.99,
            'discount_price' => 799.99,
            'stock_quantity' => 10,
            'sku' => 'SOFA-01',
            'featured' => true,
            'status' => 'active',
            'categories' => [$home->id, $furniture->id],
            'images' => [
                ['image_path' => 'products/sectional_sofa.jpg', 'is_primary' => true],
            ]
        ]);

        $this->createProduct([
            'name' => 'Queen Size Bed Frame',
            'description' => 'Modern queen size bed frame with upholstered headboard and wood slats.',
            'price' => 499.99,
            'discount_price' => 449.99,
            'stock_quantity' => 15,
            'sku' => 'BED-FRAME-01',
            'featured' => false,
            'status' => 'active',
            'categories' => [$home->id, $furniture->id],
            'images' => [
                ['image_path' => 'products/bed_frame.jpg', 'is_primary' => true],
            ]
        ]);
    }

    /**
     * Create a product with its categories and images.
     */
    private function createProduct(array $data): void
    {
        $product = Product::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'],
            'price' => $data['price'],
            'discount_price' => $data['discount_price'],
            'stock_quantity' => $data['stock_quantity'],
            'sku' => $data['sku'],
            'featured' => $data['featured'],
            'status' => $data['status'],
        ]);

        // Attach categories
        $product->categories()->attach($data['categories']);

        // Create images
        if (isset($data['images'])) {
            foreach ($data['images'] as $imageData) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageData['image_path'],
                    'is_primary' => $imageData['is_primary'],
                ]);
            }
        }
    }
}
