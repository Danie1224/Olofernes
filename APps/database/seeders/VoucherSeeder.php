<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Keep a small set of generic vouchers available; user-specific
        // vouchers are created at login/claim and should not be seeded here.
        foreach ([10, 20, 30] as $percent) {
            Voucher::firstOrCreate(
                ['code' => 'GEN-'.$percent],
                [
                    'description' => 'Generic '.$percent.'% off voucher (sample)',
                    'discount_type' => 'percentage',
                    'discount_value' => $percent,
                    'start_date' => now(),
                    'end_date' => now()->addYears(1),
                    'usage_limit' => 100,
                    'usage_count' => 0,
                    'status' => 'active',
                ]
            );
        }
    }
}
