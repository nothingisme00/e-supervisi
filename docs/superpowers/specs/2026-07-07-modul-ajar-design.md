# Desain: Modul Ajar (Sub-proyek 1 — Platform Pembelajaran Mandiri Guru)

Tanggal: 2026-07-07
Status: Disetujui user (brainstorming selesai)
Induk: [platform-pembelajaran-guru.md](../../platform-pembelajaran-guru.md)

## Konteks & Posisi dalam Peta Besar

Visi platform pembelajaran mandiri dipecah menjadi 6 sub-proyek dengan urutan
yang disepakati: **1. Modul Ajar → 2. Soal/Kuis → 5. Role Validator →
3. Video Praktik Guru → 4. Penilaian Pembelajaran Mandiri → 6. Notifikasi.**
Dokumen ini hanya mencakup sub-proyek 1. Setiap sub-proyek berikutnya melewati
siklus brainstorm → spec → rencana → implementasi sendiri.

## Ringkasan Keputusan Lingkup

- Modul = **file PDF** yang diunggah admin + **tautan YouTube** opsional
  (bisa lebih dari satu per modul), dikelompokkan **kategori bebas** buatan admin.
- **Semua guru melihat semua modul.** Modul langsung tampil begitu diunggah —
  tidak ada status draf (keputusan eksplisit user; kolom status baru
  ditambahkan nanti saat fase validator).
- Progres baca dilacak **otomatis dari halaman PDF terjauh yang dibuka**,
  ditampilkan sebagai persen.
- Kepala sekolah mendapat **rekap progres** (guru × modul), tanpa
  penilaian/predikat — penilaian adalah sub-proyek 4.
- Penampil PDF: **PDF.js** (`pdfjs-dist` via npm, dibundel Vite) — bukan
  iframe (tidak bisa melacak halaman) dan bukan konversi ke gambar
  (butuh Ghostscript + penyimpanan membengkak).

## Struktur Data

Empat tabel baru:

### `modul_kategoris`
| Kolom | Keterangan |
|---|---|
| `nama` | Nama kategori (mis. Pedagogik, Kurikulum) |
| `is_active` | Nonaktifkan-tanpa-hapus, pola sama seperti `RubrikItem`/`CarouselSlide` |

### `moduls`
| Kolom | Keterangan |
|---|---|
| `judul`, `deskripsi` | Identitas modul |
| `modul_kategori_id` | FK ke kategori |
| `file_path` | Lokasi PDF di storage |
| `jumlah_halaman` | Dihitung **server-side** saat unggah (pustaka `smalot/pdfparser`), acuan persen progres — tidak pernah dari input browser |
| `is_active` | Nonaktifkan-tanpa-hapus |

### `modul_videos`
| Kolom | Keterangan |
|---|---|
| `modul_id` | FK ke modul |
| `judul` | Judul video |
| `youtube_url` | Divalidasi pola URL YouTube saat simpan |

Tabel terpisah (bukan kolom di `moduls`) agar satu modul bisa punya beberapa
video dan sub-proyek soal/kuis nanti tinggal menambah relasi serupa.

### `modul_progress`
| Kolom | Keterangan |
|---|---|
| `user_id` + `modul_id` | Unik — satu baris per pasangan guru–modul |
| `halaman_terjauh` | Hanya pernah naik, tidak pernah turun |
| `terakhir_dibuka_at` | Kapan terakhir modul dibuka |

Persen baca = `halaman_terjauh / jumlah_halaman`, **dihitung saat ditampilkan**
(tidak disimpan) supaya tetap benar jika admin mengganti file PDF. Jika
`halaman_terjauh > jumlah_halaman` baru (PDF diganti lebih pendek), dibatasi 100%.

## Alur per Peran

### Admin — `admin/modul`
- CRUD modul mengikuti pola `admin/rubrik-items`: daftar, tambah (unggah PDF +
  judul/kategori/deskripsi + tautan YouTube), edit, nonaktifkan.
- Kelola kategori lewat modal di halaman yang sama (bukan menu terpisah).
- Validasi unggahan: hanya PDF, maksimal 20 MB.
- Mengganti PDF pada modul lama diizinkan; progres guru tetap tersimpan,
  persennya menyesuaikan jumlah halaman baru.

### Guru — `guru/modul`
- **Daftar modul**: kartu per modul (judul, kategori, jumlah halaman, bilah
  progres milik sendiri), filter per kategori.
- **Halaman baca**: PDF.js satu-halaman-per-tampilan, tombol maju/mundur +
  loncat ke halaman; video YouTube tertanam di bawah penampil. Mengikuti
  layout `modern.blade.php` termasuk dark mode.
