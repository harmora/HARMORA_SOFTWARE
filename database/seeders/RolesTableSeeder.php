<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rolesAuth')->insert([
            ['rolename' => 'admin', 'description' => 'Administrator role with full access'],
            ['rolename' => 'user', 'description' => 'user role with its Entreprise access'],
            ['rolename' => 'underuser', 'description' => 'under user that works for an entreprise'],

        ]);
    }
}
