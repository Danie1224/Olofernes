<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample customers without faker
        $customers = [
            [
                'customer_id' => Str::uuid(),
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '555-0101',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip_code' => '10001',
                'country' => 'USA',
                'date_of_birth' => '1990-01-15',
                'gender' => 'male',
                'status' => 'active',
            ],
            [
                'customer_id' => Str::uuid(),
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '555-0102',
                'address' => '456 Oak Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'zip_code' => '90001',
                'country' => 'USA',
                'date_of_birth' => '1985-06-20',
                'gender' => 'female',
                'status' => 'active',
            ],
            [
                'customer_id' => Str::uuid(),
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'phone' => '555-0103',
                'address' => '789 Pine Rd',
                'city' => 'Chicago',
                'state' => 'IL',
                'zip_code' => '60601',
                'country' => 'USA',
                'date_of_birth' => '1992-11-30',
                'gender' => 'male',
                'status' => 'active',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
