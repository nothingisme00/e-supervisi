# Panduan Deploy Laravel E-Supervisi ke Niagahoster Shared Hosting

> **Panduan Lengkap & Teruji** - Berdasarkan deployment yang berhasil dilakukan

Panduan step-by-step untuk deploy aplikasi Laravel E-Supervisi ke Niagahoster shared hosting dengan cara yang benar.

---

## 📋 Prasyarat

Sebelum memulai, pastikan Anda memiliki:
- ✅ Akun Niagahoster dengan shared hosting aktif
- ✅ Akses ke hPanel Niagahoster
- ✅ Repository GitHub dengan source code
- ✅ Aplikasi berjalan dengan baik di lokal
- ✅ Node.js & NPM terinstall di komputer lokal
- ✅ Composer terinstall di komputer lokal

---

## 🚀 Langkah-Langkah Deployment

### **STEP 1: Buat Subdomain di hPanel**

1. Login ke **hPanel Niagahoster**
2. Menu **"Domains"** → **"Subdomains"**
3. **Buat subdomain baru:**
   - Subdomain: `esupervisi` (atau nama lain)
   - Domain: `hiatta.site` (domain utama Anda)
   - Document Root: **BIARKAN DEFAULT** (`public_html/esupervisi`)
4. Klik **"Create"**

> **✅ Hasil:** Subdomain `esupervisi.hiatta.site` dibuat dengan document root di `/public_html/esupervisi/`

---

### **STEP 2: Buat Database MySQL**

1. Di hPanel, menu **"Databases"** → **"MySQL Databases"**
2. **Buat database baru:**
   - Database Name: `esupervisi_db` (akan jadi `u264116029_esupervisi_db`)
   - Klik **"Create Database"**
3. **Buat user database:**
   - Username: `user` (akan jadi `u264116029_user`)
   - Password: **Generate strong password** dan **COPY!**
   - Klik **"Create User"**
4. **Tambahkan user ke database:**
   - Pilih user: `u264116029_user`
   - Pilih database: `u264116029_esupervisi_db`
   - Klik **"Add"**
   - Centang **"ALL PRIVILEGES"**
   - Klik **"Make Changes"**

> **✅ Hasil:** Database siap dengan kredensial:
> - Database: `u264116029_esupervisi_db`
> - Username: `u264116029_user`
> - Password: (yang Anda copy tadi)

---

### **STEP 3: Download Source Code dari GitHub**

1. Di hPanel, buka **"File Manager"**
2. Navigate ke `/domains/hiatta.site/`
3. Klik **"Git Clone"** atau gunakan terminal (jika ada SSH)
4. Clone repository:
   ```
   https://github.com/username/e-supervisi.git
   ```
5. Folder `e-supervisi` akan muncul di `/domains/hiatta.site/e-supervisi/`

> **📝 Catatan:** Jika tidak ada fitur Git Clone, download ZIP dari GitHub dan extract manual.

---

### **STEP 4: Upload Vendor Dependencies**

**Di Komputer Lokal:**

1. Buka PowerShell di folder project:
   ```powershell
   cd "d:\Dokumen\Abang Punya\Project\e-supervisi"
   ```

2. Compress folder vendor:
   ```powershell
   Compress-Archive -Path "vendor" -DestinationPath "vendor.zip" -Force
   ```

**Di Hosting:**

3. Upload `vendor.zip` ke `/domains/hiatta.site/e-supervisi/`
4. **Extract** `vendor.zip` di folder tersebut
5. **Hapus** `vendor.zip` setelah extract
6. **Verifikasi:** Pastikan ada folder `vendor/` dengan file `autoload.php` di dalamnya

> **⏱️ Estimasi:** Upload 5-15 menit tergantung koneksi

---

### **STEP 5: Build & Upload Production Assets**

**Di Komputer Lokal:**

1. Build production assets:
   ```bash
   npm run build
   ```

2. Compress folder build:
   ```powershell
   Compress-Archive -Path "public\build" -DestinationPath "build.zip" -Force
   ```

**Di Hosting:**

3. Upload `build.zip` ke **2 lokasi**:
   - `/public_html/esupervisi/`
   - `/domains/hiatta.site/e-supervisi/public/`

4. **Extract** di kedua lokasi tersebut

5. **Verifikasi struktur:**
   ```
   /public_html/esupervisi/build/
   ├── manifest.json
   └── assets/
       ├── app-xxxxxxxx.css
       └── app-xxxxxxxx.js
   
   /domains/hiatta.site/e-supervisi/public/build/
   ├── manifest.json
   └── assets/
       ├── app-xxxxxxxx.css
       └── app-xxxxxxxx.js
   ```

> **⚠️ PENTING:** Folder `build/` harus ada di KEDUA lokasi!

---

### **STEP 6: Copy File Public ke Document Root**

1. Di File Manager, navigate ke `/domains/hiatta.site/e-supervisi/public/`
2. **Copy semua file** (kecuali folder `build/` yang sudah ada):
   - `.htaccess`
   - `index.php`
   - `favicon.ico`
   - `robots.txt`