- Setiap pindah halaman, browser mengirim nomor halaman ke server
  (debounce 2 detik). Server hanya menaikkan `halaman_terjauh`.

### Kepala Sekolah — `kepala/modul-progress`
- Satu halaman rekap tabel guru × modul berisi persen baca; bisa dilihat
  per modul (siapa sudah selesai) atau per guru (sudah baca apa saja).
- Hanya membaca data — tidak ada aksi penilaian.

### Navigasi
Sidebar bertambah: "Modul Ajar" (admin, guru), "Progres Modul" (kepala sekolah).

## Panduan UI/UX

Prinsip utama: **menyatu dengan desain aplikasi yang sudah ada** (layout
`modern.blade.php`, Bootstrap 5 + Tailwind, dark mode, ikon SVG inline) —
bukan bahasa visual baru. Arah gaya dari analisis UI/UX Pro Max:
*data-dense dashboard* profesional, hindari ornamen berlebihan.

### Daftar modul (guru)
- Kartu per modul: judul, badge kategori (reuse `status-badge`), jumlah
  halaman, bilah progres + angka persen (warna tidak boleh jadi satu-satunya
  penanda — selalu sertakan teks persen).
- Filter kategori wajib ada sejak awal (anti-pattern: daftar tanpa filter).
- Belum ada modul → komponen `empty-state` yang sudah ada, dengan pesan
  membimbing, bukan halaman kosong.

### Halaman baca (guru)
- PDF.js memuat secara asinkron → tampilkan `skeleton-loader` selama render
  pertama (> 300 ms wajib ada umpan balik), bukan layar putih.
- Indikator posisi selalu terlihat: "Halaman 12 dari 40 • 30%".
- Tombol maju/mundur: area sentuh minimal 44×44 px (guru banyak memakai HP),
  dinonaktifkan + spinner kecil selama halaman berikutnya dirender
  (cegah klik ganda).
- Dukungan keyboard: panah kiri/kanan untuk pindah halaman.
- Kanvas PDF diberi latar netral yang tetap nyaman di dark mode (halaman PDF
  sendiri putih — bingkainya yang menyesuaikan tema).
- Hormati `prefers-reduced-motion` untuk transisi antar halaman.

### Rekap progres (kepala sekolah)
- Tabel padat ala dashboard: baris di-highlight saat hover, persen ditampilkan
  angka + bilah mini, bisa berganti sudut pandang per-modul/per-guru.
- Guru yang belum membuka modul tetap muncul dengan 0% (bukan hilang dari
  daftar) — ketiadaan data adalah informasi penting bagi kepala sekolah.

### Umum
- Ikon dari SVG inline seperti halaman lain — tidak ada emoji sebagai ikon.
- Kontras teks minimal 4.5:1, diuji di kedua tema (dark mode dicek terpisah,
  bukan diasumsikan dari tema terang).
- Responsif mulai 375 px; tabel rekap boleh menggulir horizontal di dalam
  kontainernya sendiri, bukan seluruh halaman.

## Penanganan Kesalahan

- PDF rusak/terenkripsi → penghitung halaman gagal → unggahan ditolak dengan
  pesan jelas; tidak ada modul setengah jadi tersimpan.
- Tautan YouTube tidak valid → ditolak saat validasi form.
- PDF hilang dari storage saat guru membuka → pesan ramah, bukan error 500.
- Kiriman progres gagal (koneksi) → dicoba ulang diam-diam pada perpindahan
  halaman berikutnya; tanpa error di sisi guru.
- Server menolak nomor halaman di luar rentang (< 1 atau > `jumlah_halaman`).
- Endpoint progres hanya menerima progres milik guru yang sedang login.

## Pengujian (TDD ketat sesuai aturan proyek)

- Feature test per peran: admin CRUD + validasi unggahan; guru melihat daftar,
  membaca, progres tercatat; kepala sekolah melihat rekap; peran lain ditolak.
- Unit test kalkulasi persen, termasuk kasus PDF diganti (pembatasan 100%).
- Test endpoint progres: hanya naik, tolak di luar rentang, hanya pemilik.
- `Storage::fake()` di semua test unggahan; jalankan `php artisan config:clear`
  sebelum test (insiden DB dev terhapus — lihat guard di TestCase).
- Perkiraan: 25–35 test baru di atas 225 yang ada.

## Di Luar Lingkup (sub-proyek lain)

Soal/kuis, status validasi/draf, video praktik guru + komentar, penilaian &
predikat pembelajaran mandiri, notifikasi, tanda tangan PDF.
