<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReturnRequestItem;
use App\Models\ReturnRequest;
use App\Models\OrderItem;
use App\Models\Product;
use Faker\Factory as Faker;

class ReturnRequestItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_US');

        // Get all return requests
        $returnRequests = ReturnRequest::all();

        if ($returnRequests->isEmpty()) {
            $this->command->warn('⚠️ Skipped ReturnRequestItems seeding — no return requests found.');
            return;
        }

        $reasons = ['damaged', 'defective', 'wrong_item', 'not_as_described', 'other'];
        $totalItemsSeeded = 0;

        // For each return request, create 1-3 return items
        foreach ($returnRequests as $returnRequest) {
            // Get order items for this order
            $orderItems = OrderItem::where('order_id', $returnRequest->order_id)->get();

            if ($orderItems->isEmpty()) {
                continue; // Skip if no order items
            }

            // Create 1-3 items for this return request
            $numItems = $faker->numberBetween(1, min(3, $orderItems->count()));
            $selectedItems = $orderItems->random($numItems);

            foreach ($selectedItems as $orderItem) {
                $returnQuantity = $faker->numberBetween(1, $orderItem->quantity);
                $refundAmount = $orderItem->unit_price * $returnQuantity;

                ReturnRequestItem::create([
                    'request_id' => $returnRequest->request_id,
                    'order_item_id' => $orderItem->order_item_id,
                    'product_id' => $orderItem->product_id,
                    'quantity' => $returnQuantity,
                    'refund_amount' => $refundAmount,
                    'status' => $returnRequest->status,
                    'notes' => $faker->optional(0.6)->sentence(8),
                    'requested_at' => $returnRequest->created_at,
                ]);

                $totalItemsSeeded++;
            }
        }

        $this->command->info("✅ ReturnRequestItems seeded successfully! ($totalItemsSeeded items created)");
    }
}
