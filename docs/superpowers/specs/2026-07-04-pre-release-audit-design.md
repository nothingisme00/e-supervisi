# Desain: Audit & Perbaikan Pra-Rilis e-Supervisi

**Tanggal:** 2026-07-04
**Status:** Disetujui
**Konteks:** Aplikasi belum dipakai pengguna nyata; ini persiapan sebelum rilis. Semua area (keamanan, alur kerja, bug, UX) diprioritaskan. Mode kerja: audit dulu → laporkan per tingkat keparahan → perbaiki dengan persetujuan per kelompok.

## Latar Belakang

e-Supervisi adalah aplikasi Laravel untuk supervisi guru dengan tiga role:

- **Guru** — membuat supervisi, mengisi proses, mengunggah dokumen, submit.
- **Kepala Sekolah** — mereview evaluasi, memberi feedback, meminta revisi, menyelesaikan.
- **Admin** — mengelola user, mereview supervisi, mengelola carousel beranda.

Audit dilakukan pada kondisi kerja saat ini: branch `develop` termasuk perubahan yang belum di-commit.

## Lingkup Audit (4 lapis, berurutan)

### Lapis 1 — Keamanan (berpedoman OWASP Top 10)

- Route & middleware di `routes/web.php`: cakupan `auth`, `can:isAdmin/isGuru/isKepalaSekolah`, `must.change.password`, rate limiting.
- Semua controller (Admin/Guru/KepalaSekolah): pemeriksaan kepemilikan data (IDOR — guru A mengakses supervisi guru B lewat ID langsung).
- Upload/download file: validasi tipe & ukuran, path traversal, akses file lintas role.
- Mass assignment di model (`$fillable`/`$guarded`).
- XSS di Blade: pemakaian `{!! !!}` dan output yang tidak di-escape.
- Konfigurasi sesi, `SessionTimeout` middleware, CSRF.
- Advisori `composer audit` (24 advisori pada 13 paket) — laporkan yang relevan dan kritis.

### Lapis 2 — Kebenaran Alur Kerja

- Petakan state machine supervisi (draft → submit → review → revisi → selesai) dan semua titik transisinya di controller.
- Uji skenario transisi ilegal via request langsung:
  - Submit dua kali.
  - Guru mengedit/menghapus supervisi yang sudah direview atau selesai.
  - Kepala sekolah menyelesaikan evaluasi yang belum disubmit.
  - Jalur feedback Admin vs Kepala Sekolah yang saling menimpa.
- Konsistensi enum status antara migrasi, model, dan controller.

### Lapis 3 — Bug Umum

- Jalankan `php artisan test` penuh; catat test yang gagal.
- Periksa file yang banyak berubah (belum di-commit): controller, migrasi, seeder, middleware.
- Konsistensi migrasi vs model vs pemakaian kolom di view/controller.

### Lapis 4 — UX (Kemudahan Penggunaan)

- Validasi form & pesan error berbahasa Indonesia yang jelas.
- Umpan balik setelah aksi (redirect + notifikasi sukses/gagal).
- Empty state pada daftar kosong.
- Navigasi antar-halaman dan konsistensi tombol/aksi.
- Redesign visual diperbolehkan bila temuan UX memerlukannya (persetujuan pengguna: 2026-07-04).

## Bentuk Keluaran

Satu laporan temuan di percakapan, dikelompokkan **Kritis / Tinggi / Sedang / Rendah**. Setiap temuan memuat: lokasi file (path:baris), dampak, dan usulan perbaikan. Perbaikan dikerjakan mulai dari Kritis setelah persetujuan per kelompok.

## Cara Perbaikan

- Perbaikan langsung di working tree; tidak ada commit tanpa persetujuan pengguna.
- Perbaikan keamanan/alur kerja disertai test bila memungkinkan (TDD Guard aktif, reporter PHPUnit terpasang).
- Verifikasi akhir: jalankan ulang test suite penuh.

## Di Luar Lingkup

- Fitur baru.
- Upgrade dependensi besar — kecuali tambalan keamanan kritis dari hasil `composer audit`.
