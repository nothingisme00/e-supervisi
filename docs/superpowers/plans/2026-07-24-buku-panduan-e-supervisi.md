# Buku Panduan Penggunaan E-Supervisi — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menghasilkan buku panduan resmi E-Supervisi (`docs/Buku-Panduan-E-Supervisi.pdf`) bergaya buku pedoman e-Arsip, dengan screenshot asli aplikasi, kualitas visual profesional, tanpa jejak gaya AI.

**Architecture:** Pipeline berurutan. (1) Siapkan data contoh agar semua status supervisi terlihat. (2) Jalankan app lokal, otomasi login 3 peran, tangkap ~20 screenshot ke `docs/img-panduan/`. (3) Bangun satu berkas HTML mandiri (`docs/Buku-Panduan-E-Supervisi.html`) memakai ulang sistem desain modul lama, isi mengikuti format e-Arsip, screenshot di-embed. (4) Render ke PDF via headless Chrome dan verifikasi.

**Tech Stack:** Laravel 10 (PHP 8.2, MySQL), Playwright (browser otomasi), HTML/CSS cetak A4, headless Chrome untuk PDF, font Inter lokal.

## Global Constraints

- Bahasa Indonesia baku dan formal. **DILARANG memakai tanda pisah em-dash (—)** di seluruh isi buku. Ganti dengan titik, kata sambung, atau tanda kurung. Hindari koma yang dipaksakan.
- Jangan mengubah `docs/modul-penggunaan.html` (modul lama tetap utuh).
- Jangan menambah/mengubah fitur aplikasi. Data contoh hanya untuk screenshot (lokal).
- Brand: teal `#0F766E`, amber `#F59E0B`, font Inter (embed `docs/InterVariable*.woff2`).
- Identitas buku: Aplikasi "E-Supervisi Pembelajaran" Yayasan Az-Zahroh; alamat `https://e-supervisiazzahroh.com/`; tahun 2026; penyusun `[Nama Penyusun]` (placeholder harfiah, biarkan dalam kurung siku).
- WAJIB jalankan `php artisan config:clear` sebelum operasi artisan apa pun (mencegah insiden DB dev terhapus oleh cache test). Rujuk memori div-balance-dan-insiden-db.
- Fakta produk yang dipakai di isi buku HARUS sesuai spec (7 dokumen, 5 refleksi, rubrik 47 aspek 3 bagian skor 0/1/2, predikat SB≥91/B81-90/C71-80/K<71, sesi 10 menit, batas login 5/menit, pengingat Senin & Kamis 07.00, guru satu supervisi aktif).

---

### Task 1: Siapkan data contoh untuk screenshot

**Files:**
- Create: `database/seeders/DemoPanduanSeeder.php`
- Read (untuk verifikasi field): `app/Models/User.php`, `app/Models/DokumenEvaluasi.php`, `app/Http/Controllers/Guru/*`, `app/Models/RubrikItem.php`

**Interfaces:**
- Produces: akun `guru`/`kepala`/`admin` yang bisa login, dan minimal 3 supervisi contoh berstatus `submitted` (untuk ditinjau kepala), `completed` (untuk tampilkan rubrik + predikat + unduh), serta satu `draft`/`revision` milik guru demo (untuk tab dokumen & proses).

- [ ] **Step 1: Baca skema terkait agar seeder benar**

Baca `app/Models/User.php` (kolom fillable: nama, nik, role, mata_pelajaran, is_active, dst.), `app/Models/DokumenEvaluasi.php` (enum `jenis_dokumen` dari mana), `app/Models/RubrikItem.php`, dan controller guru untuk tahu daftar `jenis_dokumen` ketujuh berkas. Catat nilai persisnya.

- [ ] **Step 2: Tulis DemoPanduanSeeder**

Buat seeder idempoten (`updateOrCreate` untuk user berdasarkan `nik`). Buat/gunakan:
- Guru demo (mis. nama "Budi Santoso", mata pelajaran "Matematika", role `guru`, is_active true, password diketahui).
- Kepala sekolah demo (role `kepala_sekolah` sesuai nilai role di kode, is_active true).
- Untuk supervisi `completed`: buat baris supervisi milik guru demo, lampirkan 7 DokumenEvaluasi (boleh menunjuk 1 file contoh PDF/JPG dummy di `storage`), 5 refleksi pada ProsesPembelajaran, dan skor rubrik lengkap (isi EvaluasiRubrik + EvaluasiRubrikScore untuk seluruh RubrikItem aktif dengan skor variatif 1/2), plus Feedback.
- Untuk supervisi `submitted`: guru demo kedua, 7 dokumen + 5 refleksi, tanpa rubrik.
- Untuk `draft`/`revision`: guru demo, sebagian dokumen terisi (mis. 5/7) agar tab dokumen memperlihatkan progres.

