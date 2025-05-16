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

        //  User::create([
        //     'full_name' => 'Nabaa',
        //     'email' => 'Nabaaomar@gmail.com',
        //     'phone_number' => '0790001110',
        //     'password' => Hash::make('Nabaa@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);

        // User::create([
        //     'full_name' => 'Sara',
        //     'email' => 'saraali@gmail.com',
        //     'phone_number' => '0790000111',
        //     'password' => Hash::make('Sara@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);

        // User::create([
        //     'full_name' => 'Sanaa',
        //     'email' => 'Sana@gmail.com',
        //     'phone_number' => '0770000112',
        //     'password' => Hash::make('Sanaa@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);

        //  User::create([
        //     'full_name' => 'Marya',
        //     'email' => 'Marya@customer.com',
        //     'phone_number' => '0770000113',
        //     'password' => Hash::make('Maria@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);

        // User::create([
        //     'full_name' => 'Lamy',
        //     'email' => 'Lamy@gmail.com',
        //     'phone_number' => '0780000114',
        //     'password' => Hash::make('Lamy@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);

        // User::create([
        //     'full_name' => 'Ruba',
        //     'email' => 'Ruba@gmail.com',
        //     'phone_number' => '0780000115',
        //     'password' => Hash::make('Tariq@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);

        // User::create([
        //     'full_name' => 'Laila wesam',
        //     'email' => 'lailawewe@gmail.com',
        //     'phone_number' => '0780000116',
        //     'password' => Hash::make('Laila@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);

        // User::create([
        //     'full_name' => 'Yafa',
        //     'email' => 'Yafa@gmail.com',
        //     'phone_number' => '0780000117',
        //     'password' => Hash::make('Yafa@123'),
        //     'role' => 'customer',
        //     'status' => 'active',
        // ]);
    




        // Owners


        User::create([
            'full_name' => 'Sondos Tariq',
            'email' => 'SondostariqJ@gmail.com',
            'phone_number' => '0777000001',
            'password' => Hash::make('Sondos@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Baraa Tayseer',
            'email' => 'BaraaTayseerB@gmail.com',
            'phone_number' => '0799000002',
            'password' => Hash::make('Baraa@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Rafeef Khalid',
            'email' => 'RafeefKhalidm@gmail.com',
            'phone_number' => '0799000003',
            'password' => Hash::make('Rafeef@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

       
         User::create([
            'full_name' => 'Manal Khalil',
            'email' => 'Manalkhalil2@gmail.com',
            'phone_number' => '0797000019',
            'password' => Hash::make('Manal@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Hala Hassan',
            'email' => 'halahassan2@gmail.com',
            'phone_number' => '0797000004',
            'password' => Hash::make('Hala@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Maya Omar',
            'email' => 'mayaomar2@gmail.com',
            'phone_number' => '0799000005',
            'password' => Hash::make('Maya@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Massah Ali',
            'email' => 'Massah2@gmail.com',
            'phone_number' => '0799000006',
            'password' => Hash::make('Massah@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

        User::create([
            'full_name' => 'Bushra Hory',
            'email' => 'Bushra2@gmail.com',
            'phone_number' => '0799000007',
            'password' => Hash::make('Bushra@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Enas Ahmed',
            'email' => 'Enas2@gmail.com',
            'phone_number' => '0799000018',
            'password' => Hash::make('Enas@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Raghad Odeh',
            'email' => 'Raghad2@gmail.com',
            'phone_number' => '07990000020',
            'password' => Hash::make('Raghad@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Fatima Khalid',
            'email' => 'Fatima2@gmail.com',
            'phone_number' => '0799000021',
            'password' => Hash::make('Fatima@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Menna',
            'email' => 'Menna2@store.com',
            'phone_number' => '0799000022',
            'password' => Hash::make('Menna@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Rania',
            'email' => 'Rania2@gmail.com',
            'phone_number' => '0799000023',
            'password' => Hash::make('Rania@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Samaher',
            'email' => 'Samaher2@gmail.com',
            'phone_number' => '0799000024',
            'password' => Hash::make('12345678'),
            'role' => 'owner',
            'status' => 'active',
        ]);
        User::create([
            'full_name' => 'Shaden',
            'email' => 'Shaden2@gmail.com',
            'phone_number' => '0799000025',
            'password' => Hash::make('Shaden@123'),
            'role' => 'owner',
            'status' => 'active',
        ]);

       
    }

}