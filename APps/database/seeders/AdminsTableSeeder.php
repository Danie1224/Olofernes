<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin
        Admin::firstOrCreate(
            ['email' => 'admin@techstore.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        // Create additional admin accounts
        Admin::firstOrCreate(
            ['email' => 'staff1@techstore.test'],
            [
                'name' => 'Admin Staff 1',
                'password' => Hash::make('staff123'),
                'role' => 'admin'
            ]
        );

        Admin::firstOrCreate(
            ['email' => 'staff2@techstore.test'],
            [
                'name' => 'Admin Staff 2',
                'password' => Hash::make('staff123'),
                'role' => 'admin'
            ]
        );
    }
}
