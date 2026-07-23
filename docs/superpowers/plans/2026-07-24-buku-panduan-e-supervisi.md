# Buku Panduan Penggunaan E-Supervisi — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Menghasilkan buku panduan resmi E-Supervisi berupa berkas HTML rapi bergaya buku pedoman e-Arsip, dengan slot gambar placeholder yang mudah diganti sendiri oleh pengguna, plus tombol render ke PDF.

**Architecture:** Satu berkas HTML mandiri (`docs/Buku-Panduan-E-Supervisi.html`) memakai ulang sistem desain modul lama, isi mengikuti format buku e-Arsip. Setiap gambar adalah komponen `.shot`: kotak placeholder berlabel rapi berisi `<img>` yang menunjuk ke `img-panduan/NN-nama.png`. Bila file belum ada, `onerror` menyembunyikan `<img>` sehingga hanya placeholder rapi yang tampil. Begitu pengguna menaruh file PNG bernama sesuai, gambar otomatis muncul mengisi kotak tanpa mengubah tata letak. Berkas `render-pdf.bat` menghasilkan PDF via headless Chrome.

**Tech Stack:** HTML/CSS cetak A4, font Inter lokal, headless Chrome untuk PDF. Tidak menjalankan aplikasi Laravel (screenshot diisi pengguna).

## Global Constraints

- Bahasa Indonesia baku dan formal. **DILARANG memakai tanda pisah em-dash (—)** di seluruh isi buku. Ganti dengan titik, kata sambung, atau tanda kurung. Hindari koma yang dipaksakan. Tidak ada jejak gaya AI.
- **RAPI adalah syarat utama.** Tidak boleh ada elemen berantakan: tidak ada gambar rusak (broken icon), tidak ada teks meluber keluar kotak, tidak ada tabel terpotong antar halaman, margin konsisten.
- Jangan mengubah `docs/modul-penggunaan.html` (modul lama tetap utuh).
- Jangan menjalankan atau mengubah aplikasi. Tidak perlu seeder atau data contoh.
- Brand: teal `#0F766E`, amber `#F59E0B`, font Inter (embed `docs/InterVariable*.woff2`).
- Identitas buku: Aplikasi "E-Supervisi Pembelajaran" Yayasan Az-Zahroh; alamat `https://e-supervisiazzahroh.com/`; tahun 2026; penyusun `[Nama Penyusun]` (placeholder harfiah, biarkan dalam kurung siku); kota pada Kata Pengantar `[Kota]`.
- Fakta produk (WAJIB akurat, dari spec): login peran + NIK + sandi, batas 5 percobaan/menit, sesi 10 menit idle, ganti sandi wajib saat login pertama. Status: draft, submitted, under_review, revision, completed. Guru satu supervisi aktif. 7 dokumen wajib (Capaian Pembelajaran, Alur Tujuan Pembelajaran, Kalender Pendidikan, Program Tahunan, Program Semester, Modul Ajar, Bahan Ajar), format PDF/JPG/PNG maks 2 MB. 5 refleksi maks 500 karakter (tab Proses terbuka setelah 7 dokumen). Rubrik 47 aspek, 3 bagian (A Pendahuluan, B Inti, C Penutup), skor 0/1/2. Predikat Sangat Baik ≥91, Baik 81-80, Cukup 71-80, Kurang <71. Kepala: rubrik dulu baru umpan balik lalu selesai/minta revisi. Pengingat otomatis Senin & Kamis 07.00. Modul ajar PDF maks 20 MB + video, progres baca terekam.
- **Konvensi nama file placeholder** (dipakai konsisten di semua tugas): dua digit urut + deskripsi kebab-case, mis. `img-panduan/01-login.png`, `04-admin-dashboard.png`. Nomor gambar pada caption (Gambar X.Y) mengikuti nomor bab, dan setiap caption menyebut nama file placeholdernya.

---

### Task 1: Kerangka HTML, sistem desain, komponen placeholder, bagian depan, render script

