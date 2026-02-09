# Sakti Produk

Sakti Produk adalah aplikasi berbasis Laravel yang digunakan untuk menampilkan dan mengelola produk hasil kegiatan akademik seperti Kerja Praktik, Pengabdian kepada Masyarakat, dan Tugas Akhir.

## Instalasi dan Konfigurasi
```bash
copy .env.example .env
composer install
composer require laravel/sanctum
composer require intervention/image:^2.7
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan key:generate
php artisan route:clear
php artisan optimize:clear
php artisan storage:link
```
- NB: Memerlukan penyimpanan eksternal untuk menyimpan foto-foto produk