Gunakan struktur field persis hasil Step 1. Jangan menebak nama kolom.

- [ ] **Step 3: Jalankan seeder & verifikasi**

Run:
```
php artisan config:clear
php artisan db:seed --class=DemoPanduanSeeder
php artisan tinker --execute="echo \App\Models\Supervisi::selectRaw('status,count(*) c')->groupBy('status')->get();"
```
Expected: muncul baris untuk `submitted`, `completed`, dan `draft`/`revision` (masing-masing ≥1).

- [ ] **Step 4: Commit**

```
git add database/seeders/DemoPanduanSeeder.php
git commit -m "chore: seeder data contoh untuk screenshot buku panduan"
```

---

### Task 2: Tangkap screenshot asli dari app lokal

**Files:**
- Create: `docs/img-panduan/` (folder output PNG)
- Create: `scripts/tmp-screenshot-panduan.mjs` (skrip Playwright sementara, boleh dibuang setelah selesai; ATAU pakai tool browser Playwright langkah demi langkah)

**Interfaces:**
- Consumes: akun & data dari Task 1.
- Produces: berkas PNG bernama jelas di `docs/img-panduan/`, mis. `01-login.png`, `03-admin-dashboard.png`, ... `20-guru-modul.png`.

- [ ] **Step 1: Jalankan aplikasi**

Run (background): `php artisan serve --port=8123`
Verifikasi `http://localhost:8123/login` memuat halaman login.

- [ ] **Step 2: Susun daftar target & rute**

Baca `routes/web.php` untuk konfirmasi rute tiap layar (admin.dashboard, admin.users.index, kepala.*, guru.home, dst.). Cocokkan dengan daftar 20 screenshot di spec.

- [ ] **Step 3: Otomasi tangkap layar per peran**

Untuk tiap peran (admin, kepala, guru): login lewat form (pilih peran, isi NIK, isi sandi). Jika muncul layar ganti sandi pertama, tangkap dulu lalu selesaikan. Set viewport lebar desktop (mis. 1440x900), tunggu jaringan idle, ambil screenshot tiap rute. Simpan ke `docs/img-panduan/` dengan penamaan berurut sesuai spec. Tangani sesi 10 menit dengan menyelesaikan tiap peran tanpa jeda panjang.

Catatan kendala diketahui: screenshot penuh halaman kadang timeout; bila terjadi, ambil per-viewport atau elemen utama (rujuk mobile-ui-fix: pakai geometry/elemen bila full-page gagal).

- [ ] **Step 4: Verifikasi kelengkapan gambar**

Run: `ls docs/img-panduan/`
Expected: ~18–20 PNG, tidak ada yang berukuran 0 byte. Buka 2–3 secara acak untuk memastikan kontennya benar (bukan halaman error/login yang gagal).

- [ ] **Step 5: Commit**

```
git add docs/img-panduan
git commit -m "docs: screenshot asli aplikasi untuk buku panduan"
```

---

### Task 3: Bangun kerangka HTML buku (desain + bagian depan)

**Files:**
- Create: `docs/Buku-Panduan-E-Supervisi.html`
- Reference: `docs/modul-penggunaan.html` (sumber sistem desain untuk diadaptasi)

**Interfaces:**
- Produces: berkas HTML dengan blok `<style>` lengkap (adaptasi token & komponen dari modul lama + gaya khas buku e-Arsip: header berjalan, kotak identitas sampul, caption "Gambar X.Y", tabel matriks), plus Sampul, Kata Pengantar, dan Daftar Isi.

- [ ] **Step 1: Salin & adaptasi sistem desain**

Buat `docs/Buku-Panduan-E-Supervisi.html`. Ambil blok `<style>` dari `docs/modul-penggunaan.html` sebagai dasar. Tambah gaya yang khas buku e-Arsip:
- `.running-head` (teks kecil kanan-atas tiap halaman berisi judul buku).
- `.figure` + `figcaption` bergaya italic terpusat untuk caption "Gambar X.Y ...".
- `.idtable` (tabel identitas sampul: Alamat aplikasi, Jenis dokumen, Sasaran pengguna, Tahun).
- `.matrix` untuk tabel matriks hak akses.
- Screenshot memakai `max-width:100%`, border tipis, `break-inside:avoid`.

