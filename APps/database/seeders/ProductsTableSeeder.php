<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Brand;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Laptops
            [
                'product_code' => 'LAP-001',
                'name' => 'MacBook Pro 16-inch',
                'description' => 'Powerful laptop with M2 Pro chip, 16GB RAM, and 512GB SSD storage.',
                'price' => 2499.99,
                'stock_quantity' => 15,
                'category' => 'Laptop',
            ],
            [
                'product_code' => 'LAP-002',
                'name' => 'Dell XPS 13',
                'description' => 'Ultra-thin laptop with Intel i7 processor and 8GB RAM.',
                'price' => 1299.99,
                'stock_quantity' => 25,
                'category' => 'Laptop',
            ],
            [
                'product_code' => 'LAP-003',
                'name' => 'HP Spectre x360',
                'description' => '2-in-1 convertible laptop with touchscreen and pen support.',
                'price' => 1199.99,
                'stock_quantity' => 20,
                'category' => 'Laptop',
            ],
            [
                'product_code' => 'LAP-004',
                'name' => 'ASUS ROG Strix G15',
                'description' => 'Gaming laptop with RTX 3060 graphics and AMD Ryzen 7 processor.',
                'price' => 1599.99,
                'stock_quantity' => 12,
                'category' => 'Laptop',
            ],

            // Smartphones
            [
                'product_code' => 'PHN-001',
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with A17 Pro chip and titanium design.',
                'price' => 999.99,
                'stock_quantity' => 30,
                'category' => 'Smartphone',
            ],
            [
                'product_code' => 'PHN-002',
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Premium Android phone with S Pen and 200MP camera.',
                'price' => 1199.99,
                'stock_quantity' => 22,
                'category' => 'Smartphone',
            ],
            [
                'product_code' => 'PHN-003',
                'name' => 'Google Pixel 8 Pro',
                'description' => 'AI-powered smartphone with exceptional camera quality.',
                'price' => 899.99,
                'stock_quantity' => 18,
                'category' => 'Smartphone',
            ],

            // Tablets
            [
                'product_code' => 'TAB-001',
                'name' => 'iPad Pro 12.9-inch',
                'description' => 'Professional tablet with M2 chip and Liquid Retina XDR display.',
                'price' => 1099.99,
                'stock_quantity' => 14,
                'category' => 'Tablet',
            ],
            [
                'product_code' => 'TAB-002',
                'name' => 'Samsung Galaxy Tab S9',
                'description' => 'Android tablet with S Pen and AMOLED display.',
                'price' => 799.99,
                'stock_quantity' => 16,
                'category' => 'Tablet',
            ],

            // Gaming
            [
                'product_code' => 'GAM-001',
                'name' => 'PlayStation 5',
                'description' => 'Next-generation gaming console with 4K gaming support.',
                'price' => 499.99,
                'stock_quantity' => 8,
                'category' => 'Gaming',
            ],
            [
                'product_code' => 'GAM-002',
                'name' => 'Xbox Series X',
                'description' => 'Microsoft gaming console with 4K 120fps gaming.',
                'price' => 499.99,
                'stock_quantity' => 10,
                'category' => 'Gaming',
            ],
            [
                'product_code' => 'GAM-003',
                'name' => 'Nintendo Switch OLED',
                'description' => 'Portable gaming console with OLED screen and Joy-Con controllers.',
                'price' => 349.99,
                'stock_quantity' => 20,
                'category' => 'Gaming',
            ],

            // Accessories
            [
                'product_code' => 'ACC-001',
                'name' => 'AirPods Pro (2nd Gen)',
                'description' => 'Wireless earbuds with active noise cancellation and spatial audio.',
                'price' => 249.99,
                'stock_quantity' => 35,
                'category' => 'Accessory',
            ],
            [
                'product_code' => 'ACC-002',
                'name' => 'Sony WH-1000XM5 Headphones',
                'description' => 'Premium noise-cancelling wireless headphones.',
                'price' => 399.99,
                'stock_quantity' => 12,
                'category' => 'Accessory',
            ],
            [
                'product_code' => 'ACC-003',
                'name' => 'Logitech MX Master 3S Mouse',
                'description' => 'Wireless mouse with precision tracking and ergonomic design.',
                'price' => 99.99,
                'stock_quantity' => 28,
                'category' => 'Accessory',
            ],
            [
                'product_code' => 'ACC-004',
                'name' => 'Apple Magic Keyboard',
                'description' => 'Wireless keyboard with backlit keys and rechargeable battery.',
                'price' => 129.99,
                'stock_quantity' => 24,
                'category' => 'Accessory',
            ],

            // Desktop
            [
                'product_code' => 'DSK-001',
                'name' => 'Apple Mac Studio',
                'description' => 'Compact desktop with M2 Max chip and professional performance.',
                'price' => 1999.99,
                'stock_quantity' => 6,
                'category' => 'Desktop',
            ],
            [
                'product_code' => 'DSK-002',
                'name' => 'Dell OptiPlex 7090',
                'description' => 'Business desktop with Intel i7 processor and 16GB RAM.',
                'price' => 899.99,
                'stock_quantity' => 15,
                'category' => 'Desktop',
            ],
        ];

        // Ensure we have at least one admin and brand to satisfy NOT NULL constraints
        if (Admin::count() === 0 || Brand::count() === 0) {
            $this->command->error('Admins or Brands table is empty! Seed them first.');
            return;
        }

        foreach ($products as $productData) {
            // assign a random existing admin and brand for this product
            $productData['admin_id'] = Admin::inRandomOrder()->first()->admin_id;
            $productData['brand_id'] = Brand::inRandomOrder()->first()->brand_id;

            Product::firstOrCreate(
                ['product_code' => $productData['product_code']],
                $productData
            );
        }
    }
}
