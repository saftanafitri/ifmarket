<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => '130456',
                'password' => Hash::make('password123'), // Pastikan password terenkripsi
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => '137921',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
