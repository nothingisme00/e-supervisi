# E-Supervisi Hostinger Deployment Guide

**Website:** https://esupervisi.hiatta.site  
**Hosting:** Hostinger Shared Hosting  
**Server IP:** 153.92.10.35  
**PHP Version:** 8.2

---

## 📁 Struktur Folder di Hostinger

```
/home/u264116029/domains/hiatta.site/
├── e-supervisi/                    ← FOLDER UTAMA LARAVEL
│   ├── app/
│   │   ├── Http/
│   │   ├── Livewire/              ← Livewire components
│   │   │   └── Admin/
│   │   │       └── UserManagement.php
│   │   └── Models/
│   ├── bootstrap/
│   │   ├── app.php
│   │   └── cache/                 ← HAPUS ISINYA SAAT CLEAR CACHE
│   ├── config/
│   ├── database/
│   ├── public/
│   │   └── build/                 ← VITE BUILD (harus ada manifest.json)
│   ├── resources/
│   │   └── views/
│   ├── routes/
│   ├── storage/
│   │   ├── app/
│   │   ├── framework/
│   │   │   ├── cache/
│   │   │   ├── sessions/
│   │   │   └── views/             ← HAPUS ISINYA SAAT CLEAR CACHE
│   │   └── logs/
│   │       └── laravel.log        ← CEK ERROR DI SINI
│   ├── vendor/                    ← COMPOSER DEPENDENCIES (BESAR!)
│   │   └── autoload.php           ← FILE INI WAJIB ADA
│   ├── .env                       ← KONFIGURASI ENVIRONMENT
│   └── artisan
│
└── public_html/
    └── esupervisi/                ← FOLDER PUBLIC (DOCUMENT ROOT)
        ├── index.php              ← ENTRY POINT
        ├── .htaccess
        └── build/                 ← COPY DARI e-supervisi/public/build/
            ├── assets/
            └── manifest.json
```

---

## ⚠️ PENTING: Path di index.php

File `public_html/esupervisi/index.php` HARUS menggunakan path yang benar:

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Path ke folder Laravel (NAIK 2 LEVEL dari public_html/esupervisi/)
$basePath = dirname(__DIR__, 2) . '/e-supervisi';

if (file_exists($maintenance = $basePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $basePath.'/vendor/autoload.php';

(require_once $basePath.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

---

## 🔄 Cara Update Aplikasi

### 1. Upload File yang Diubah
Upload file ke folder yang sesuai:
- **Views** → `e-supervisi/resources/views/`
- **Controllers** → `e-supervisi/app/Http/Controllers/`
- **Livewire** → `e-supervisi/app/Livewire/`
- **Assets (CSS/JS)** → `e-supervisi/public/build/` DAN `public_html/esupervisi/build/`

### 2. Clear Cache (WAJIB setelah update views/config)
Hapus semua file di:
- `e-supervisi/storage/framework/views/`
- `e-supervisi/bootstrap/cache/`

### 3. Hard Refresh Browser
Tekan `Ctrl + Shift + R` untuk bypass cache browser.

---

## 🚨 Troubleshooting Common Errors

### Error 500 - Internal Server Error
1. Cek `e-supervisi/storage/logs/laravel.log`
2. Pastikan folder `storage` dan `bootstrap/cache` writable (permission 755)
3. Pastikan `.env` ada dan konfigurasi benar

### Vite Manifest Not Found
1. Pastikan folder `build` ada di `e-supervisi/public/build/`
2. Pastikan `manifest.json` ada di dalam folder `build`
3. Copy juga ke `public_html/esupervisi/build/`

### Livewire Component Not Found
1. Pastikan file component ada di `e-supervisi/app/Livewire/`
2. Nama class dan namespace harus sesuai

### vendor/autoload.php Not Found
1. Upload ulang folder `vendor` dari lokal
2. Pastikan tidak ada nested folder (`vendor/vendor/`)
3. Pastikan `autoload.php` ada di root folder vendor

---

## 📝 Cloudflare DNS Settings

| Type | Name       | Content       | Proxy  |
|------|------------|---------------|--------|
| A    | esupervisi | 153.92.10.35  | Proxied|
| A    | hiatta.site| 72.61.214.40  | Proxied|
| A    | www        | 72.61.214.40  | Proxied|

---

## 📋 Checklist Deployment

- [ ] Upload semua file yang diubah ke lokasi yang benar
- [ ] Pastikan `vendor/autoload.php` ada
- [ ] Pastikan `public/build/manifest.json` ada di KEDUA lokasi
- [ ] Hapus cache views dan bootstrap
- [ ] Cek `.env` konfigurasi (APP_DEBUG=false untuk production)
- [ ] Test akses website
- [ ] Hard refresh browser

---

*Terakhir diupdate: 16 Desember 2025*