- [ ] **Step 2: Tulis Sampul + Kata Pengantar + Daftar Isi**

- Sampul: judul buku "BUKU PANDUAN DAN PEDOMAN PENGGUNAAN APLIKASI E-SUPERVISI PEMBELAJARAN — YAYASAN AZ-ZAHROH" (tanpa em-dash di isi paragraf; pada judul boleh pakai baris terpisah), screenshot login sebagai gambar sampul, lalu tabel identitas.
- Kata Pengantar: paragraf pembuka formal, ditutup tempat & tanggal ("`[Kota]`, 2026") dan "`[Nama Penyusun]`".
- Daftar Isi: daftar 9 BAB + Lampiran dengan nomor halaman (boleh manual).

- [ ] **Step 3: Verifikasi render awal**

Run (dari folder docs): `php -S 127.0.0.1:8391` lalu buka `http://127.0.0.1:8391/Buku-Panduan-E-Supervisi.html` di browser Playwright. Ambil screenshot sampul untuk cek visual.
Expected: sampul rapi, font Inter termuat, tabel identitas tampil, tanpa em-dash.

- [ ] **Step 4: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: kerangka HTML buku panduan (desain, sampul, kata pengantar, daftar isi)"
```

---

### Task 4: Isi BAB I–III (Pendahuluan, Gambaran Umum, Hak Akses)

**Files:**
- Modify: `docs/Buku-Panduan-E-Supervisi.html`

- [ ] **Step 1: Tulis BAB I Pendahuluan**

Sub-bab 1.1 Latar Belakang, 1.2 Tujuan Penyusunan, 1.3 Ruang Lingkup. Nada formal seperti e-Arsip, konteks supervisi pembelajaran Yayasan Az-Zahroh. Tanpa em-dash.

- [ ] **Step 2: Tulis BAB II Gambaran Umum**

2.1 Deskripsi Singkat, 2.2 Manfaat, 2.3 Menu Utama (tabel Menu/Fungsi/Ketersediaan Akses untuk Admin, Kepala, Guru), 2.4 Alur Siklus Supervisi & Arti Status (draft/submitted/under_review/revision/completed), 2.5 Istilah Penting (tabel). Sisipkan screenshot dashboard sebagai Gambar 2.1.

- [ ] **Step 3: Tulis BAB III Persyaratan, Akun, dan Hak Akses**

3.1 Persyaratan Sistem, 3.2 Ketentuan Akun, 3.3 Tiga Peran Pengguna (tabel), 3.4 Matriks Hak Akses (tabel fitur x peran), 3.5 Cara Login (langkah + Gambar login), 3.6 Ganti Kata Sandi Pertama (Gambar), 3.7 Sesi Berakhir Otomatis. Fakta: batas 5 percobaan/menit, sesi 10 menit idle.

- [ ] **Step 4: Verifikasi**

Buka ulang di browser lokal, telusuri BAB I–III. Cari string em-dash: `grep -c "—" docs/Buku-Panduan-E-Supervisi.html` harus `0` pada teks isi (kecuali jika sengaja di elemen non-teks; targetkan 0).
Expected: 0 em-dash; screenshot BAB tampil benar.

- [ ] **Step 5: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: BAB I-III buku panduan"
```

---

### Task 5: Isi BAB IV–VI (Panduan Administrator, Kepala Sekolah, Guru)

**Files:**
- Modify: `docs/Buku-Panduan-E-Supervisi.html`

- [ ] **Step 1: BAB IV Panduan Administrator**

Sub-bab: 4.1 Dashboard, 4.2 Manajemen Pengguna (tambah/sunting/reset sandi/aktif-nonaktif), 4.3 Tinjau Supervisi, 4.4 Kelola Rubrik & Predikat, 4.5 Kelola Modul Ajar (PDF maks 20 MB + video), 4.6 Kelola Carousel. Tiap sub-bab: paragraf + langkah bernomor + Gambar 4.x (screenshot admin terkait).

- [ ] **Step 2: BAB V Panduan Kepala Sekolah**

5.1 Daftar Evaluasi, 5.2 Periksa Dokumen Guru, 5.3 Mulai Tinjau, 5.4 Langkah 1 Isi Rubrik 47 Aspek (3 bagian A/B/C, skor 0/1/2), 5.5 Langkah 2 Umpan Balik, 5.6 Selesaikan atau Minta Revisi, 5.7 Predikat Hasil (tabel SB/B/C/K), 5.8 Rekap Progres Modul. Sisipkan Gambar rubrik & hasil. Tegaskan: rubrik dulu baru bisa selesai.

