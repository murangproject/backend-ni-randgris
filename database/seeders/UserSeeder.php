<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => env('INITIAL_USER_FIRST_NAME'),
            'middle_name' => env('INITIAL_USER_MIDDLE_NAME'),
            'last_name' => env('INITIAL_USER_LAST_NAME'),
            'email' => env('INITIAL_USER_EMAIL'),
            'role_type' => env('INITIAL_USER_ROLE_TYPE'),
            'password' => Hash::make(env('INITIAL_USER_PASSWORD'))
        ]);
    }
}
