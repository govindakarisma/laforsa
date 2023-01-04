<h1 align="center"><b>Laravel Sanctum dan Fortify</b></h1>

# Install Segala Kebutuhan

Install depedensi yang dibutuhakan dalam pengembangan aplikasi seperti Laravel, Laravel Sanctum, Laravel Fortify DLL

## Laravel Framework

Disini akan menggunakan laravel versi 8 sekian

`composser create-project laravel/laravel:^8.0 api-toska`

## Laravel Fortify

Install package autentikasi Laravel Fortify, disini akan menggunakan versi 1.7

`composer require laravel/fortify:^1.7`

Selanjutnya lakukan perintah `php artisan vendor:publish` lalu pilih nomor list yang memiliki _namespace_ `Provider: Laravel\Fortify\ForifyServiceProvider` dan tekan enter atau run

## Laravel Sanctum

Install package laravel sanctum (Biasanya sudah terinstall otomatis saat install Laravel)

`composer require laravel/sanctum`

Selanjutnya lakukan perintah `php artisan vendor:publish` lalu pilih nomor list yang memiliki <i>namespace</i> `Provider: Laravel\Sanctum\SanctumServiceProvider` dan tekan enter atau run

## Migration

Tahap selanjutnya migrasi table ke database

-   Lakukan migration ke Database, pastikan telah membuat Database pada DBMS. Lalu atur nama Database pada file `.env`

-   Setelah Database dibuat dan nama database pada dile `.env` disesuaikan dengan nama Database pada DBMS. Lakukan _migration_ dengan memebuat perintah `php artisan migrate`

<hr>

# konfigurasi Fortify dan Sanctum

Disini akan melakukan _setup_ untuk mengimplementasikan **Sanctum** maupun **Fortify** sebagai API. Ada beberapa hal yang perlu di modifikasi atau disesuaikan agar nantinya aplikasi backend saling terhubung dengan frontend.

-   Pertama silahkan masukkan sanctum middleware `\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class` kedalam `app/Http/Kernel.php` tepat pada `api` key.

```php
'api' => [
  \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
  'throttle:api',
  \Illuminate\Routing\Middleware\SubstituteBindings::class,
]
```

-   Setelah itu pastikan `FortifyServiceProvider` telah terdaftar di `config/app.php`. Jika tidak terdaftar maka sistem tidak akan mengenalinya.

```php
  'providers' => [
    /*
     * Application Service Providers...
     */
    App\Providers\FortifyServiceProvider::class,
  ]
```

-   Masih pada file `config/app.php` kita akan menambahkan **SPA URL** yang secara default tidak ada pada halaman tersebut. Kita akan menambah `spa_url` tepat dibawah `asset_url`.

```php
  'asset_url' => env('ASSET_URL', null),
  'spa_url' => env('SPA_URL', null),
```

-   Selanjutnya kita tambahkan juga `SPA_URL` pada file `.env` dengan url yang berisi alamat frontend `SPA_URL=http://localhost:3000`
