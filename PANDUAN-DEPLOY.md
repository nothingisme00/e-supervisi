# Panduan Deploy — E-Supervisi

Panduan langkah demi langkah untuk memasang aplikasi ke server produksi.
Bagian A = shared hosting cPanel (rekomendasi, paling murah & umum di Indonesia).
Bagian B = ringkasan untuk VPS / Railway. Bagian C = checklist final.

Stack: **Laravel 12 + Livewire 3 + Tailwind 4 (Vite) + MySQL**.

> Fakta penting yang menyederhanakan deploy:
> - **Tidak ada job yang di-queue** → tidak perlu menjalankan queue worker. Cukup `QUEUE_CONNECTION=sync`.
> - **Satu-satunya proses terjadwal** = pengingat supervisi (Senin & Kamis 07:00) → cukup 1 cron.
> - `public/.htaccess` sudah tersedia untuk Apache/cPanel.

---

## Ringkasan alur

1. Build aset di komputer lokal (`npm run build`).
2. Upload seluruh project ke server.
3. Siapkan `.env` produksi + database.
4. Jalankan migrasi + seeder + `storage:link`.
5. Optimize (`config:cache`, dll) **di server**, bukan di lokal.
6. Arahkan document root ke folder `public/`.
7. Pasang 1 cron untuk scheduler.

> ⚠️ **Jangan jalankan `php artisan config:cache` / `optimize` di komputer dev.**
> Perintah itu mengunci nilai `.env` dev ke cache dan pernah menyebabkan masalah
> di lingkungan pengembangan. Optimize hanya dilakukan **di server produksi**.

---

## Bagian A — Shared Hosting cPanel

### A0. Yang perlu disiapkan
- Akun hosting cPanel yang mendukung **PHP 8.2+** dan **MySQL 8 / MariaDB 10.4+**.
- Domain atau subdomain (mis. `esupervisi.namasekolah.sch.id`).
- Akses **Terminal** di cPanel sangat membantu. Jika tidak ada, semua tetap bisa
  lewat **File Manager**, hanya beberapa langkah artisan dilakukan lewat trik URL/route (lihat A7).

### A1. Build aset di komputer lokal
Composer `vendor/` dan hasil build Vite sebaiknya disiapkan di lokal karena shared
hosting sering tidak punya Node.js.

```bash
# di komputer Anda, di dalam folder project
composer install --optimize-autoloader --no-dev
npm ci
npm run build
```

Hasilnya: folder `vendor/` terisi dan `public/build/` berisi aset produksi.

### A2. Kemas & upload
Kompres project menjadi ZIP **termasuk** `vendor/` dan `public/build/`, tapi
**tanpa** `node_modules/`, `.git/`, `.env`, dan `storage/*.key`.

Struktur yang disarankan di server (Laravel di luar `public_html`):

```
/home/namauser/
├── esupervisi/            <- SELURUH project ditaruh di sini
│   ├── app/  bootstrap/  config/  ...
│   ├── public/            <- ini yang akan jadi web root
│   └── vendor/
└── public_html/           <- (opsional, lihat A5)
```

Upload ZIP ke `/home/namauser/`, lalu **Extract** lewat File Manager sehingga
menjadi folder `esupervisi/`.

### A3. Buat database
cPanel → **MySQL Databases**:
1. Buat database baru (mis. `namauser_esupervisi`).
2. Buat user MySQL + password kuat.
3. **Add User To Database** → beri **ALL PRIVILEGES**.
4. Catat nama database, user, dan password.

### A4. Siapkan file `.env`
1. Salin `.env.production` → `.env` (via File Manager: rename/copy).
2. Edit `.env`, isi semua yang bertanda `<<< ISI >>>`:
   - `APP_URL` = alamat final (https).
   - `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` dari langkah A3.
   - `DEFAULT_*_PASSWORD` = password kuat.
3. Generate `APP_KEY`:
   - **Ada Terminal:** `cd ~/esupervisi && php artisan key:generate`
   - **Tanpa Terminal:** jalankan `php artisan key:generate` lewat Terminal cPanel,
     atau minta bantuan generate lalu tempel manual ke `APP_KEY=` (format `base64:....`).

> Jika domain **belum** ada SSL saat pertama tes, sementara set
> `SESSION_SECURE_COOKIE=false`. Kembalikan ke `true` setelah SSL aktif.

### A5. Arahkan document root ke `public/`
Pilih salah satu:

**Opsi 1 — Ubah document root (paling bersih).**
cPanel → **Domains** → pilih domain/subdomain → set **Document Root** ke
`esupervisi/public`. Selesai.