**Files:**
- Create: `docs/Buku-Panduan-E-Supervisi.html`
- Create: `docs/render-pdf.bat`
- Create: `docs/img-panduan/README.txt`
- Reference: `docs/modul-penggunaan.html` (sumber sistem desain untuk diadaptasi)

**Interfaces:**
- Produces: berkas HTML dengan blok `<style>` lengkap termasuk komponen `.shot` (placeholder gambar), `.running-head`, `.idtable`, `.figcap`, `.matrix`; serta Sampul, Kata Pengantar, dan Daftar Isi. Komponen `.shot` dipakai semua tugas berikutnya dengan pola HTML tetap.

- [ ] **Step 1: Salin & adaptasi sistem desain dari modul lama**

Buat `docs/Buku-Panduan-E-Supervisi.html`. Ambil blok `<style>` dari `docs/modul-penggunaan.html` (token warna, font Inter, `@page A4`, tabel `.t`, callout `.note`, langkah `.flow/.step`, cover, divider) sebagai dasar. Pertahankan brand teal/amber.

- [ ] **Step 2: Tambah komponen placeholder gambar `.shot` (kunci kerapian)**

Tambahkan CSS agar placeholder selalu rapi dan tidak pernah menampilkan broken-icon. Pola:
```css
.shot{ position:relative; width:100%; aspect-ratio:16/10; border:1px solid var(--mist-line);
  border-radius:8px; overflow:hidden; background:
  repeating-linear-gradient(45deg,#F3F0E8,#F3F0E8 10px,#EEEAE0 10px,#EEEAE0 20px);
  display:flex; align-items:center; justify-content:center; margin:3mm 0 1.5mm; break-inside:avoid; }
.shot__ph{ text-align:center; color:var(--muted); padding:6mm; }
.shot__ph b{ display:block; color:var(--teal-deep); font-size:9.4pt; margin-bottom:1mm; }
.shot__ph small{ font-size:7.8pt; }
.shot__img{ position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
  object-position:top center; background:#fff; }
.figcap{ font-size:8pt; color:var(--muted); text-align:center; font-style:italic; margin:0 0 5mm; }
```
Pola HTML tiap gambar (dipakai konsisten):
```html
<figure class="fig">
  <div class="shot">
    <div class="shot__ph"><b>Gambar 4.1</b><small>Letakkan screenshot: img-panduan/04-admin-dashboard.png</small></div>
    <img class="shot__img" src="img-panduan/04-admin-dashboard.png" alt=""
         onerror="this.style.display='none'">
  </div>
  <figcaption class="figcap">Gambar 4.1 Dashboard Administrator</figcaption>
</figure>
```
`onerror` menyembunyikan `<img>` bila file belum ada, jadi hanya placeholder yang tampil (tetap rapi, tanpa ikon rusak).

- [ ] **Step 3: Tulis Sampul + tabel identitas**

Sampul bergaya buku pedoman: eyebrow "MODUL PENGGUNAAN SISTEM", judul "BUKU PANDUAN DAN PEDOMAN PENGGUNAAN APLIKASI E-SUPERVISI PEMBELAJARAN" dan subjudul "YAYASAN AZ-ZAHROH", sebuah `.shot` untuk `01-login.png` sebagai gambar sampul, lalu `.idtable` berisi baris: Alamat aplikasi (`https://e-supervisiazzahroh.com/`), Jenis dokumen (Panduan operasional penggunaan aplikasi), Sasaran pengguna (Administrator, Kepala Sekolah, dan Guru), Tahun (2026). Tanpa em-dash di teks.

- [ ] **Step 4: Tulis Kata Pengantar + Daftar Isi**

Kata Pengantar: tiga paragraf formal (latar penyusunan, isi ringkas, harapan penyempurnaan), ditutup "`[Kota]`, 2026" dan "`[Nama Penyusun]`". Daftar Isi: Kata Pengantar, Daftar Isi, BAB I sampai BAB IX, Lampiran, dengan kolom nomor halaman (boleh manual, isi wajar).

- [ ] **Step 5: Tulis render-pdf.bat + README folder gambar**

