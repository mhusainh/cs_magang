# CS Magang - Aplikasi Laravel

Aplikasi web berbasis Laravel untuk manajemen magang dan PPDB (Penerimaan Peserta Didik Baru).

## Prasyarat

Sebelum mengkloning dan menjalankan proyek ini, pastikan sistem Anda telah memenuhi persyaratan berikut:

-   PHP >= 8.1
-   Composer
-   MySQL/MariaDB

## Langkah Instalasi

1. Clone repositori ini:

```bash
git clone [url-repositori]
cd cs_magang
```

2. Install dependensi PHP:

```bash
composer install
```

3. Salin file .env.example menjadi .env:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Konfigurasi database di file .env:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

7. Jalankan migrasi database:

```bash
php artisan migrate
```

8. Generate JWT secret key:

```bash
php artisan jwt:secret
```

9. Jalankan development server:

```bash
php artisan serve
```

## Fitur

-   Autentikasi pengguna dengan JWT
-   Manajemen data peserta PPDB
-   Pengelolaan biodata orang tua
-   Manajemen jurusan
-   Sistem pembayaran dan tagihan
-   Upload dan manajemen berkas
-   Tracking progress pengguna
-   API Response yang terstruktur

## Struktur Proyek

-   `app/` - Berisi logika utama aplikasi
    -   `Controllers/` - Controller untuk menangani request
    -   `Models/` - Model Eloquent untuk interaksi database
    -   `Services/` - Business logic layer
    -   `Repositories/` - Data access layer
    -   `DTO/` - Data Transfer Objects
    -   `Requests/` - Form request untuk validasi input data
    -   `Traits/` - Reusable traits seperti ApiResponse

## Teknologi yang Digunakan

-   Laravel 10.x
-   MySQL/MariaDB
-   JWT untuk autentikasi

## Kontribusi

Untuk berkontribusi pada proyek ini:

1. Fork repositori
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -am 'Menambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## Lisensi

Proyek ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
