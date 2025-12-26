<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddToCart;
use App\Models\Customer;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class AddToCartTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        // Ensure there are customers and products first
        $customer = Customer::first();
        $product  = Product::first();

        // Example: add multiple cart entries (PK auto-increment per migration)
        AddToCart::create([
            'customer_id' => $customer->customer_id,
            'product_id'  => $product->product_id,
            'quantity'    => 2,
        ]);

        AddToCart::create([
            'customer_id' => $customer->customer_id,
            'product_id'  => $product->product_id,
            'quantity'    => 5,
        ]);
    }
}