`docs/render-pdf.bat`: batch Windows yang mencari Chrome (cek `%ProgramFiles%\Google\Chrome\Application\chrome.exe` dan `%ProgramFiles(x86)%\...` dan `%LocalAppData%\...`), lalu menjalankan:
```
chrome.exe --headless=new --disable-gpu --no-pdf-header-footer --print-to-pdf="%~dp0Buku-Panduan-E-Supervisi.pdf" "file:///%~dp0Buku-Panduan-E-Supervisi.html"
```
(gunakan `%~dp0` agar path absolut relatif ke lokasi bat). Tampilkan pesan sukses & lokasi PDF.
`docs/img-panduan/README.txt`: instruksi singkat berbahasa Indonesia. Isi: cara mengganti placeholder (taruh file PNG/JPG dengan nama persis seperti tertulis di tiap kotak, mis. `04-admin-dashboard.png`), saran rasio gambar 16:10 (mis. 1440x900) agar pas, lalu klik `render-pdf.bat` untuk membuat PDF.

- [ ] **Step 6: Verifikasi render awal**

Buka `docs/Buku-Panduan-E-Supervisi.html` langsung di browser. Pastikan: font Inter termuat, sampul rapi, tabel identitas rapi, placeholder gambar sampul tampil sebagai kotak berlabel (bukan ikon rusak), Daftar Isi rapi. Jalankan `grep -c "—" docs/Buku-Panduan-E-Supervisi.html` → harus 0.
Expected: bagian depan rapi, 0 em-dash, tidak ada broken image.

- [ ] **Step 7: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html docs/render-pdf.bat docs/img-panduan/README.txt
git commit -m "docs: kerangka buku panduan (desain, placeholder gambar, sampul, kata pengantar, daftar isi, render script)"
```

---

### Task 2: BAB I–III (Pendahuluan, Gambaran Umum, Persyaratan & Hak Akses)

**Files:**
- Modify: `docs/Buku-Panduan-E-Supervisi.html`

**Interfaces:**
- Consumes: komponen `.shot`, `.t`, `.note`, `.flow` dari Task 1.

- [ ] **Step 1: BAB I Pendahuluan**

1.1 Latar Belakang, 1.2 Tujuan Penyusunan Pedoman (daftar a-e), 1.3 Ruang Lingkup. Konteks supervisi pembelajaran di lingkungan Yayasan Az-Zahroh. Nada formal seperti e-Arsip. Tanpa em-dash.

- [ ] **Step 2: BAB II Gambaran Umum Aplikasi**

2.1 Deskripsi Singkat. 2.2 Manfaat (daftar a-e). 2.3 Menu Utama dengan tabel `.t` kolom Menu / Fungsi Utama / Ketersediaan Akses (baris untuk Dashboard, Supervisi/Evaluasi, Rubrik, Modul Ajar, Pengguna, Carousel, Notifikasi, Pengaturan; tandai akses Admin/Kepala/Guru). Sisipkan `.shot` `03-dashboard.png` sebagai Gambar 2.1. 2.4 Alur Siklus Supervisi dan Arti Status (jelaskan draft, submitted, under_review, revision, completed dalam kalimat + tabel status/arti/pelaku). 2.5 Istilah Penting (tabel Istilah/Makna: Supervisi, Rubrik, Aspek, Predikat, Refleksi, Modul Ajar, Notifikasi).

- [ ] **Step 3: BAB III Persyaratan, Akun, dan Hak Akses**

3.1 Persyaratan Sistem (perangkat, browser, koneksi, akun dari admin). 3.2 Ketentuan Akun. 3.3 Tiga Peran Pengguna (tabel Peran/Karakteristik Akses untuk Administrator, Kepala Sekolah, Guru). 3.4 Matriks Hak Akses (tabel Fitur x tiga peran). 3.5 Cara Login (langkah `.flow` + `.shot` `02-login.png` sebagai Gambar 3.1; sebut batas 5 percobaan/menit). 3.6 Ganti Kata Sandi Pertama Kali (langkah + `.shot` `03-ganti-sandi.png`). 3.7 Sesi Berakhir Otomatis (10 menit, callout).

- [ ] **Step 4: Verifikasi**

Buka di browser, telusuri BAB I-III. `grep -c "—"` = 0. Pastikan tabel tidak meluber, placeholder rapi.
Expected: BAB I-III lengkap, rapi, 0 em-dash.

- [ ] **Step 5: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: BAB I-III buku panduan"
```

