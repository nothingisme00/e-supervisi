---
description: Deploy Laravel e-supervisi ke Niagahoster Shared Hosting
---

# Panduan Deploy Laravel e-supervisi ke Niagahoster

## Persiapan Sebelum Deploy

### 1. Compress Project untuk Upload
Buat file ZIP dari project (tanpa folder yang tidak perlu):

```bash
# Buat folder sementara untuk file yang akan di-upload
mkdir deploy-temp
```

**File/Folder yang TIDAK perlu di-upload:**
- `node_modules/`
- `.git/`
- `storage/logs/*` (kecuali .gitignore)
- `storage/framework/cache/*` (kecuali .gitignore)
- `storage/framework/sessions/*` (kecuali .gitignore)
- `storage/framework/views/*` (kecuali .gitignore)
- `.env` (akan dibuat manual di server)
- `database_import.sql`, `*.sql` files
- `*.py` files
- `public/test-*.php`, `public/debug-*.php`

### 2. Optimize Project untuk Production

```bash
# Install dependencies production only
composer install --optimize-autoloader --no-dev

# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## Langkah Deploy ke Niagahoster

### Step 1: Login ke cPanel Niagahoster
1. Buka https://panel.niagahoster.co.id
2. Login dengan akun Anda
3. Klik "cPanel" untuk masuk ke control panel

### Step 2: Buat Database MySQL
1. Di cPanel, cari dan klik **"MySQL Databases"**
2. Buat database baru:
   - Database Name: `e_supervisi` (atau nama lain)
   - Klik "Create Database"
3. Buat user database:
   - Username: `e_supervisi_user` (atau nama lain)
   - Password: Buat password yang kuat
   - Klik "Create User"
4. Tambahkan user ke database:
   - Pilih user yang baru dibuat
   - Pilih database yang baru dibuat
   - Centang "ALL PRIVILEGES"
   - Klik "Add"
5. **CATAT**: Database name, username, dan password untuk konfigurasi `.env`

### Step 3: Upload File Project

#### Opsi A: Upload via File Manager (Recommended untuk file besar)
1. Di cPanel, klik **"File Manager"**
2. Navigate ke folder `public_html` (atau subdomain folder)
3. Buat folder baru bernama `e-supervisi-temp`
4. Upload file ZIP project ke folder tersebut
5. Klik kanan file ZIP → Extract
6. Setelah extract selesai, hapus file ZIP

#### Opsi B: Upload via FTP
1. Download FileZilla atau FTP client lainnya
2. Koneksi ke server:
   - Host: ftp.yourdomain.com (atau IP dari cPanel)
   - Username: username cPanel Anda
   - Password: password cPanel Anda
   - Port: 21
3. Upload semua file project ke folder `public_html/e-supervisi-temp`

### Step 4: Struktur Folder di Server

**PENTING**: Laravel di shared hosting memerlukan struktur khusus:

```
public_html/
├── e-supervisi/          # Folder aplikasi Laravel (di luar public_html)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   └── ...
└── public/               # Atau subdomain folder
    ├── index.php         # Dari folder public Laravel
    ├── .htaccess
    ├── css/
    ├── js/
    └── ...
