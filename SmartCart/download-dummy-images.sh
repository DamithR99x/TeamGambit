#!/bin/bash

# Create directories if they don't exist
mkdir -p public/storage/products
mkdir -p public/storage/categories

# Download product images
echo "Downloading product images..."
curl -o public/storage/products/iphone13pro.jpg https://placehold.co/600x400/007bff/ffffff?text=iPhone+13+Pro
curl -o public/storage/products/iphone13pro_2.jpg https://placehold.co/600x400/0056b3/ffffff?text=iPhone+13+Pro+2
curl -o public/storage/products/galaxys22.jpg https://placehold.co/600x400/28a745/ffffff?text=Galaxy+S22
curl -o public/storage/products/pixel6.jpg https://placehold.co/600x400/dc3545/ffffff?text=Pixel+6
curl -o public/storage/products/macbookpro14.jpg https://placehold.co/600x400/6f42c1/ffffff?text=MacBook+Pro+14
curl -o public/storage/products/dellxps15.jpg https://placehold.co/600x400/fd7e14/ffffff?text=Dell+XPS+15
curl -o public/storage/products/mens_jeans.jpg https://placehold.co/600x400/20c997/ffffff?text=Mens+Jeans
curl -o public/storage/products/mens_shirt.jpg https://placehold.co/600x400/e83e8c/ffffff?text=Mens+Shirt
curl -o public/storage/products/womens_dress.jpg https://placehold.co/600x400/6f42c1/ffffff?text=Womens+Dress
curl -o public/storage/products/womens_jeans.jpg https://placehold.co/600x400/fd7e14/ffffff?text=Womens+Jeans
curl -o public/storage/products/cookware_set.jpg https://placehold.co/600x400/20c997/ffffff?text=Cookware+Set
curl -o public/storage/products/knife_set.jpg https://placehold.co/600x400/e83e8c/ffffff?text=Knife+Set
curl -o public/storage/products/sectional_sofa.jpg https://placehold.co/600x400/007bff/ffffff?text=Sectional+Sofa
curl -o public/storage/products/bed_frame.jpg https://placehold.co/600x400/28a745/ffffff?text=Bed+Frame

# Download category images
echo "Downloading category images..."
curl -o public/storage/categories/electronics.jpg https://placehold.co/600x400/007bff/ffffff?text=Electronics
curl -o public/storage/categories/clothing.jpg https://placehold.co/600x400/28a745/ffffff?text=Clothing
curl -o public/storage/categories/home-kitchen.jpg https://placehold.co/600x400/dc3545/ffffff?text=Home+%26+Kitchen
curl -o public/storage/categories/smartphones.jpg https://placehold.co/600x400/6f42c1/ffffff?text=Smartphones
curl -o public/storage/categories/laptops.jpg https://placehold.co/600x400/fd7e14/ffffff?text=Laptops
curl -o public/storage/categories/accessories.jpg https://placehold.co/600x400/20c997/ffffff?text=Accessories
curl -o public/storage/categories/men.jpg https://placehold.co/600x400/e83e8c/ffffff?text=Men
curl -o public/storage/categories/women.jpg https://placehold.co/600x400/007bff/ffffff?text=Women
curl -o public/storage/categories/kids.jpg https://placehold.co/600x400/28a745/ffffff?text=Kids
curl -o public/storage/categories/appliances.jpg https://placehold.co/600x400/dc3545/ffffff?text=Appliances
curl -o public/storage/categories/kitchenware.jpg https://placehold.co/600x400/6f42c1/ffffff?text=Kitchenware
curl -o public/storage/categories/furniture.jpg https://placehold.co/600x400/fd7e14/ffffff?text=Furniture

echo "Downloading completed!" 