---

### Task 3: BAB IV–VI (Panduan Administrator, Kepala Sekolah, Guru)

**Files:**
- Modify: `docs/Buku-Panduan-E-Supervisi.html`

- [ ] **Step 1: BAB IV Panduan Administrator**

4.1 Dashboard (Gambar 4.1 `04-admin-dashboard.png`). 4.2 Manajemen Pengguna: tambah, sunting, reset kata sandi, aktif/nonaktif (langkah + Gambar 4.2 `05-admin-pengguna.png`; callout hati-hati menghapus). 4.3 Tinjau Supervisi (Gambar 4.3 `06-admin-supervisi.png`). 4.4 Kelola Rubrik dan Predikat (Gambar 4.4 `07-admin-rubrik.png`). 4.5 Kelola Modul Ajar, PDF maks 20 MB + video (Gambar 4.5 `08-admin-modul.png`). 4.6 Kelola Carousel Beranda (Gambar 4.6 `09-admin-carousel.png`). Tiap sub-bab: paragraf pengantar + langkah bernomor.

- [ ] **Step 2: BAB V Panduan Kepala Sekolah**

5.1 Daftar Evaluasi (Gambar 5.1 `10-kepala-evaluasi.png`). 5.2 Periksa Dokumen dan Refleksi Guru (Gambar 5.2 `11-kepala-detail.png`). 5.3 Mulai Tinjau (status jadi under_review). 5.4 Langkah Pertama Isi Rubrik 47 Aspek, tiga bagian A/B/C skor 0/1/2 (Gambar 5.3 `12-kepala-rubrik.png`; callout rubrik dulu baru bisa selesai). 5.5 Langkah Kedua Beri Umpan Balik (Gambar 5.4 `13-kepala-umpanbalik.png`). 5.6 Selesaikan atau Minta Revisi. 5.7 Predikat Hasil Penilaian (tabel/skala Sangat Baik ≥91, Baik 81-90, Cukup 71-80, Kurang <71). 5.8 Rekap Progres Modul Ajar (Gambar 5.5 `14-kepala-rekap.png`).

- [ ] **Step 3: BAB VI Panduan Guru**

6.1 Mengajukan Supervisi, satu supervisi aktif pada satu waktu (Gambar 6.1 `15-guru-beranda.png`). 6.2 Mengunggah Tujuh Dokumen Wajib: tabel 7 dokumen + Gambar 6.2 `16-guru-dokumen.png`; format PDF/JPG/PNG maks 2 MB; callout tab Proses terbuka setelah 7 dokumen. 6.3 Mengisi Proses Pembelajaran, lima refleksi maks 500 karakter (daftar 5 pertanyaan + Gambar 6.3 `17-guru-proses.png`). 6.4 Mengirim untuk Dinilai (status submitted). 6.5 Menanggapi Permintaan Revisi (langkah). 6.6 Melihat dan Mengunduh Hasil Penilaian (Gambar 6.4 `18-guru-hasil.png`). 6.7 Modul Ajar (Gambar 6.5 `19-guru-modul.png`).

- [ ] **Step 4: Verifikasi**

Buka di browser, cek tiap BAB memuat placeholder gambar bernomor benar dan langkah rapi. `grep -c "—"` = 0.
Expected: 3 bab panduan lengkap, rapi.

