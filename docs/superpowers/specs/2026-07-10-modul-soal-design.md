# Desain: Soal/Kuis Modul Ajar (Sub-proyek 2 — Platform Pembelajaran Mandiri Guru)

Tanggal: 2026-07-10
Status: Disetujui user (brainstorming selesai)
Induk: [platform-pembelajaran-guru.md](../../platform-pembelajaran-guru.md)

## Konteks & Posisi dalam Peta Besar

Urutan sub-proyek yang disepakati sejak spec sub-proyek 1: **1. Modul Ajar
(selesai) → 2. Soal/Kuis (dokumen ini) → 5. Role Validator → 3. Video Praktik
Guru → 4. Penilaian Pembelajaran Mandiri → 6. Notifikasi.** Dokumen ini hanya
mencakup sub-proyek 2: soal pilihan ganda yang menguji pemahaman guru setelah
membaca modul.

## Ringkasan Keputusan Lingkup

- **Syarat akses**: guru hanya bisa mengerjakan soal suatu modul setelah
  progres baca modul tersebut mencapai **100%** (`ModulProgress::persen() ==
  100`). Soal terkunci sebelum itu — tombol nonaktif + pesan penjelasan,
  bukan disembunyikan total (guru tetap tahu soal itu ada).
- **Tipe soal**: **pilihan ganda saja** (4 opsi, 1 kunci jawaban), auto-grade.
  Esai/penilaian manual eksplisit di luar lingkup — kandidat sub-proyek lain.
- **Percobaan**: guru **boleh mengulang** kapan saja. Setiap pengerjaan
  dicatat sebagai *attempt* baru (riwayat lengkap tersimpan); skor yang
  ditampilkan sebagai capaian guru adalah **skor tertinggi** dari semua
  attempt-nya.
- **Berdiri sendiri (standalone)**: hasil soal **tidak** terhubung ke
  `EvaluasiRubrik`/predikat kepala sekolah pada tahap ini. Itu jadi bagian
  sub-proyek 4 (Penilaian Pembelajaran Mandiri). Kepala sekolah pada tahap
  ini hanya dapat **melihat** (read-only) skor terbaik tiap guru per modul,
  diperluas dari halaman rekap progres modul yang sudah ada.
- Modul tanpa soal tetap valid (tidak wajib punya soal) — sesuai pola
  `ModulVideo` yang juga opsional per modul.

## Struktur Data

Tiga tabel baru, menyambung ke `moduls` dan `users` yang sudah ada:

### `modul_soal`
| Kolom | Keterangan |
|---|---|
| `modul_id` | FK ke `moduls` |
| `pertanyaan` | Teks soal |
| `opsi_a` .. `opsi_d` | Empat opsi jawaban |
| `kunci_jawaban` | `enum('a','b','c','d')` |
| `urutan` | Urutan tampil soal dalam kuis |
| `is_active` | Nonaktifkan-tanpa-hapus, pola sama seperti `RubrikItem`/`ModulVideo` |

### `modul_soal_attempts`
| Kolom | Keterangan |
|---|---|
| `user_id` + `modul_id` | Siapa mengerjakan kuis modul mana |
| `skor` | Jumlah jawaban benar |
| `skor_maksimal` | Jumlah soal aktif saat attempt dibuat (disimpan, bukan dihitung ulang — tahan perubahan jumlah soal di masa depan) |
| `submitted_at` | Kapan attempt diselesaikan |

Banyak baris per pasangan guru–modul (setiap percobaan = baris baru), beda
dengan `modul_progress` yang satu baris per pasangan. Skor tertinggi dihitung
saat ditampilkan (`max('skor')` dari attempt guru tsb pada modul tsb).

### `modul_soal_attempt_jawaban`
| Kolom | Keterangan |
|---|---|
| `modul_soal_attempt_id` | FK ke attempt |
| `modul_soal_id` | FK ke soal yang dijawab |
| `jawaban_dipilih` | `enum('a','b','c','d')` |
| `is_benar` | Disimpan saat submit (tahan perubahan kunci jawaban di kemudian hari — histori tetap mencerminkan apa yang benar saat dikerjakan) |

Tabel jawaban per-butir dipisah dari attempt agar guru bisa melihat riwayat
detail (soal mana yang salah) tanpa menyimpannya sebagai JSON blob.

## Alur per Peran

### Admin — `admin/modul` (perluasan halaman yang sudah ada)
- Di halaman detail/kelola tiap modul, tambah bagian "Soal Kuis": daftar
  soal, tambah/edit/nonaktifkan (pola sama seperti `RubrikItemController`).
- Validasi: pertanyaan wajib, 4 opsi wajib diisi, kunci jawaban wajib salah
  satu dari 4 opsi.
- Mengubah kunci jawaban soal lama **tidak** mengubah histori attempt lama
  (karena `is_benar` sudah disimpan saat submit).

