# 📌 Memo: Cara Update Web Live (e-supervisiazzahroh.com)

> Ditulis 2026-07-22. Baca ini setiap kali mau mengubah sesuatu dan lupa caranya.

## Cara kerjanya (versi singkat)

Server otomatis mengecek GitHub **setiap 5 menit**. Kalau ada commit baru di
branch `main`, server menarik perubahan itu dan web live langsung terupdate.

**Jadi rumusnya: ubah kode → commit → push ke `main` → tunggu maks. 5 menit → selesai.**
Tidak perlu upload ZIP, tidak perlu buka cPanel, tidak perlu klik apa-apa.

---

## Skenario 1 — Ubah tampilan/teks/logika PHP (file .php, .blade.php)

Paling sering terjadi. Langkahnya:

```bash
git add <file yang diubah>
git commit -m "pesan perubahan"
git push origin main
```

Tunggu maksimal 5 menit → cek web live. **Selesai.**

## Skenario 2 — Ubah CSS atau JavaScript (tampilan/warna/interaksi)

⚠️ **INI YANG SERING LUPA.** Server TIDAK bisa mem-build sendiri, jadi hasil
build harus ikut di-commit dari laptop:

```bash
npm run build                      # 1. WAJIB build dulu di laptop
git add public/build               # 2. WAJIB ikutkan hasil build
git add <file css/js yang diubah>  # 3. plus file yang kamu ubah
git commit -m "pesan perubahan"
git push origin main
```

Kalau lupa langkah 1–2: web live tetap pakai tampilan lama walau kode sudah
ter-push (karena browser membaca hasil build, bukan file sumbernya).

## Skenario 3 — Tambah/ubah package Composer (jarang sekali)

Kalau mengubah `composer.json` (menambah library PHP baru), push saja TIDAK
cukup. Setelah push, buka **Terminal cPanel** dan jalankan:

```bash
cd ~/esupervisi
composer install --optimize-autoloader --no-dev
```

## Skenario 4 — Tambah migration database baru

Tidak perlu apa-apa ekstra — script deploy otomatis menjalankan
`php artisan migrate --force` setiap deploy. Cukup commit + push seperti biasa.

---

## Cara mengecek deploy jalan atau tidak

Buka **Terminal cPanel**, lalu:

```bash
tail -20 ~/deploy.log
```

- Ada baris `Deploy selesai di commit: ...` dengan kode commit terbarumu → sukses.
- Tidak ada baris baru → belum 5 menit, atau tidak ada commit baru di `main`.
- Ada pesan error → screenshot dan tanyakan ke Claude.

Kalau butuh deploy SEKARANG tanpa menunggu cron:

```bash
bash ~/repositories/e-supervisi/scripts/deploy-server.sh --force
```

---

## Yang TIDAK BOLEH dilakukan

| Jangan | Kenapa |
|---|---|
| Pakai menu **Git Deploy Manager** di cPanel | Pernah bikin insiden: source code terekspos publik + gambar hilang (2026-07-22) |
| Edit file langsung di server (File Manager) | Akan TERTIMPA oleh deploy berikutnya. Selalu ubah di laptop → push |
| Hapus/edit `~/public_html/index.php` di server | File ini spesial (path-nya diedit khusus untuk struktur hosting) dan sengaja TIDAK disentuh deploy |
| Jalankan `php artisan config:cache` di server | OPcache host ini bermasalah — situs bisa error 500 (lihat PANDUAN-DEPLOY.md A7) |
| Push kode yang belum dites ke `main` | `main` = langsung live! Kerjakan dulu di branch lain / `develop`, merge ke `main` kalau sudah yakin |

## File yang AMAN (tidak pernah disentuh deploy)

`.env` (password/config server), `storage/` (semua file upload: gambar carousel,
thumbnail, dokumen guru), `vendor/`, `index.php` web root, symlink `storage`.
Jadi deploy tidak akan pernah menghapus data upload atau merusak konfigurasi.

---

*Detail teknis lengkap: [PANDUAN-DEPLOY.md](PANDUAN-DEPLOY.md) bagian A9.
Script deploy: [scripts/deploy-server.sh](scripts/deploy-server.sh).*
