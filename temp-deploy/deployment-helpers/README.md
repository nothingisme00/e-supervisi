# E-Supervisi Deployment Helpers

Folder ini berisi script helper untuk deployment ke shared hosting Niagahoster yang tidak memiliki akses SSH.

## ⚠️ PERINGATAN KEAMANAN

**SEMUA FILE PHP DI FOLDER INI HARUS DIHAPUS SETELAH DIGUNAKAN!**

File-file ini memberikan akses ke fungsi-fungsi sensitif Laravel dan HANYA boleh digunakan saat deployment awal.

## File-file Helper

### 1. `generate-key.php`
**Fungsi**: Generate APP_KEY untuk aplikasi Laravel

**Cara Pakai**:
1. Upload file ini ke folder `public_html/`
2. Akses via browser: `https://yourdomain.com/generate-key.php`
3. Copy APP_KEY yang di-generate
4. **HAPUS file ini segera setelah selesai!**

### 2. `migrate-database.php`
**Fungsi**: Menjalankan database migration dan seeder

**Cara Pakai**:
1. Pastikan `.env` sudah dikonfigurasi dengan benar
2. Upload file ini ke folder `public_html/`
3. Akses via browser: `https://yourdomain.com/migrate-database.php`
4. Tunggu sampai proses selesai
5. **HAPUS file ini segera setelah selesai!**

**Output**: Script akan membuat semua tabel database dan mengisi data awal (admin, kepala sekolah, guru)

### 3. `optimize.php`
**Fungsi**: Optimize Laravel untuk production (cache config, routes, views)

**Cara Pakai**:
1. Upload file ini ke folder `public_html/`
2. Akses via browser: `https://yourdomain.com/optimize.php`
3. Tunggu sampai proses selesai
4. **HAPUS file ini segera setelah selesai!**

**Kapan digunakan**:
- Setelah deployment awal
- Setelah update konfigurasi
- Setelah update routes atau views

## Urutan Penggunaan

Untuk deployment baru, gunakan script dalam urutan berikut:

```
1. generate-key.php      → Generate APP_KEY
2. migrate-database.php  → Setup database
3. optimize.php          → Optimize untuk production
```

## Catatan Penting

1. **Path Laravel**: Semua script mengasumsikan struktur folder:
   ```
   /home/username/
   ├── e-supervisi/          # Folder Laravel
   └── public_html/          # Web root
       └── [helper-files].php
   ```
   
   Jika struktur folder Anda berbeda, edit variabel `$laravelPath` di setiap file.

2. **Permissions**: Pastikan folder `storage/` dan `bootstrap/cache/` memiliki permission 755

3. **Database**: Pastikan database sudah dibuat dan kredensial di `.env` sudah benar sebelum menjalankan `migrate-database.php`

4. **Keamanan**: Jangan lupa hapus semua file helper setelah selesai digunakan!

## Troubleshooting

### Error: "Class 'DB' not found" di migrate-database.php
**Solusi**: Tambahkan `use Illuminate\Support\Facades\DB;` di bagian atas file

### Error: "vendor/autoload.php not found"
**Solusi**: Periksa path `$laravelPath` sudah benar

### Error: "Permission denied" saat cache
**Solusi**: Set permission folder `storage/` dan `bootstrap/cache/` ke 755

### Migration gagal
**Solusi**: 
- Cek koneksi database di `.env`
- Pastikan DB_HOST = `localhost` (bukan 127.0.0.1)
- Cek nama database include prefix username

## Support

Jika mengalami masalah, cek:
1. Error logs di cPanel → Error Logs
2. Laravel logs di `storage/logs/laravel.log`
3. PHP error logs di cPanel
