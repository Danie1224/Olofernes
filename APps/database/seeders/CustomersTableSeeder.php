<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('customers')->insert([
                'customer_id' => strtoupper(Str::uuid()),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->streetAddress,
                'city' => $faker->city,
                'state' => $faker->state,
                'zip_code' => $faker->postcode,
                'country' => $faker->country,
                'date_of_birth' => $faker->date(),
                'gender' => $faker->randomElement(['male', 'female']),
                'status' => $faker->randomElement(['active', 'inactive', 'suspended']),
            ]);
        }
    }
}