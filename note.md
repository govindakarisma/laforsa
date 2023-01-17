## Mengatasi **CSRF token mismatch**

```json
{
    "message": "CSRF token mismatch.",
    "exception": "Symfony\\Component\\HttpKernel\\Exception\\HttpException",
    "file": "D:\\Programming\\Practice\\Laravel\\Parsinta\\api-toska\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Exceptions\\Handler.php",
    "line": 389
}
```

Hal ini terjadi karena setiap route api memerlukan CSRF Token, biasanya hanya terjadi pada GUI seperti Postman, Insomnial dll. sementara kita atasi dengan cara membuat pengecualian route yang tidak perlu menggunakan Token, karena pada react itu tidak perlu dilakukan. Hal yang perlu dilakukan adalah mengKonfigurasi route yang tidak memerlukan verifikasi csrf token pada file `app\Http\Middleware\VerifyCsrfToken.php` lalu tambahkan route yang ingin diakses dengan tanpa verifikasi CSRF token seperti gambar dibawah ini.

```php
protected $except = [
  '/login', 'logout'
];
```

## Menghapus GIT

```
rm -rf .git
```

 <hr>

## Mengubah root url akses API

Secara default untuk akses url API setelah domain akan ditambah `/api` contohnya `http://127.0.0.1:8000/api/data`. Untuk mengubahnya misalnya mengganti dengan `/awokwokwok` dapat dilakukan perubahan pada file `app/Providers/RouteServiceProvider.php`.

```php
 public function boot()
 {
     $this->configureRateLimiting();

     $this->routes(function () {
         Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));

         Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
     });
 }
```

Ubah value pada `Route::prefix('api')` menjadi nama yang diinginkan, misal `Route::prefix('awokwokwok')`

```php
 public function boot()
 {
     $this->configureRateLimiting();

     $this->routes(function () {
         Route::prefix('awokwokwok')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));

         Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
     });
 }
```

Jika prefix defaultnya diubah, ada 1 hal yang harus diperhatikan yaitu mengubah paths yang terdapat pada `config/cors.php` sesuai dengan nama yang telah diganti

```php
'paths' => [
    /** Sebelum */
    'api/*',
    /** Setelah */
    'awokwokwok/*',
    'login',
    'logout',
    'register',
    'user/password',
    'forgot-password',
    'reset-password',
    'sanctum/csrf-cookie',
    'user/profile-information',
    'email/verification-notification',
],
```

## Membuat Global Function (Laravel)

Jika ingin membuat function sendiri dan akan digunakan secara global, bisa dengan menggunakan _Helper Function_. Buatlah sebuah file `php` didalam folder `app` sebut saja nama filenya `helpers.php`, lalu tulis function yg ingin dibuat.

```php
    <?php

    functtion formatPrice($string)
    {
        return str_replace(',', '.', number_format($string));
    }
```

Function tersebut belum bisa digunakan, agar bisa digunakan daftarkan terlebih dahulu pada `composer.json` dengan memasukkan code berikut.

```json
{
    "files": ["app/helpers.php"]
}
```

Masukkan code tersebut kedalam objek autoload.

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        },
        // Dibawah objek autoload
        "files": ["app/helpers.php"]
    }
}
```

Lalu buka terminal dengan memasukkan perintah berikut, dan selesai.

```
composer dump-autoload
```