- [ ] **Step 5: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: BAB IV-VI panduan per peran"
```

---

### Task 4: BAB VII–IX + Lampiran

**Files:**
- Modify: `docs/Buku-Panduan-E-Supervisi.html`

- [ ] **Step 1: BAB VII Pedoman Operasional dan Keamanan Data**

7.1 Keamanan Akun dan Kata Sandi. 7.2 Notifikasi dan Pengingat Otomatis: lonceng notifikasi (Gambar 7.1 `20-notifikasi.png`), pengingat Senin dan Kamis pukul 07.00. 7.3 Pengelolaan Dokumen (kualitas berkas, penamaan yang jelas). 7.4 Backup dan Pemulihan (saran prosedur berkala).

- [ ] **Step 2: BAB VIII Pemecahan Masalah**

Tabel `.t` kolom Masalah / Kemungkinan Penyebab / Saran Penanganan. Baris minimal: gagal login/terlalu banyak percobaan, diminta ganti sandi terus, tab Proses terkunci (Guru), berkas ditolak saat unggah, evaluasi tak bisa diselesaikan (Kepala), tak bisa membuat supervisi baru (Guru), keluar sendiri (sesi 10 menit), gambar/dokumen tidak muncul.

- [ ] **Step 3: BAB IX Penutup + Lampiran**

BAB IX Penutup: dua paragraf formal. Lampiran 1 Checklist Kesiapan Implementasi: tabel No/Komponen yang Dicek/Status/Keterangan (baris relevan: alamat aplikasi dapat diakses, akun admin aktif, akun kepala & guru dibuat, rubrik 47 aspek siap, modul ajar terunggah, uji alur supervisi satu siklus, notifikasi berjalan, dsb). Lampiran 2 Daftar Tujuh Dokumen Wajib Supervisi (daftar bernomor). Footer dokumen: judul buku, versi, tahun.

- [ ] **Step 4: Verifikasi**

Buka di browser dari sampul sampai lampiran. `grep -c "—"` = 0. Pastikan tabel tidak terpotong buruk.
Expected: buku lengkap 9 BAB + lampiran, rapi.

- [ ] **Step 5: Commit**

```
git add docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: BAB VII-IX + lampiran buku panduan"
```

---

### Task 5: Render PDF, uji kerapian menyeluruh, rapikan

**Files:**
- Create: `docs/Buku-Panduan-E-Supervisi.pdf`
- Modify (bila perlu perbaikan kerapian): `docs/Buku-Panduan-E-Supervisi.html`

- [ ] **Step 1: Render PDF**

Jalankan `docs/render-pdf.bat` (atau perintah headless Chrome setara dengan path absolut). Hasil: `docs/Buku-Panduan-E-Supervisi.pdf`.

- [ ] **Step 2: Audit kerapian PDF (syarat utama)**

Buka PDF, periksa tiap halaman: (a) tidak ada tabel/kotak terpotong antar halaman secara buruk, (b) placeholder gambar utuh dan sejajar, (c) heading tidak menggantung sendiri di dasar halaman, (d) margin konsisten, (e) tidak ada teks meluber, (f) Daftar Isi cocok urutannya, (g) tidak ada em-dash. Catat masalah.

- [ ] **Step 3: Perbaiki kerapian bila ada temuan**

Terapkan perbaikan CSS/markup (mis. `break-inside:avoid`, `break-before`, penyesuaian tinggi `.shot`, pemenggalan tabel) lalu render ulang sampai bersih. Ulangi Step 1-2 hingga tidak ada temuan.

- [ ] **Step 4: Commit final**

```
git add docs/Buku-Panduan-E-Supervisi.pdf docs/Buku-Panduan-E-Supervisi.html
git commit -m "docs: buku panduan penggunaan E-Supervisi (PDF final + rapikan)"
```

---

## Catatan eksekusi

- Placeholder yang SENGAJA dibiarkan di produk akhir hanya `[Nama Penyusun]` dan `[Kota]` pada Kata Pengantar, serta kotak gambar placeholder (memang untuk diisi pengguna). Semua teks lain harus terisi nyata.
- Kerapian di atas segalanya. Bila ragu antara padat dan rapi, pilih rapi.
- Nomor & nama file gambar harus konsisten antar caption, placeholder, dan README.
