<?php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'first_name' => 'superadmin',
            'last_name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => '2022-01-02 17:04:58',
            'avatar' => 'avatar-1.jpg',
            'id_no' => 358230097,
            'phone_no' => '0781829338',
            'role_id' => 1,
            'sub_county_id' => 1,
            'ward_id' => 1,
            'gender'=>'M',
            'created_at' => now(),
         
        ]);
       
        User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => '2022-01-02 17:04:58',
            'avatar' => 'avatar-1.jpg',
            'id_no' => 35810097,
            'phone_no' => '0791181728',
            'role_id' => 2,
            'sub_county_id' => 1,
            'ward_id' => 1,
            'gender'=>'M',
            'created_at' => now(),
         
        ]);

        User::create(
        [
            'first_name' => 'chiefofficer',
            'last_name' => 'chiefofficer',
            'email' => 'chiefofficer@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => '2022-01-02 17:04:58',
            'avatar' => 'avatar-1.jpg',
            // Add the remaining fields here as well
            'id_no' => 358103098,
            'phone_no' => '0784949387',
            'role_id' => 3,
            'sub_county_id' => 1,
            'ward_id' => 1,
            'gender'=>'M',
            'created_at' => now(),
        ]);
    }
}