3. **Paste** ke `/public_html/esupervisi/`

---

### **STEP 7: Edit File index.php**

1. Buka file `/public_html/esupervisi/index.php`
2. **Replace seluruh isinya** dengan:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require '/home/u264116029/domains/hiatta.site/e-supervisi/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once '/home/u264116029/domains/hiatta.site/e-supervisi/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

3. **Save** file

> **⚠️ PENTING:** Ganti `u264116029` dengan username hosting Anda!

---

### **STEP 8: Konfigurasi File .env**

1. Navigate ke `/domains/hiatta.site/e-supervisi/`
2. **Copy** file `.env.example` → `.env` (jika belum ada)
3. **Edit** file `.env`:

```env
APP_NAME="E-Supervisi"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://esupervisi.hiatta.site

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u264116029_esupervisi_db
DB_USERNAME=u264116029_user
DB_PASSWORD=password_yang_anda_copy_tadi

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.hiatta.site

CACHE_STORE=database
QUEUE_CONNECTION=database
```

4. **Save** file

> **⚠️ PENTING:** 
> - Ganti `u264116029` dengan username hosting Anda
> - Ganti password dengan yang benar
> - `APP_KEY` masih kosong, akan di-generate di step berikutnya

---

### **STEP 9: Generate APP_KEY**

1. Buat file `/public_html/esupervisi/generate-key.php`:

```php
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Generate APP_KEY</h1>";
echo "<pre>";

$laravelPath = '/home/u264116029/domains/hiatta.site/e-supervisi';

require $laravelPath . '/vendor/autoload.php';
$app = require_once $laravelPath . '/bootstrap/app.php';

try {
    Artisan::call('key:generate', ['--force' => true]);
    echo "✓ APP_KEY generated successfully!\n\n";
    echo Artisan::output();
    echo "\nCheck your .env file for the new APP_KEY.";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage();
}

echo "\n\n⚠️ DELETE THIS FILE AFTER USE!";
echo "</pre>";
?>
```

2. **Akses:** `https://esupervisi.hiatta.site/generate-key.php`
3. **Verifikasi:** Cek file `.env`, seharusnya `APP_KEY` sudah terisi
4. **HAPUS** file `generate-key.php`

---

### **STEP 10: Import Database**

**Di Komputer Lokal:**

1. Jalankan migration & seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```

2. Export database via phpMyAdmin lokal:
   - Export database `e_supervisi`
   - Format: SQL
   - Download file `.sql`

**Di Hosting:**

3. Buka **phpMyAdmin** di hPanel
4. Pilih database `u264116029_esupervisi_db`
5. Tab **"Import"**
6. Upload file `.sql` yang tadi di-export
7. Klik **"Go"** atau **"Import"**
8. **Tunggu** sampai selesai

> **✅ Hasil:** Database ter-import dengan semua tabel dan data

---

### **STEP 11: Install SSL Certificate**

1. Di hPanel, menu **"Security"** → **"SSL"**
2. Pilih domain: `esupervisi.hiatta.site`
3. Pilih **"Free SSL"** (Let's Encrypt)
4. Klik **"Install"**
5. **Tunggu** 5-15 menit untuk aktivasi

---

### **STEP 12: Test Aplikasi**

1. **Akses:** `https://esupervisi.hiatta.site`
2. **Halaman login** seharusnya muncul dengan tampilan yang benar
3. **Login** dengan kredensial default:

**Admin:**
```
Email: admin@example.com
Password: admin123
```

**Kepala Sekolah:**
```
Email: kepala@example.com
Password: kepala123
```

**Guru:**
```
Email: guru@example.com
Password: guru123
```

---

## 🔒 Post-Deployment Security

### **WAJIB Dilakukan Setelah Deploy:**

1. **Hapus helper scripts** di `/public_html/esupervisi/`:
   - ❌ `generate-key.php`
   - ❌ `test.php`
   - ❌ Semua file helper lainnya

2. **Ganti password default:**
   - Login sebagai admin
   - Ganti password dari `admin123` ke password yang kuat
   - Lakukan untuk semua user default

3. **Set APP_DEBUG=false:**
   - Edit `.env`
   - Pastikan `APP_DEBUG=false`

4. **Update APP_URL:**
   - Pastikan `APP_URL=https://esupervisi.hiatta.site`

---

## 🔄 Cara Update Aplikasi

### **Update Kode PHP/Blade:**

1. Edit file di lokal
2. Upload file yang diubah ke hosting
3. Clear cache:
   - Buat file `clear-cache.php` di `/public_html/esupervisi/`
   - Isi dengan script clear cache (lihat troubleshooting)
   - Akses via browser
   - Hapus file setelah selesai

### **Update UI/CSS/JS:**

1. Edit file di lokal
2. Build ulang: `npm run build`
3. Compress folder `public/build/`
4. Upload & extract ke kedua lokasi:
   - `/public_html/esupervisi/build/`
   - `/domains/hiatta.site/e-supervisi/public/build/`
