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
## NB: Deployment Notes

- Pastikan direktori `storage/` dan `bootstrap/cache/` writable oleh web server.
- Aplikasi memerlukan penyimpanan eksternal untuk foto produk.
