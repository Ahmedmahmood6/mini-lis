<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@minilis.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '01000000001',
            ]
        );

        User::updateOrCreate(
            ['email' => 'receptionist@minilis.com'],
            [
                'name' => 'Sarah Receptionist',
                'password' => Hash::make('password'),
                'role' => 'receptionist',
                'phone' => '01100000002',
            ]
        );

        User::updateOrCreate(
            ['email' => 'technician@minilis.com'],
            [
                'name' => 'Dr. Ahmed Technician',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'phone' => '01200000003',
            ]
        );
    }
}
