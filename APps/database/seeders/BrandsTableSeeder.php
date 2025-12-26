<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Define the tech brands
        $brands = [
            [
                'name' => 'Lenovo',
                'description' => 'Leading manufacturer of personal computers and IT solutions',
            ],
            [
                'name' => 'Acer',
                'description' => 'Global IT hardware manufacturer',
            ],
            [
                'name' => 'Dell',
                'description' => 'Premier technology company providing computing solutions',
            ],
            [
                'name' => 'Apple',
                'description' => 'Innovative technology company known for premium devices',
            ],
            [
                'name' => 'Asus',
                'description' => 'Leading provider of computer hardware and electronics',
            ],
        ];

        // Create each brand
        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['name' => $brand['name']],
                ['description' => $brand['description']]
            );
        }
    }
}
