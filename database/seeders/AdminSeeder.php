<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'admin'], // Admin default
            [
                'password' => Hash::make('admin123'), // Ubah sesuai kebutuhan
                'role' => 'admin'
            ]
        );
    }
}


