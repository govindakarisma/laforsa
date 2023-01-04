<h1 align="center"><b>Laravel Sanctum dan Fortify</b></h1>

# Install Segala Kebutuhan

Install depedensi yang dibutuhakan dalam pengembangan apliaksi seperti Laravel, Laravel Sanctum, Laravel Fortify DLL

## Laravel Framework

Disini akan menggunakan laravel versi 8 sekian

`composser create-project laravel/laravel:^8.0 api-toska`

## Laravel Fortify

Install package autentikasi Laravel Fortify, disini akan menggunakan versi 1.7

`composer require laravel/fortify:^1.7`

Selanjutnya lakukan perintah `php artisan vendor:publish` lalu pilih nomor list yang memiliki <i>namespace</i> `Provider: Laravel\Fortify\ForifyServiceProvider` dan tekan enter atau run

## Laravel Sanctum

Install package laravel sanctum (Biasanya sudah terinstall otomatis saat install Laravel)

`composer require laravel/sanctum`

Selanjutnya lakukan perintah `php artisan vendor:publish` lalu pilih nomor list yang memiliki <i>namespace</i> `Provider: Laravel\Sanctum\SanctumServiceProvider` dan tekan enter atau run

## Migration

Tahap selanjutnya migrasi table ke database

-   Lakukan migration ke database, pastikan telah membuat database pada dbms. Lalu atur nama database pada file `.env`

-   Setelah Database dibuat dan nama database pada dile `.env` disesuaikan dengan nama database pada DBMS. Lakukan migration dengan memebuat perintah `php artisan migrate`

<hr>

# konfigurasi Fortify dan Sanctum

Disini akan melakukan _setup_ untuk mengimplementasikan **Sanctum** maupun **Fortify** sebagai API. Ada beberapa hal yang perlu di modifikasi atau disesuaikan agar nantinya aplikasi backend saling terhubung dengan frontend.

-   pertama silahkan masukkan sanctum middleware `\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class` kedalam `app/Http/Kernel.php` tepat pada `api` key.

```php
'api' => [
  \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
  'throttle:api',
  \Illuminate\Routing\Middleware\SubstituteBindings::class,
]
```
