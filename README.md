# Sistem Informasi Disporabudpar Kabupaten Jember

Website profil dan informasi **Dinas Kepemudaan, Olahraga, Kebudayaan, dan Pariwisata (Disporabudpar) Kabupaten Jember**, lengkap dengan panel admin untuk mengelola destinasi wisata dan berita. Dibangun dengan **Laravel 13** dan **Livewire 4**.

## Fitur

**Situs publik**

- Beranda dengan slider, destinasi unggulan, dan berita terbaru
- Peta wisata interaktif (**Leaflet + OpenStreetMap**) dengan penanda per destinasi dan filter kategori
- Daftar berita dengan filter kategori dan paginasi, serta halaman detail berita
- Mode terang/gelap

**Panel admin** (`/admin`)

- Login dengan peran `admin` dan `editor`, serta status akun aktif/nonaktif
- Dashboard ringkasan (jumlah destinasi, destinasi unggulan, berita terbit, draf)
- Kelola destinasi: tambah, ubah, hapus (soft delete), tandai unggulan, unggah gambar
- Kelola berita: tulis, ubah, hapus (soft delete), status draf/terbit, unggah gambar sampul

**Dalam pengembangan** (tabel basis datanya sudah ada, halamannya belum): kunjungan wisata, kunjungan hotel, data kesenian, data ekonomi kreatif, profil, struktur organisasi, dan hubungi kami.

## Kebutuhan sistem

| Kebutuhan | Versi | Keterangan |
|---|---|---|
| **PHP** | **8.3 atau lebih baru** | Dikembangkan dengan PHP 8.5 |
| **Laravel** | **^13.17** | Terpasang otomatis lewat Composer (dikembangkan dengan 13.38) |
| **Livewire** | **^4.4** | Terpasang otomatis lewat Composer |
| **MySQL** | **8.0 atau lebih baru** | Migrasi memakai indeks FULLTEXT dan constraint CHECK. MariaDB belum diuji |
| **Composer** | 2.x | |
| **Git** | | |
| Node.js + npm | 20.19+ atau 22.12+ | **Opsional.** Halaman memakai CSS statis di `public/css`, jadi aplikasi jalan tanpa `npm` |

Ekstensi PHP yang harus aktif: `ctype`, `curl`, `dom`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pcre`, `pdo`, `pdo_mysql`, `session`, `tokenizer`, `xml`.

Peta dan font dimuat dari CDN (Leaflet dari cdnjs, tile dari OpenStreetMap, font dari Google Fonts), jadi **perlu koneksi internet** saat membuka situs.

Di Windows, paling mudah memakai [Laragon](https://laragon.org/) atau XAMPP, yang sudah menyertakan PHP, MySQL, dan Composer.

## Instalasi

### 1. Ambil kode

```bash
git clone https://github.com/USERNAME/NAMA-REPO.git
cd NAMA-REPO
```

### 2. Pasang dependensi PHP

```bash
composer install
```

### 3. Siapkan file `.env`

```bash
# Linux / macOS
cp .env.example .env

# Windows (CMD)
copy .env.example .env
```

Lalu buat application key:

```bash
php artisan key:generate
```

### 4. Buat database

Buat database kosong bernama `disporabudpar` (lewat phpMyAdmin, HeidiSQL, atau terminal):

```sql
CREATE DATABASE disporabudpar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Atur `.env`

Buka `.env` dan sesuaikan bagian ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=disporabudpar
DB_USERNAME=root
DB_PASSWORD=