### Guru — perluasan `guru/modul/{modul}`
- Di halaman baca modul (atau kartu daftar modul), tampilkan status kuis:
  terkunci (progres < 100%), tersedia (progres 100%, belum dikerjakan), atau
  skor terbaik + tombol "Ulangi" (sudah pernah dikerjakan).
- Halaman kerjakan kuis: satu form berisi semua soal aktif modul tsb,
  submit sekali → dinilai langsung → halaman hasil (skor, jawaban benar/salah
  per soal, kunci jawaban yang benar).
- Guard server-side wajib: endpoint submit kuis menolak jika progres guru
  pada modul tsb belum 100%, terlepas dari tampilan client.
- Modul tanpa soal aktif → tidak menampilkan bagian kuis sama sekali.

### Kepala Sekolah — perluasan `kepala/modul-progress`
- Kolom tambahan di rekap yang sudah ada: skor kuis terbaik per guru per
  modul (atau "—" jika belum ada soal/belum dikerjakan). Read-only, tanpa
  aksi penilaian (predikat tetap di luar lingkup sub-proyek ini).

### Navigasi
Tidak ada menu sidebar baru — kuis menyatu ke halaman modul admin/guru yang
sudah ada, dan kolom tambahan di rekap kepala sekolah yang sudah ada.

## Panduan UI/UX

Mengikuti prinsip yang sama seperti sub-proyek 1: menyatu dengan
`layouts.modern`, dark mode wajib di semua elemen, ikon SVG inline, bukan
bahasa visual baru.

### Status kuis (guru, di halaman modul)
- Tiga state visual jelas beda: **terkunci** (ikon gembok, teks abu-abu,
  tombol nonaktif + tooltip "Selesaikan membaca modul untuk membuka kuis"),
  **tersedia** (tombol solid "Kerjakan Kuis"), **sudah dikerjakan** (badge
  skor terbaik + tombol sekunder "Ulangi Kuis").
- Warna status tidak boleh jadi satu-satunya penanda — selalu sertakan teks.

### Halaman kerjakan kuis
- Semua soal dalam satu halaman (tidak multi-step per soal) — sesuai jumlah
  soal per modul yang diperkirakan sedikit (< 20).
- Opsi jawaban sebagai radio button custom, area sentuh minimal 44×44 px.
- Indikator progres pengisian ("3 dari 10 soal terjawab") jika soal cukup
  banyak, agar guru tahu ada soal yang terlewat sebelum submit.
- Konfirmasi sebelum submit jika ada soal belum terjawab (bukan blokir keras
  — guru boleh submit dengan jawaban kosong, dihitung salah).

### Halaman hasil kuis
- Skor besar di atas (mis. "8 / 10"), lalu daftar soal dengan penanda
  benar/salah + kunci jawaban yang benar untuk soal yang salah dijawab.
- Tombol "Ulangi Kuis" jelas terlihat.

### Rekap kepala sekolah (kolom tambahan)
- Skor terbaik ditampilkan sebagai angka polos di kolom baru tabel yang
  sudah ada, konsisten dengan pola kolom persen baca yang sudah ada.

## Penanganan Kesalahan

- Guru mengakses URL kuis langsung tanpa progres 100% → redirect dengan
  pesan flash, bukan error 500.
- Submit kuis untuk modul tanpa soal aktif (mis. semua soal baru
  dinonaktifkan admin di tengah pengerjaan) → tolak dengan pesan jelas.
- Admin menghapus/nonaktifkan soal yang sudah pernah dijawab → histori
  attempt lama tetap utuh (tidak bergantung pada relasi `is_active` soal
  saat ini untuk menghitung ulang skor).
- Endpoint submit hanya menerima jawaban untuk soal milik modul yang
  bersangkutan (cegah guru mengirim `modul_soal_id` dari modul lain).
- Endpoint submit dan hasil hanya menampilkan/menyimpan data milik guru yang
  sedang login.

## Pengujian (TDD ketat sesuai aturan proyek)

- Unit test: guard `bisaKerjakanSoal()`/setara di `ModulProgress` (100% vs
  kurang), kalkulasi skor attempt, skor tertinggi dari banyak attempt.
- Feature test admin: CRUD soal, validasi kunci jawaban wajib salah satu
  opsi, nonaktifkan tanpa hapus.
- Feature test guru: kuis terkunci ditolak (progres < 100%), submit
  berhasil menyimpan attempt + jawaban per butir + skor benar, histori tetap
  benar setelah admin ubah kunci jawaban, tolak `modul_soal_id` dari modul
  lain, tolak akses ke hasil attempt guru lain.
- Feature test kepala sekolah: kolom skor terbaik tampil benar di rekap,
  "—" untuk guru yang belum mengerjakan/modul tanpa soal.
- `php artisan config:clear` sebelum test (guard di `TestCase`).
- Perkiraan: 20–28 test baru.

## Di Luar Lingkup (sub-proyek lain)

Esai/penilaian manual, integrasi skor ke `EvaluasiRubrik`/predikat kepala
sekolah, role validator, video praktik guru + komentar, notifikasi
pengingat, tanda tangan PDF.
