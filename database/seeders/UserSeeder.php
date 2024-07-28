<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = ['Sarl', 'Sarl Au', 'Sa', 'Snc', 'Scs'];

        foreach ($labels as $label) {
            DB::table('forme_juridiques')->insert([
                'label' => $label,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('entreprises')->insert([
            [
                'denomination' => 'Entreprise A',
                'forme_juridique_id' => 1,
                'ICE' => 1234567890123,
                'IF' => 1234567890,
                'RC' => 123456,
                'address' => '1234 Main St',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'denomination' => 'Entreprise B',
                'forme_juridique_id' => 2,
                'ICE' => 9876543210987,
                'IF' => 9876543210,
                'RC' => 654321,
                'address' => '5678 Second St',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ]);


           DB::table('users')->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'country_code' => 'US',
                'phone' => '1234567890',
                'email' => 'tst2@gmail.com',
                'address' => '1234 Elm St',
                'city' => 'Springfield',
                'state' => 'IL',
                'country' => 'USA',
                'zip' => '62704',
                'password' => Hash::make('admin123'),
                'dob' => '1990-01-01',
                'doj' => '2020-01-01',
                'photo' => 'johndoe.jpg',
                'avatar' => 'avatar.png',
                'active_status' => 1,
                'dark_mode' => 0,
                'messenger_color' => 'blue',
                'lang' => 'en',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'status' => 1,
                'entreprise_id' => 1,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'country_code' => 'US',
                'phone' => '0987654321',
                'email' => 'test@gmail.com',
                'address' => '5678 Oak St',
                'city' => 'Springfield',
                'state' => 'IL',
                'country' => 'USA',
                'zip' => '62705',
                'password' => Hash::make('test123'),
                'dob' => '1992-02-02',
                'doj' => '2021-02-01',
                'photo' => 'janedoe.jpg',
                'avatar' => 'avatar.png',
                'active_status' => 1,
                'dark_mode' => 0,
                'messenger_color' => 'green',
                'lang' => 'en',
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'status' => 1,
                'entreprise_id' => 2,
            ],
            // Add more entries as needed
        ]);



    }
}
