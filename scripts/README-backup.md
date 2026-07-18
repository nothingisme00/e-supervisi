# Backup Database e-supervisi

Skrip untuk mencadangkan database MySQL/MariaDB lokal secara otomatis, agar
kejadian database terhapus (seperti sebelumnya) tidak lagi berakibat fatal.

## File
- `backup-db.ps1` — skrip utama (PowerShell). Membaca setelan DB dari `.env`,
  membuat dump ber-timestamp ke `database/backups/`, dan menyimpan hanya 30
  backup terbaru (otomatis menghapus yang lebih lama).
- `backup-db.bat` — pembungkus agar bisa diklik-dua-kali / dipakai Task Scheduler.

Hasil backup disimpan di `database/backups/` dengan nama
`e_supervisi_YYYYMMDD_HHmmss.sql`. Folder itu di-`.gitignore` (dump berisi data
asli, tidak ikut ke Git).

## Menjalankan manual
Klik dua kali `scripts\backup-db.bat`, atau dari terminal di root project:

```powershell
scripts\backup-db.bat
```

Ingin menyimpan lebih/kurang dari 30 file? Tambahkan angka:

```powershell
powershell -ExecutionPolicy Bypass -File scripts\backup-db.ps1 -KeepCount 60
```

## Menjadwalkan otomatis (Windows Task Scheduler)
1. Buka **Task Scheduler** → **Create Basic Task…**
2. Nama: `Backup DB e-supervisi`. Trigger: **Daily** (mis. jam 20:00).
   - Untuk lebih sering, pilih Daily lalu di tab **Triggers** → edit →
     centang **Repeat task every** 6 hours (atau sesuai kebutuhan).
3. Action: **Start a program**.
   - Program/script:
     ```
     C:\Windows\System32\cmd.exe
     ```
   - Add arguments:
     ```
     /c "D:\Dokumen\Abang Punya\Project\e-supervisi\scripts\backup-db.bat"
     ```
4. Centang **Open the Properties dialog** → di tab **General** pilih
   **Run whether user is logged on or not** (agar tetap jalan walau belum login),
   lalu OK.

> Catatan: MySQL/MariaDB (XAMPP) harus dalam keadaan menyala saat backup berjalan.
> Kalau MySQL Anda hanya menyala saat kerja, jadwalkan backup pada jam kerja.

## Cara restore (mengembalikan data dari backup)
Dump dibuat dengan `--databases`, jadi otomatis membuat ulang database `e_supervisi`.

Lewat command line:
```powershell
C:\xampp2\mysql\bin\mysql.exe -h 127.0.0.1 -u root < "database\backups\e_supervisi_YYYYMMDD_HHmmss.sql"
```

Atau lewat **phpMyAdmin** → pilih/klik database → tab **Import** → pilih file
`.sql` dari `database/backups/` → **Go**.

## Produksi (Laravel Cloud)
Database produksi di Laravel Cloud dikelola terpisah dan memiliki mekanisme
backup sendiri di dashboard penyedia — skrip ini khusus untuk database **lokal**.
