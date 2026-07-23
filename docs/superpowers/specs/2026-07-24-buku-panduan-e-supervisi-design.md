# Desain: Buku Panduan Penggunaan E-Supervisi

**Tanggal:** 2026-07-24
**Status:** Disetujui (design), menunggu implementasi

## Tujuan

Membuat buku panduan resmi penggunaan aplikasi E-Supervisi Pembelajaran (Yayasan Az-Zahroh)
yang mengikuti **format dan struktur buku pedoman e-Arsip SMA Negeri 8 Medan** (gaya buku
pedoman resmi: Kata Pengantar, Daftar Isi, BAB bernomor, Lampiran), lengkap dengan
**screenshot asli aplikasi**, berkualitas visual profesional, tanpa jejak gaya AI.

## Keputusan yang sudah diambil

1. **Sumber screenshot:** jalankan aplikasi lokal (`localhost:8123`), tambah data contoh,
   login sebagai tiga peran, tangkap layar asli via browser otomatis (Playwright).
2. **Identitas buku:** pakai brand yang ada, placeholder untuk nama penyusun.
   - Aplikasi: E-Supervisi Pembelajaran, Yayasan Az-Zahroh
   - Alamat aplikasi: `https://e-supervisiazzahroh.com/`
   - Tahun: 2026 · Penyusun: `[Nama Penyusun]` (placeholder)
3. **Struktur:** 9 BAB (disetujui).

## Pendekatan

Pakai ulang sistem desain modul lama (`docs/modul-penggunaan.html`): brand teal `#0F766E` +
amber `#F59E0B`, font Inter (embed lokal `InterVariable*.woff2`), CSS cetak A4, ekspor PDF
via headless Chrome. Isi disusun ulang mengikuti format buku e-Arsip, dan mockup CSS diganti
screenshot asli.

**File keluaran (baru, tidak menimpa modul lama):**
- `docs/Buku-Panduan-E-Supervisi.html` — sumber (HTML+CSS mandiri)
- `docs/Buku-Panduan-E-Supervisi.pdf` — hasil A4
- `docs/img-panduan/` — folder screenshot asli (di-embed sebagai file atau data URI)

## Struktur buku (9 BAB)

- **Sampul** + tabel identitas (Alamat aplikasi, Jenis dokumen, Sasaran pengguna, Tahun)
- **Kata Pengantar** (tempat, tanggal, `[Nama Penyusun]`)
- **Daftar Isi**
- **BAB I Pendahuluan** — latar belakang, tujuan, ruang lingkup
- **BAB II Gambaran Umum Aplikasi** — deskripsi, manfaat, menu utama (tabel),
  alur siklus supervisi & arti status, istilah penting
- **BAB III Persyaratan, Akun, dan Hak Akses** — persyaratan sistem, ketentuan akun,
  tiga peran, matriks hak akses, cara login, ganti sandi pertama, sesi otomatis
- **BAB IV Panduan Administrator** — dashboard, manajemen pengguna, supervisi, rubrik,
  modul ajar, carousel
- **BAB V Panduan Kepala Sekolah** — daftar evaluasi, mulai tinjau, rubrik 47 aspek,
  umpan balik, predikat hasil, rekap progres modul
- **BAB VI Panduan Guru** — ajukan supervisi, 7 dokumen wajib, 5 refleksi, tanggapi revisi,
  modul ajar
- **BAB VII Pedoman Operasional dan Keamanan Data** — penamaan, keamanan akun,
  notifikasi & pengingat otomatis, backup
- **BAB VIII Pemecahan Masalah** — tabel kendala/penyebab/solusi
- **BAB IX Penutup**
- **Lampiran** — checklist kesiapan implementasi + contoh

## Rencana screenshot (target ±18 layar)

Umum: (1) halaman login, (2) ganti sandi pertama.
Administrator: (3) dashboard, (4) manajemen pengguna, (5) daftar supervisi, (6) kelola rubrik,
(7) kelola modul ajar, (8) kelola carousel.
Kepala Sekolah: (9) daftar evaluasi, (10) detail supervisi + dokumen, (11) isi rubrik 47 aspek,
(12) umpan balik, (13) predikat hasil, (14) rekap progres modul.
Guru: (15) beranda, (16) tab dokumen (7 berkas), (17) tab proses (5 refleksi),
(18) hasil penilaian, (19) lonceng notifikasi, (20) modul ajar.

Prasyarat data: perlu seed data contoh agar tiap status supervisi terlihat
(Draft, Diajukan, Ditinjau, Revisi, Selesai). DB lokal saat ini: 3 user, 1 supervisi.

## Gaya penulisan (syarat wajib)

- Bahasa Indonesia baku dan formal, mengikuti nada buku e-Arsip.
- **Tanpa tanda pisah em-dash (—).** Gunakan titik, atau kata sambung, atau tanda kurung.
- Tanpa koma yang dipaksakan atau tidak pada tempatnya.
- Kalimat lugas dan alami, tidak ada jejak gaya AI (hindari pola daftar berulang yang kaku,
  frasa klise, dan tanda baca menyimpang).

## Fakta produk (akurat dari kode, untuk isi buku)

- Login: peran + NIK + kata sandi. Batas 5 percobaan per menit. Sesi berakhir 10 menit idle.
- Ganti sandi wajib saat login pertama.
- Status supervisi: Draft → Diajukan → Ditinjau → Selesai, dengan cabang Revisi.
- Guru hanya boleh punya satu supervisi aktif (Draft/Revisi) pada satu waktu.
- 7 dokumen wajib: Capaian Pembelajaran, Alur Tujuan Pembelajaran, Kalender Pendidikan,
  Program Tahunan, Program Semester, Modul Ajar, Bahan Ajar. Format PDF/JPG/PNG, maks 2 MB.
- 5 pertanyaan refleksi pada tab Proses (maks 500 karakter).
- Rubrik 47 aspek, 3 bagian (A Pendahuluan, B Inti, C Penutup), skor 0/1/2.
- Predikat: Sangat Baik ≥91, Baik 81–90, Cukup 71–80, Kurang <71.
- Penilaian kepala sekolah: langkah 1 rubrik dulu, langkah 2 umpan balik, baru selesai.
- Pengingat otomatis Senin & Kamis pukul 07.00. Lonceng notifikasi database.
- Modul ajar: PDF (maks 20 MB) + video praktik; progres baca terekam.
- Admin: kelola pengguna (tambah/sunting/reset sandi/aktif-nonaktif), supervisi, rubrik,
  modul ajar, carousel.

## Keluar dari lingkup (YAGNI)

- Tidak mengubah modul lama `docs/modul-penggunaan.html`.
- Tidak menambah fitur ke aplikasi. Data contoh untuk screenshot bersifat sementara/lokal.
