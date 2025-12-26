<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class OrderItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_US'); // English locale

        $orderIds = Order::pluck('order_id')->toArray();
        $productIds = Product::pluck('product_id')->toArray();

        if (empty($orderIds) || empty($productIds)) {
            $this->command->error('Orders or Products table is empty! Seed them first.');
            return;
        }

        // Generate 2-5 items per order
        foreach ($orderIds as $orderId) {
            $numItems = $faker->numberBetween(2, 5);

            for ($i = 0; $i < $numItems; $i++) {
                $productId = $faker->randomElement($productIds);
                $unitPrice = Product::where('product_id', $productId)->value('price');
                $quantity = $faker->numberBetween(1, 5);

                OrderItem::create([
                    // PK order_item_id is auto-increment per migration
                    'order_id' => $orderId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $unitPrice * $quantity,
                ]);
            }
        }

        $this->command->info('OrderItemsTableSeeder completed: order items inserted.');
    }
}
