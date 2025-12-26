<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Customer;
use Faker\Factory as Faker;

class PaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        foreach (Order::all() as $order) {
            Payment::create([
                'order_id' => $order->order_id,
                'customer_id' => $order->customer_id,
                'payment_method' => $faker->randomElement(['credit_card', 'paypal', 'gcash', 'cod']),
                'amount' => $order->total_price,
                'payment_status' => $faker->randomElement(['completed', 'pending', 'failed']),
                'transaction_date' => $faker->dateTimeThisYear(),
            ]);
        }
    }
}