```

**Cara Setup:**
1. Via File Manager, pindahkan folder `e-supervisi-temp` ke **LUAR** `public_html`:
   - Klik "Up One Level" sampai ke root directory (biasanya `/home/username/`)
   - Rename `public_html/e-supervisi-temp` menjadi `e-supervisi`
   
2. Copy isi folder `public` dari Laravel ke `public_html`:
   - Copy semua file dari `/home/username/e-supervisi/public/*`
   - Paste ke `/home/username/public_html/` (atau subdomain folder)

### Step 5: Edit index.php

Edit file `public_html/index.php`:

```php
// Cari baris ini:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Ganti menjadi (sesuaikan path):
require __DIR__.'/../e-supervisi/vendor/autoload.php';
$app = require_once __DIR__.'/../e-supervisi/bootstrap/app.php';
```

### Step 6: Konfigurasi .env

1. Via File Manager, navigate ke `/home/username/e-supervisi/`
2. Copy file `.env.example` menjadi `.env`
3. Edit file `.env` dengan konfigurasi production:

```env
APP_NAME="E-Supervisi"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_e_supervisi
DB_USERNAME=username_e_supervisi_user
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.yourdomain.com

CACHE_STORE=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

**PENTING**: Ganti:
- `yourdomain.com` dengan domain Anda
- `username_e_supervisi` dengan nama database yang dibuat
- `username_e_supervisi_user` dengan username database
- `your_database_password` dengan password database

### Step 7: Set Permissions (Via Terminal SSH atau File Manager)

Jika ada akses SSH:
```bash
cd /home/username/e-supervisi
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

Jika via File Manager:
1. Klik kanan folder `storage` → Change Permissions → 755
2. Klik kanan folder `bootstrap/cache` → Change Permissions → 755

### Step 8: Generate Application Key

Via SSH:
```bash
cd /home/username/e-supervisi
php artisan key:generate
```

**Jika tidak ada SSH**, buat file temporary di `public_html/generate-key.php`:
```php
<?php
require __DIR__.'/../e-supervisi/vendor/autoload.php';
$app = require_once __DIR__.'/../e-supervisi/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->call('key:generate');
echo "Key generated successfully!";
// Hapus file ini setelah selesai
?>
```

Akses: `https://yourdomain.com/generate-key.php`
**HAPUS file ini setelah selesai!**

### Step 9: Migrate Database

Via SSH:
```bash
cd /home/username/e-supervisi
php artisan migrate --force
php artisan db:seed --force
```

**Jika tidak ada SSH**, buat file temporary di `public_html/migrate.php`:
```php
<?php
require __DIR__.'/../e-supervisi/vendor/autoload.php';
$app = require_once __DIR__.'/../e-supervisi/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "Running migrations...<br>";
$kernel->call('migrate', ['--force' => true]);

echo "Running seeders...<br>";
$kernel->call('db:seed', ['--force' => true]);

echo "Done!<br>";
// Hapus file ini setelah selesai
?>
```

Akses: `https://yourdomain.com/migrate.php`
**HAPUS file ini setelah selesai!**

### Step 10: Optimize untuk Production

Via SSH:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Via temporary file `public_html/optimize.php`:
```php
<?php
require __DIR__.'/../e-supervisi/vendor/autoload.php';
$app = require_once __DIR__.'/../e-supervisi/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('config:cache');
echo "Config cached<br>";

$kernel->call('route:cache');
echo "Routes cached<br>";

$kernel->call('view:cache');
echo "Views cached<br>";

echo "Optimization complete!";
?>
```

### Step 11: Setup .htaccess (Jika belum ada)

File `public_html/.htaccess`:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect to HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    
    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Step 12: Test Aplikasi

1. Buka browser dan akses domain Anda
2. Coba login dengan kredensial default:
   - **Admin**: admin@example.com / admin123
   - **Kepala Sekolah**: kepala@example.com / kepala123
   - **Guru**: guru@example.com / guru123

### Step 13: Keamanan Post-Deploy

1. **Hapus semua file temporary** (generate-key.php, migrate.php, optimize.php)
2. **Ganti password default** semua user
3. **Pastikan `.env` tidak bisa diakses** dari browser
4. **Hapus file debug** di folder public

## Troubleshooting

### Error 500 Internal Server Error
- Cek file `.env` sudah benar
- Cek permissions folder `storage` dan `bootstrap/cache` (755)
- Cek error log di cPanel → Error Logs

### Database Connection Error
- Pastikan DB_HOST = `localhost` (bukan 127.0.0.1)
- Pastikan nama database include prefix username (contoh: `username_e_supervisi`)
- Cek kredensial database sudah benar

### CSS/JS tidak load
- Pastikan file di folder `public` sudah ter-copy ke `public_html`
- Cek APP_URL di `.env` sudah sesuai domain
- Clear browser cache

### Session tidak berfungsi
- Pastikan SESSION_DRIVER=database di `.env`
- Jalankan migrate untuk membuat tabel sessions
- Cek SESSION_DOMAIN di `.env`

## Maintenance

### Update Aplikasi
1. Backup database dan file `.env`
2. Upload file baru (overwrite yang lama)
3. Jalankan migrate jika ada perubahan database
4. Clear cache: `php artisan cache:clear`

### Backup
- Backup database via phpMyAdmin (Export)
- Backup folder `storage/app` (uploaded files)
- Backup file `.env`
