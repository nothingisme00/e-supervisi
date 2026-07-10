# Desain: Video Praktik Guru (Platform Pembelajaran Mandiri Guru)

Tanggal: 2026-07-10
Status: Disetujui user (brainstorming selesai)
Induk: [platform-pembelajaran-guru.md](../../platform-pembelajaran-guru.md)

## Konteks & Posisi dalam Peta Besar

Dari peta 6 sub-proyek yang disepakati 2026-07-07, dua sub-proyek **dibatalkan
saat brainstorming ini**: Soal/Kuis (tidak perlu soal setelah membaca modul)
dan Role Validator (tidak dibutuhkan). Peta yang tersisa:
**Modul Ajar (selesai) → Video Praktik Guru (dokumen ini) →
Penilaian Pembelajaran Mandiri → Notifikasi.**

## Ringkasan Keputusan Lingkup

Konsep berubah dari bayangan awal: video praktik **bukan fitur berdiri
sendiri**, melainkan **melekat pada supervisi** — dan fondasinya sudah ada
semua di aplikasi:

- `proses_pembelajaran.link_video` sudah diisi guru saat mengisi supervisi
  (wajib sebelum submit; URL bebas — YouTube/Google Drive/lainnya,
  validasi `nullable|url`).
- Tautan itu sudah tampil di 4 halaman (detail milik guru,
  lihat-supervisi-guru-lain, evaluasi kepala sekolah, detail admin) —
  tapi hanya sebagai **tautan keluar** (buka tab baru).
- Komentar antar guru pada supervisi orang lain sudah ada
  (`guru.supervisi.comment`, tabel `feedback`) — kebutuhan "belajar bersama"
  dari dokumen visi sudah terpenuhi mekanismenya.

Yang kurang hanya **penyajian**: video harus bisa langsung ditonton di dalam
aplikasi. Maka lingkup sub-proyek ini: **tanpa tabel baru, tanpa route baru,
tanpa perubahan alur pengisian** — murni peningkatan tampilan + satu utilitas
baru.

Keputusan brainstorm awal yang gugur karena konsep berubah: galeri video,
tabel `praktik_videos`, sistem komentar baru, rekap video untuk kepala sekolah.

## Komponen

### 1. Utilitas embed bersama — `App\Support\VideoEmbed`

Menerjemahkan URL menjadi URL embed + thumbnail:

| Sumber | Deteksi | Embed | Thumbnail |
|---|---|---|---|
| YouTube | `watch?v=`, `youtu.be/`, `/shorts/` (host-anchored) | `youtube.com/embed/{id}` | `img.youtube.com/vi/{id}/hqdefault.jpg` |
| Google Drive | `drive.google.com/file/d/{id}` | `drive.google.com/file/d/{id}/preview` | tidak ada |
| Lainnya | — | tidak dikenali | tidak ada |

URL yang tidak dikenali → pemakai jatuh kembali ke tautan keluar
(perilaku sekarang).

Sekalian melunasi utang tercatat modul ajar (`.superpowers/sdd/progress.md`):
regex YouTube di `app/Models/ModulVideo.php` belum host-anchored dan belum
mendukung `/shorts/` — logikanya dipindah ke `VideoEmbed`;
`ModulVideo::getYoutubeEmbedUrlAttribute` dan validasi di
`Admin/ModulController` memakainya.

### 2. Partial Blade bersama — `resources/views/components/video-praktik.blade.php`

Pemutar tertanam rasio 16:9 + tautan asli tetap ditampilkan di bawahnya
(cadangan bila embed gagal / file Drive tidak di-share). Menggantikan blok
"Link Video Pembelajaran" di:

- `resources/views/guru/supervisi/detail.blade.php`
- `resources/views/guru/supervisi/view.blade.php`
- `resources/views/kepala/evaluasi/show.blade.php`
- `resources/views/admin/supervisi/detail.blade.php`

### 3. Kartu timeline beranda guru

Di `resources/views/guru/home.blade.php`: thumbnail kecil video (YouTube)
atau badge "Ada video praktik" (Drive/lainnya — tidak menyediakan thumbnail
publik), klik menuju halaman detail. Kartu di halaman "Supervisi Saya"
(`my-supervisi.blade.php`) mendapat thumbnail/badge yang sama.
Ikon SVG inline, bukan emoji.

## Penanganan Kesalahan

- URL tak dikenali → tautan biasa (perilaku sekarang; tidak ada yang rusak).
- File Drive tidak di-share → pesan dari Google tampil di dalam iframe,
  tautan asli tetap tersedia di bawah pemutar.
- Tidak ada perubahan validasi form (`nullable|url` tetap).

## Panduan UI

Menyatu dengan layout `modern.blade.php` (Bootstrap 5 + Tailwind):
dark mode diuji terpisah (bukan diasumsikan), responsif mulai 375 px,
kontras teks minimal 4.5:1, area sentuh minimal 44×44 px.

## Pengujian (TDD ketat sesuai aturan proyek)

- Unit test `VideoEmbed`: varian YouTube (watch / youtu.be / shorts),
  URL palsu (`evil.com/watch?v=...` harus ditolak — host-anchored),
  Google Drive, URL tak dikenal.
- Feature test: 4 halaman menampilkan iframe embed / fallback tautan.
- Regression test: `ModulVideo` tetap benar setelah refactor ke `VideoEmbed`.
- Perkiraan 15–25 test baru. WAJIB `php artisan config:clear` sebelum test
  (insiden DB dev terhapus — lihat guard di TestCase).

## Verifikasi Akhir

- `php artisan config:clear && php artisan test` (275 test lama + baru hijau)
  dan `npm run test`.
- Smoke test browser (pelajaran sub-proyek 1 — bug embed hanya ketahuan di
  browser): login guru → beranda menampilkan thumbnail/badge → detail memutar
  video YouTube; supervisi ber-link Drive menampilkan preview; URL lain tetap
  tautan; cek juga halaman kepala sekolah & admin, dark mode, 375 px.

## Di Luar Lingkup (sub-proyek lain)

Penilaian & predikat pembelajaran mandiri, notifikasi, tanda tangan PDF.
