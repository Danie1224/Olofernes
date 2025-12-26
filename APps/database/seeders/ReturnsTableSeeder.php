<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReturnRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Admin;
use Faker\Factory as Faker;

class ReturnsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('en_US');

        $orderIds = Order::pluck('order_id')->toArray();
        $productIds = Product::pluck('product_id')->toArray();
        $customerIds = Customer::pluck('customer_id')->toArray();
        $adminIds = Admin::pluck('admin_id')->toArray();

        if (empty($orderIds) || empty($productIds) || empty($customerIds)) {
            $this->command->warn('⚠️ Skipped ReturnRequests seeding — missing orders, products, or customers.');
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            ReturnRequest::create([
                'order_id' => $faker->randomElement($orderIds),
                'product_id' => $faker->randomElement($productIds),
                'customer_id' => $faker->randomElement($customerIds),
                'reason' => $faker->sentence(10),
                'status' => $faker->randomElement(['pending', 'approved', 'rejected', 'refunded']),
            ]);
        }

        $this->command->info('✅ 20 Return Requests seeded successfully.');
    }
}
