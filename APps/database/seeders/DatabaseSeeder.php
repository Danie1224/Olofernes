<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $this->call([
            AdminsTableSeeder::class,    // Create admins first
            BrandsTableSeeder::class,    // Then brands
            CustomersTableSeeder::class, // inserts string customer_id
            ProductsTableSeeder::class,  // Depends on admins and brands
            OrdersTableSeeder::class,    // depends on customers, products
            OrderItemsTableSeeder::class,
            ShippingsTableSeeder::class,
            PaymentsTableSeeder::class,
            VoucherSeeder::class,
            ReturnsTableSeeder::class,   // Create return requests
            ReturnRequestItemsTableSeeder::class, // Create return request items
            // Note: user-specific vouchers are created on login/claim; no seeder.
        ]);
    }
}

