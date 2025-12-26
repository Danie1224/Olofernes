<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shipping;
use App\Models\Order;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ShippingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_US');

        $orderIds = Order::pluck('order_id')->toArray();

        if (empty($orderIds)) {
            $this->command->error('Orders table is empty! Please seed orders first.');
            return;
        }

        foreach ($orderIds as $orderId) {
            Shipping::create([
                // PK shipping_id is auto-increment per migration
                'order_id' => $orderId,
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip_code' => $faker->postcode,
                'country' => $faker->country,
                'tracking_number' => strtoupper($faker->bothify('TRK-########')),
                'shipping_status' => $faker->randomElement(['pending', 'shipped', 'delivered', 'cancelled']),
            ]);
        }

        $this->command->info('ShippingsTableSeeder completed: shipping records inserted.');
    }
}
