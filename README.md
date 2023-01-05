<h1 align="center"><b>Laravel Sanctum dan Fortify</b></h1>

# Install Segala Kebutuhan

Install depedensi yang dibutuhakan dalam pengembangan aplikasi seperti Laravel, Laravel Sanctum, Laravel Fortify DLL

## Laravel Framework

Aplikasi ini menggunakan laravel versi 8

```
composer create-project laravel/laravel:^8.0 api-toska
```

## Laravel Fortify

Install package Laravel Fortify, disini akan menggunakan versi 1.7

```
composer require laravel/fortify:^1.7
```

Selanjutnya masukkan perintah berikut pada folder projek aplikasi dengan _Command Line_

```
php artisan vendor:publish
```

lalu pilih nomor list yang memiliki _namespace_ `Provider: Laravel\Fortify\ForifyServiceProvider` dan tekan enter atau run

## Laravel Sanctum

Install package Laravel Sanctum (Biasanya sudah terinstall otomatis saat instalasi Laravel)

```
composer require laravel/sanctum
```

Selanjutnya lakukan perintah sama seperti yang dilakukan sebelumnya yaitu

```
php artisan vendor:publish
```

lalu pilih nomor list yang memiliki _namespace_ `Provider: Laravel\Sanctum\SanctumServiceProvider` dan tekan enter atau run.

## Migration

Tahap selanjutnya migrasi table ke Database

-   Lakukan _migration_ ke Database, pastikan telah membuat Database pada DBMS. Lalu atur nama Database pada file `.env`

-   Setelah Database dibuat dan nama database pada dile `.env` disesuaikan dengan nama Database pada DBMS. Lakukan _migration_ dengan memebuat perintah _Command Line_

```
php artisan migrate
```

<hr>

# konfigurasi Fortify dan Sanctum

Disini akan melakukan _setup_ untuk mengimplementasikan **Sanctum** maupun **Fortify** sebagai API. Ada beberapa hal yang perlu di modifikasi atau disesuaikan agar nantinya aplikasi backend saling terhubung dengan frontend.

Pertama silahkan masukkan sanctum middleware kedalam `app/Http/Kernel.php` tepat pada `api` key.

```php
\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class
```

Masukkan _namespace_ diatas pada atribut api seperti dibawah.

```php
'api' => [
  \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
  'throttle:api',
  \Illuminate\Routing\Middleware\SubstituteBindings::class,
]
```

Selanjutnya daftarkan juga `FortifyServiceProvider` pada file `config/app.php`. Jika tidak terdaftar maka sistem tidak akan mengenalinya.

```php
App\Providers\FortifyServiceProvider::class
```

Masukkan _namespace_ diatas pada atribut api seperti dibawah.

```php
  'providers' => [
    /*
     * Application Service Providers...
     */
    App\Providers\FortifyServiceProvider::class,
  ]
```

Masih pada file `config/app.php` kita akan menambahkan **SPA URL** yang secara default tidak ada pada halaman tersebut. Kita akan menambah `spa_url` tepat dibawah `asset_url`.

```php
  'asset_url' => env('ASSET_URL', null),
  'spa_url' => env('SPA_URL', null),
```

Selanjutnya kita tambahkan juga `SPA_URL` pada file `.env` dengan url yang berisi alamat frontend

```
SPA_URL=http://localhost:3000
```

Lanjut untuk mengatur _Session Cookie Domain_ yang terdapat pada file `config/session.php` pada objek `'domain' => env('SESSION_DOMAIN', null)`. karna session domain akan membaca file `.env` maka kita cukup menambahkan session domainnya pada file tersebut. Buka file `.env` lalu masukkan atribut

```
SESSION_DOMAIN=localhost
```

Oke next lakukan pengaturan untuk Frontend dengan membuka file `config/sanctum.php`.

```php
  'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),
```

Secara default _sanctum stateful domain_ telah menyediakan domain untuk _development_. Tetapi untuk memastikan, kita masukkan saja konfigurasi atau atributnya kedalam file `.env` saja dengan membuat atribut

```
SANCTUM_STATEFUL_DOMAIN=localhost:3000
```

Lalu tahap berikutnya lakukan pengaturan _**CORS**_ agar _frontend_ bisa mengakses _backend_. buka dile `config/cors.php` lalu atur path dengan menambahkan semua url yang dibutuhkan.

```php
  'paths' => [
    'api/*',
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

Masih pada file yang sama, terdapat atribut `'support_credential' => false` yang secara default bernilai `false`. jika tidak diizinkan credential dari cookie yang ada di backend maka akan gagal, maka pastikan bernilai `true` agar frontend aplikasi bisa menggunakan cookie dari Backend

Next, setup _Fortify_ itu sendiri pada file `config/fortify.php` untuk membuat default redirect dengan memasukkan url spa yang sudah dilakukan setup pada file `.env` seperti berikut.

```php
  /** Kondisi awal */
  'home' => RouteServiceProvider::HOME,
  /** Ubah menjadi */
  'home' => env('SPA_URL'.'/dashboard')
  /** Atau */
  'home' => config('app.spa_url'),
```

Masih pada file yang sama, kita akan mengatur _view_. view akan dieksekusi saat bernilai `true`. Ubah menjadi `false` karena kita hanya menggunakan ini sebagai backend maka tidak memerlukan view.

```php
  'views' => false
```

Lanjuttt, pergi ke file `app\Http\Middleware\Authenticate.php`

```php
  protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
```

Pada file tersebut terdapat fungsi pengecekan autentikasi. Jika user mengakses url yang memerlukan autentikasi namun belum melakukan login, maka akan dilakukan penolakan. Secara deafult akan mengembalikan user ke halaman login `http://localhost:8000/login`. Lakukan modifikasi karna kita akan melakukan redirect ke halaman Frontend.

```php
  protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return config('app.spa_url') . '/login';
        }
    }
```

Selanjutnya, jika user sudah terautentikasi, jangan lagi melakukan login. Pada tahap ini dapat diatur pada file `app\Http\Middleware\RedirectIfAuthenticated.php`

```php
  public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /** Masukkan script pengkondisian dibawah ini */
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'You are already authenticated'], 200);
                }
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
```

Sampai disini telah dilakukan segala konfigurasi kebutuhan Banckend dan Frontend, serta dapat dipastikan aplikasi akan saling terintegrasi.