5. Clear cache browser (Ctrl+Shift+R)

### **Update Database (Migration):**

1. Buat migration di lokal
2. Upload file migration ke hosting
3. Buat file `run-migration.php` (lihat troubleshooting)
4. Akses via browser
5. Hapus file setelah selesai

---

## 🛠️ Troubleshooting

### **Error: HTTP 500**

**Penyebab:** File `index.php` salah atau path tidak benar

**Solusi:**
- Cek file `index.php` di `/public_html/esupervisi/`
- Pastikan path absolute benar
- Pastikan tidak ada duplikasi bootstrap code

### **Error: Vite manifest not found**

**Penyebab:** Folder `build/` tidak ada atau tidak di lokasi yang benar

**Solusi:**
- Pastikan folder `build/` ada di KEDUA lokasi:
  - `/public_html/esupervisi/build/`
  - `/domains/hiatta.site/e-supervisi/public/build/`
- Build ulang assets di lokal: `npm run build`
- Upload ulang folder `build/`

### **Error: Database connection failed**

**Penyebab:** Kredensial database salah atau user belum ditambahkan ke database

**Solusi:**
- Cek file `.env`, pastikan:
  - `DB_DATABASE` benar (dengan prefix `u264116029_`)
  - `DB_USERNAME` benar (dengan prefix `u264116029_`)
  - `DB_PASSWORD` benar
- Di hPanel, pastikan user sudah ditambahkan ke database dengan ALL PRIVILEGES

### **CSS/JS tidak load (tampilan berantakan)**

**Penyebab:** File assets tidak bisa diakses

**Solusi:**
- Cek folder `build/` ada di `/public_html/esupervisi/build/`
- Test akses: `https://esupervisi.hiatta.site/build/manifest.json`
- Jika 404, upload ulang folder `build/`
- Hard refresh browser (Ctrl+Shift+R)

### **Script Clear Cache**

Buat file `clear-cache.php` di `/public_html/esupervisi/`:

```php
<?php
$laravelPath = '/home/u264116029/domains/hiatta.site/e-supervisi';
require $laravelPath . '/vendor/autoload.php';
$app = require_once $laravelPath . '/bootstrap/app.php';

echo "<h1>Clear Cache</h1><pre>";

Artisan::call('cache:clear');
echo "✓ Cache cleared\n";

Artisan::call('config:clear');
echo "✓ Config cache cleared\n";

Artisan::call('route:clear');
echo "✓ Route cache cleared\n";

Artisan::call('view:clear');
echo "✓ View cache cleared\n";

echo "\n✓ All caches cleared!";
echo "\n⚠️ DELETE THIS FILE AFTER USE!";
echo "</pre>";
?>
```

Akses via browser, lalu **HAPUS** file setelah selesai.

---

## 📝 Checklist Deployment

- [ ] Subdomain dibuat
- [ ] Database MySQL dibuat
- [ ] User database dibuat dan ditambahkan ke database
- [ ] Source code di-download dari GitHub
- [ ] Folder `vendor/` di-upload dan extract
- [ ] Assets di-build dan folder `build/` di-upload ke kedua lokasi
- [ ] File public di-copy ke document root
- [ ] File `index.php` di-edit dengan path yang benar
- [ ] File `.env` dikonfigurasi dengan kredensial yang benar
- [ ] `APP_KEY` di-generate
- [ ] Database di-import
- [ ] SSL certificate di-install
- [ ] Aplikasi bisa diakses dan login berhasil
- [ ] Helper scripts dihapus
- [ ] Password default diganti
- [ ] `APP_DEBUG` di-set ke `false`

---

## 🎯 Struktur Folder Final

```
/home/u264116029/
├── domains/
│   └── hiatta.site/
│       └── e-supervisi/              ← Source code Laravel
│           ├── app/
│           ├── bootstrap/
│           ├── config/
│           ├── database/
│           ├── public/
│           │   └── build/            ← Build assets (lokasi 2)
│           ├── resources/
│           ├── routes/
│           ├── storage/
│           ├── vendor/               ← Dependencies
│           ├── .env                  ← Config production
│           └── ...
│
└── public_html/
    └── esupervisi/                   ← Document root subdomain
        ├── build/                    ← Build assets (lokasi 1)
        │   ├── manifest.json
        │   └── assets/
        ├── .htaccess
        ├── index.php                 ← Entry point (sudah di-edit)
        ├── favicon.ico
        └── robots.txt
```

---

## ✅ Deployment Berhasil!

Jika semua langkah diikuti dengan benar, aplikasi E-Supervisi Anda sekarang sudah **LIVE** dan bisa diakses di:

**🌐 https://esupervisi.hiatta.site**

Selamat! 🎉

---

**Catatan:** Panduan ini dibuat berdasarkan deployment yang berhasil dilakukan. Semua masalah yang muncul selama proses deployment sudah diperbaiki dan solusinya sudah termasuk dalam panduan ini.
