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

        User::updateOrCreate(
            ['email' => 'mona.reception@minilis.com'],
            [
                'name' => 'Mona El-Sayed',
                'password' => Hash::make('password'),
                'role' => 'receptionist',
                'phone' => '01145678901',
            ]
        );

        User::updateOrCreate(
            ['email' => 'khaled.lab@minilis.com'],
            [
                'name' => 'Dr. Khaled Mahmoud',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'phone' => '01234567890',
            ]
        );

        User::updateOrCreate(
            ['email' => 'noha.reception@minilis.com'],
            [
                'name' => 'Noha Hassan',
                'password' => Hash::make('password'),
                'role' => 'receptionist',
                'phone' => '01012345678',
            ]
        );

        User::updateOrCreate(
            ['email' => 'youssef.lab@minilis.com'],
            [
                'name' => 'Dr. Youssef Ali',
                'password' => Hash::make('password'),
                'role' => 'technician',
                'phone' => '01598765432',
            ]
        );
    }
}