- [ ] **Step 3: BAB VI Panduan Guru**

6.1 Ajukan Supervisi (satu supervisi aktif), 6.2 Unggah 7 Dokumen Wajib (tabel + Gambar tab dokumen; format PDF/JPG/PNG maks 2 MB), 6.3 Isi Proses Pembelajaran (5 refleksi maks 500 karakter, syarat 7 dokumen dulu), 6.4 Kirim untuk Dinilai, 6.5 Menanggapi Revisi, 6.6 Melihat & Mengunduh Hasil, 6.7 Modul Ajar.

- [ ] **Step 4: Verifikasi**

Buka di browser lokal, pastikan tiap BAB memuat screenshot yang benar dan tidak ada em-dash. `grep -c "—"` tetap 0.
Expected: 3 bab panduan lengkap dengan gambar sesuai peran.

- [ ] **Step 5: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: BAB IV-VI panduan per peran"
```

---

### Task 6: Isi BAB VII–IX + Lampiran

**Files:**
- Modify: `docs/Buku-Panduan-E-Supervisi.html`

- [ ] **Step 1: BAB VII Pedoman Operasional dan Keamanan Data**

7.1 Kebiasaan Aman Akun, 7.2 Notifikasi (lonceng + Gambar) & Pengingat Otomatis (Senin & Kamis 07.00), 7.3 Penamaan/Pengelolaan Dokumen, 7.4 Backup dan Pemulihan.

- [ ] **Step 2: BAB VIII Pemecahan Masalah**

Tabel Kendala/Penyebab/Solusi (adaptasi FAQ dari modul lama ke gaya tabel e-Arsip): gagal login, diminta ganti sandi terus, tab Proses terkunci, berkas ditolak, evaluasi tak bisa diselesaikan, tak bisa buat supervisi baru, keluar sendiri, gambar tak muncul.

- [ ] **Step 3: BAB IX Penutup + Lampiran**

Penutup ringkas formal. Lampiran 1: Checklist Kesiapan Implementasi (tabel No/Komponen/Status/Keterangan). Lampiran 2: contoh yang relevan (mis. daftar 7 dokumen wajib atau contoh penamaan berkas).

- [ ] **Step 4: Verifikasi**

Buka lokal, cek seluruh dokumen mengalir dari sampul sampai lampiran. `grep -c "—"` = 0.
Expected: buku lengkap 9 BAB + lampiran.

- [ ] **Step 5: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: BAB VII-IX + lampiran buku panduan"
```

---

### Task 7: Render PDF & verifikasi akhir

**Files:**
- Create: `docs/Buku-Panduan-E-Supervisi.pdf`

- [ ] **Step 1: Render PDF via headless Chrome**

Run (path absolut wajib):
```
chrome.exe --headless=new --disable-gpu --no-pdf-header-footer \
  --print-to-pdf="<abs>/docs/Buku-Panduan-E-Supervisi.pdf" \
  "file:///<abs>/docs/Buku-Panduan-E-Supervisi.html"
```
(Jika `chrome.exe` tidak di PATH, gunakan path instalasi Chrome; rujuk memori modul-penggunaan-pdf.)

- [ ] **Step 2: Verifikasi PDF**

Buka PDF, periksa: sampul full, halaman tidak terpotong, screenshot tajam & tidak melebihi margin, caption benar, daftar isi cocok, tidak ada em-dash. Hitung halaman wajar (target 18–24 hal).
Expected: PDF rapi dan profesional, siap dibagikan.

- [ ] **Step 3: Bersihkan berkas sementara**

Hapus `scripts/tmp-screenshot-panduan.mjs` bila dibuat. Pertimbangkan menonaktifkan/menghapus data DemoPanduanSeeder dari DB lokal bila mengganggu (opsional; seeder tetap disimpan di repo).

- [ ] **Step 4: Commit final**

```
git add docs/Buku-Panduan-E-Supervisi.pdf
git commit -m "docs: buku panduan penggunaan E-Supervisi (PDF final)"
```

---

## Catatan eksekusi

- Hentikan `php artisan serve` (background) setelah Task 2 & 3 selesai bila tidak dipakai.
- Bila screenshot gagal untuk layar tertentu (mis. rute butuh data yang belum ada), kembali ke Task 1 lengkapi data, jangan memaksakan gambar palsu.
- Placeholder yang SENGAJA dibiarkan di produk akhir hanya: `[Nama Penyusun]` dan `[Kota]` pada Kata Pengantar. Semua lainnya harus terisi nyata.