**Opsi 2 — Jika document root tidak bisa diubah.**
Pindahkan **isi** folder `esupervisi/public/` ke `public_html/`, lalu edit
`public_html/index.php`, ubah dua baris path agar menunjuk ke folder project:

```php
require __DIR__.'/../esupervisi/vendor/autoload.php';
$app = require_once __DIR__.'/../esupervisi/bootstrap/app.php';
```

Pastikan `public_html/.htaccess` ikut terbawa (sudah ada di `public/.htaccess`).

### A6. Migrasi, seeder, storage link (via Terminal cPanel)
```bash
cd ~/esupervisi
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

> `storage:link` membuat symlink `public/storage` → `storage/app/public` agar file
> upload (foto, dokumen) bisa diakses publik. Jika host melarang symlink, lihat A8.

### A7. Optimize untuk produksi (di server)
```bash
cd ~/esupervisi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

Untuk membersihkan cache setelah update konfigurasi:
`php artisan optimize:clear`.

### A8. Pasang cron scheduler
cPanel → **Cron Jobs** → tambah, jalan **setiap menit**:

```
* * * * * cd /home/namauser/esupervisi && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

Ini yang menjalankan pengingat supervisi otomatis (Senin & Kamis 07:00).
Sesuaikan path `php` dengan yang disediakan host (cek di **Select PHP Version** /
tanya support). Tanpa cron, aplikasi tetap jalan — hanya notifikasi terjadwal yang tidak terkirim.

> **Jika symlink storage tidak didukung:** buat folder `public/storage` biasa dan
> arahkan upload ke sana, atau set `FILESYSTEM_DISK` sesuai kebutuhan. Untuk mayoritas
> host Niagahoster/Hostinger, `storage:link` berfungsi normal.

---

## Bagian B — VPS / Railway (ringkas)

### VPS (Ubuntu + Nginx + PHP-FPM)
- Clone repo, `composer install --no-dev --optimize-autoloader`, `npm ci && npm run build`.
- `.env` dari `.env.production`, `php artisan key:generate`, `migrate --force`, `db:seed --force`, `storage:link`.
- Optimize seperti A7.
- Nginx `root` → folder `public/`, konfigurasi PHP-FPM standar Laravel.
- Permission: `chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache`.
- Scheduler: satu baris crontab `* * * * * php /path/artisan schedule:run`.
- Queue tetap tidak diperlukan (`sync`).

### Railway / Render (git push = deploy, cocok untuk demo klien)
- Tambahkan MySQL plugin, set semua variabel dari `.env.production` di dashboard.
- Build: `composer install --no-dev --optimize-autoloader && npm ci && npm run build`.
- Release/start: `php artisan migrate --force && php artisan db:seed --force && php artisan config:cache && php artisan route:cache && php artisan view:cache` lalu serve via `php artisan serve --host 0.0.0.0 --port $PORT` (atau Nginx+FPM image).
- Scheduler: gunakan cron/worker bawaan platform memanggil `php artisan schedule:run`.
- Cocok saat butuh **URL yang bisa dicoba klien** tanpa setup server manual.

---

## Bagian C — Checklist final sebelum "live"

- [ ] `APP_ENV=production` dan `APP_DEBUG=false`
- [ ] `APP_KEY` sudah ter-generate (bukan kosong)
- [ ] `APP_URL` = domain final (https)
- [ ] Kredensial database benar & `migrate --force` sukses
- [ ] `db:seed --force` sukses → bisa login admin
- [ ] **Password default admin sudah diganti** lewat aplikasi
- [ ] `storage:link` sudah dibuat & upload file tampil
- [ ] SSL aktif & `SESSION_SECURE_COOKIE=true`
- [ ] Aset tampil rapi (Tailwind ter-load dari `public/build`)
- [ ] `config:cache route:cache view:cache` sudah dijalankan di server
- [ ] Cron `schedule:run` terpasang (untuk pengingat supervisi)
- [ ] Uji cepat: login 3 role, ajukan supervisi, upload dokumen, beri feedback, cetak PDF
- [ ] Halaman `/up` (health check) mengembalikan status sehat

---

## Kredensial awal (dari seeder)

| Role          | NIK                | Password                          |
| ------------- | ------------------ | --------------------------------- |
| Administrator | `1234567890123456` | sesuai `DEFAULT_ADMIN_PASSWORD`   |

Buat akun Kepala Sekolah & Guru lewat panel admin setelah login.
**Ganti semua password default segera setelah live.**
