<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {

        // Customers 

         User::create([
            'full_name' => 'Nabaa',
            'email' => 'Nabaa@gmail.com',
            'phone_number' => '0780000010',
            'password' => Hash::make('Nabaa@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Sara',
            'email' => 'sara@gmail.com',
            'phone_number' => '0780000011',
            'password' => Hash::make('Sara@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Sanaa',
            'email' => 'Sanaa@gmail.com',
            'phone_number' => '0780000012',
            'password' => Hash::make('Sanaa@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

         User::create([
            'full_name' => 'Maria',
            'email' => 'omar@customer.com',
            'phone_number' => '0780000013',
            'password' => Hash::make('Maria@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Lamy',
            'email' => 'Lamy@gmail.com',
            'phone_number' => '0780000014',
            'password' => Hash::make('Lamy@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Ruba',
            'email' => 'Ruba@gmail.com',
            'phone_number' => '0780000015',
            'password' => Hash::make('Tariq@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Ruba',
            'email' => 'laila@gmail.com',
            'phone_number' => '0780000016',
            'password' => Hash::make('Laila@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Yafa',
            'email' => 'Yafa@gmail.com',
            'phone_number' => '0780000017',
            'password' => Hash::make('Yafa@123'),
            'role' => 'customer',
            'status' => 'active',
        ]);
    




        // Owners


        User::create([
            'full_name' => 'Sondos Tariq',
            'email' => 'Sondostariq@gmail.com',
            'phone_number' => '0780000001',
            'password' => Hash::make('Sondos@123'),
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

       
         User::create([
            'full_name' => 'Lina',
            'email' => 'lina@store.com',
            'phone_number' => '0780000003',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Hana',
            'email' => 'hana@store.com',
            'phone_number' => '0780000004',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Maya',
            'email' => 'maya@store.com',
            'phone_number' => '0780000005',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Nina',
            'email' => 'nina@store.com',
            'phone_number' => '0780000006',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rita',
            'email' => 'rita@store.com',
            'phone_number' => '0780000007',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);

       
    }

}