# Akun admin pertama (WAJIB diisi sebelum langkah 6)
ADMIN_NAME="Administrator"
ADMIN_EMAIL=admin@disporabudpar.test
ADMIN_PASSWORD=isi_password_yang_kuat
```

> Kalau `ADMIN_PASSWORD` kosong, akun admin **tidak** dibuat.

### 6. Migrasi dan isi data awal

```bash
php artisan migrate --seed
```

Perintah ini membuat semua tabel, lalu mengisi data awal: akun admin, daftar kecamatan, kategori, bidang, halaman statis, dan kontak instansi.

### 7. Tautkan folder penyimpanan gambar

```bash
php artisan storage:link
```

Langkah ini **wajib**, kalau tidak gambar destinasi dan berita yang diunggah lewat admin tidak akan tampil.

### 8. Jalankan

```bash
php artisan serve
```

Buka <http://127.0.0.1:8000>. Alamat ini otomatis diarahkan ke `/publik/beranda`.

## Data contoh (opsional)

Supaya beranda dan peta tidak kosong saat mencoba:

```bash
php artisan db:seed --class=DestinasiSeeder
php artisan db:seed --class=BeritaSeeder
```

Jalankan **setelah** langkah 6, dan cukup sekali. Semua data contoh bisa diubah atau dihapus lewat panel admin.

## Alamat penting

| Alamat | Keterangan |
|---|---|
| `/publik/beranda` | Beranda |
| `/publik/peta-wisata` | Peta wisata (Leaflet) |
| `/publik/berita` | Daftar berita |
| `/publik/berita/{slug}` | Detail berita |
| `/admin/login` | Login admin |
| `/admin/dashboard` | Dashboard admin |
| `/admin/destinasi` | Kelola destinasi |
| `/admin/berita` | Kelola berita |

Masuk ke `/admin/login` dengan `ADMIN_EMAIL` dan `ADMIN_PASSWORD` yang Anda isi di `.env`.

## Struktur basis data

22 tabel domain dan 17 relasi. Diagram lengkap (dengan semua kolom) ada di [`docs/ERD-Disporabudpar.png`](docs/ERD-Disporabudpar.png), dan versi vektornya di [`docs/ERD-Disporabudpar.svg`](docs/ERD-Disporabudpar.svg).

![ERD](docs/ERD-Disporabudpar.png)

## Struktur folder penting

```
app/
  Livewire/Admin/        Halaman panel admin (Dashboard, Destinasi, Berita, ...)
  Livewire/Publik/       Halaman situs publik (Beranda, PetaWisata, Berita, ...)
  Models/                Model Eloquent
  Http/Middleware/       PastikanAdminAktif (hanya akun aktif yang boleh masuk admin)
database/
  migrations/            Skema basis data
  seeders/               Data awal dan data contoh
public/css/              publik.css dan admin.css
resources/views/
  layouts/               publik.blade.php, admin.blade.php, admin-login.blade.php
  livewire/              Tampilan tiap halaman
routes/web.php           Semua route
```

## Pengembangan

```bash
php artisan test                # jalankan test
./vendor/bin/pint               # rapikan gaya kode
php artisan optimize:clear      # bersihkan cache (config, route, view)
```

## Deploy ke server (produksi)

Di `.env` server:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.go.id
```

Lalu:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

Arahkan *document root* web server ke folder `public/`. Jangan pernah mengunggah file `.env` ke GitHub, dan **ganti password admin** setelah login pertama.

## Masalah umum

| Pesan error | Penyebab dan solusi |
|---|---|
| `No application encryption key has been specified` | Jalankan `php artisan key:generate` |
| `SQLSTATE[HY000] [1049] Unknown database` | Database belum dibuat atau nama di `DB_DATABASE` salah (langkah 4 dan 5) |
| `SQLSTATE[HY000] [1045] Access denied` | `DB_USERNAME` atau `DB_PASSWORD` salah |
| `Table 'sessions' doesn't exist` | Migrasi belum dijalankan: `php artisan migrate` |
| Tidak bisa login admin | `ADMIN_PASSWORD` kosong saat seeding. Isi di `.env`, lalu `php artisan db:seed --class=AdminSeeder` |
| Gambar yang diunggah tidak tampil | Jalankan `php artisan storage:link` |
| `Route [...] not defined` | Halaman tersebut belum dibuat. Lihat bagian "Dalam pengembangan" |
| Perubahan tampilan tidak muncul | `php artisan optimize:clear` |

## Lisensi

Dibangun di atas [Laravel](https://laravel.com), yang berlisensi [MIT](https://opensource.org/licenses/MIT).
