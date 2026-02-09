<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Photo; // <-- Tambahkan model Photo
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan fasad Storage
use Illuminate\Support\Facades\File;    // <-- Tambahkan fasad File

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Mohon jalankan seeder untuk User dan Category terlebih dahulu.');
            return;
        }

        // --- LOGIKA BARU UNTUK GAMBAR ---
        // 1. Tentukan folder sumber gambar contoh
        $sourceFolder = storage_path('app/seed-images');

        // 2. Ambil daftar semua file gambar dari folder sumber
        if (!File::exists($sourceFolder) || empty(File::files($sourceFolder))) {
            $this->command->error('Folder seed-images tidak ditemukan atau kosong. Mohon buat folder di storage/app/seed-images dan isi dengan beberapa gambar.');
            return;
        }
        $sourceFiles = File::files($sourceFolder);

        // 3. Bersihkan direktori tujuan sebelum memulai seeder
        $destinationFolder = 'products';
        Storage::disk('public')->deleteDirectory($destinationFolder);
        Storage::disk('public')->makeDirectory($destinationFolder);
        // --- AKHIR LOGIKA BARU ---


        // Loop untuk membuat 50 produk
        foreach (range(1, 150) as $index) {
            $sellerNames = [];
            for ($i = 0; $i < $faker->numberBetween(1, 3); $i++) {
                $sellerNames[] = $faker->name;
            }

            $productName = $faker->sentence(3);
            $product = Product::create([
                'name'          => $productName,
                'slug'          => Str::slug($productName) . '-' . uniqid(),
                'description'   => $faker->paragraph(5),
                'category_id'   => $categories->random()->id,
                'user_id'       => $users->random()->id,
                'seller_name'   => $sellerNames[0],
                'video'         => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'email'         => $faker->safeEmail,
                'instagram'     => 'https://instagram.com/' . $faker->userName,
                'linkedin'      => 'https://linkedin.com/in/' . $faker->userName,
                'github'        => 'https://github.com/' . $faker->userName,
                'product_link'  => $faker->url,
                'status' => $faker->randomElement(['approved', 'pending', 'rejected']),
            ]);

            foreach ($sellerNames as $name) {
                Seller::create([
                    'product_id' => $product->id,
                    'name'       => $name,
                ]);
            }

            // --- LOGIKA BARU UNTUK MEMBUAT & MENYIMPAN FOTO ---
            // 4. Buat 2 sampai 5 foto untuk setiap produk
            $photoCount = $faker->numberBetween(2, 5);
            for ($i = 0; $i < $photoCount; $i++) {
                // Pilih gambar acak dari folder sumber
                $randomFile = $faker->randomElement($sourceFiles);

                // Buat nama file baru yang unik
                $newFileName = uniqid() . '.' . $randomFile->getExtension();

                // Salin file dari sumber ke direktori tujuan (public/storage/products)
                Storage::disk('public')->put($destinationFolder . '/' . $newFileName, File::get($randomFile));

                // Simpan path ke database
                Photo::create([
                    'product_id' => $product->id,
                    'url'        => $destinationFolder . '/' . $newFileName,
                ]);
            }
            // --- AKHIR LOGIKA BARU ---
        }
    }
}