<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Admin;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_US');

        $customerIds = Customer::pluck('customer_id')->toArray();
        $productIds = \App\Models\Product::pluck('product_id')->toArray();
        $adminIds = Admin::pluck('admin_id')->toArray();

        if (empty($customerIds) || empty($productIds) || empty($adminIds)) {
            $this->command->error('Customers, Products, or Admins not found! Seed them first.');
            return;
        }

        for ($i = 1; $i <= 20; $i++) {
            Order::create([
                // PK order_id is auto-increment per migration
                'customer_id' => $faker->randomElement($customerIds),
                'product_id' => $faker->randomElement($productIds),
                'admin_id' => $faker->randomElement($adminIds),
                'quantity' => $faker->numberBetween(1, 5),
                'total_price' => $faker->randomFloat(2, 500, 20000),
                'payment_method' => $faker->randomElement(['cash', 'card', 'gcash', 'paypal']),
                'status' => $faker->randomElement(['pending', 'processing', 'shipped', 'completed', 'cancelled']),
                'order_date' => $faker->dateTimeBetween('-1 year', 'now'),
            ]);
        }

        $this->command->info('OrdersTableSeeder completed: 20 orders inserted.');
    }
}
