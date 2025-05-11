<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'full_name' => 'Mona Handmade',
            'email' => 'mona@store.com',
            'phone_number' => '0780000001',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Noura Knits',
            'email' => 'noura@store.com',
            'phone_number' => '0780000002',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Layla Arts',
            'email' => 'layla@store.com',
            'phone_number' => '0780000003',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        // العملاء
        User::create([
            'full_name' => 'Ahmed Buyer',
            'email' => 'ahmed@customer.com',
            'phone_number' => '0780000010',
            'password' => Hash::make('12345678'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Sara Shopper',
            'email' => 'sara@customer.com',
            'phone_number' => '0780000011',
            'password' => Hash::make('12345678'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Omar Orders',
            'email' => 'omar@customer.com',
            'phone_number' => '0780000012',
            'password' => Hash::make('12345678'),
            'role' => 'customer',
            'status' => 'active',
        ]);
    }
}

