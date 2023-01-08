<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'username' => 'admin01',
                'email' => 'admin@mail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active'
            ],
            [
                'name' => 'Kelly Price',
                'username' => 'vendor01',
                'email' => 'vendor@mail.com',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'status' => 'active'
            ],
            [
                'name' => 'John Doe',
                'username' => 'user01',
                'email' => 'john@mail.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active'
            ]
        ]);
    }
}
