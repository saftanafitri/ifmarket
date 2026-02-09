<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->delete();

        $category = [
            [
                'name'=> 'Kerja Praktik(KP)'
            ],
            [
                'name'=> 'Tugas Akhir(TA)'
            ],
            [
                'name'=> 'Tugas Kuliah'
            ],
            [
                'name'=> 'Penelitian'
            ],
            [
                'name'=> 'Pengabdian Masyarakat'
            ],

        ];

        category::insert($category);
    }
}
