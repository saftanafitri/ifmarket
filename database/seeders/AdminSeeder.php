<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'Sakti Produk',
            'password' => 'SaktiProduct705', 
            'role_id' => 1, // Role 1 untuk admin
        ]);
    }
}
