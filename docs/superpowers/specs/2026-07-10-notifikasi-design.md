# Desain: Notifikasi In-App (Platform Pembelajaran Mandiri Guru)

Tanggal: 2026-07-10
Status: Disetujui user (brainstorming selesai)
Induk: [platform-pembelajaran-guru.md](../../platform-pembelajaran-guru.md)

## Konteks & Posisi dalam Peta Besar

Sub-proyek terakhir yang tersisa dari peta platform pembelajaran. Status akhir peta:
Modul Ajar (selesai) → Video Praktik Guru (selesai, merged `2da6300`) → **Notifikasi (dokumen ini)**.
Soal/Kuis, Role Validator, dan Penilaian Pembelajaran Mandiri **dibatalkan** user
(yang terakhir karena "sepertinya sudah tercakup": nilai/predikat rubrik, rekap
progres modul, video praktik, dan refleksi guru semuanya sudah ada).

Notifikasi adalah satu-satunya gap nyata yang belum ada wujudnya di kode:
eksplorasi mengonfirmasi tidak ada `->notify()`, tidak ada tabel `notifications`,
dan tidak ada penjadwal (scheduler) sama sekali.

Tujuan: memberi pengguna pemberitahuan **dalam aplikasi** atas kejadian penting
supaya tidak perlu mengecek manual, plus pengingat terjadwal agar guru mengisi
supervisi tepat waktu. Sesuai dokumen visi: "pemberitahuan internal sementara"
(hindari biaya token WA/Telegram).

## Ringkasan Keputusan Lingkup

- **Saluran:** dalam aplikasi saja (tanpa email/WA/Telegram).
- **Empat pemicu:**
  1. **Tanggapan atas supervisi guru** → ke GURU pemilik: saat dapat
     feedback/komentar, diminta revisi, atau selesai dinilai.
  2. **Supervisi perlu direview** → ke KEPALA SEKOLAH (tingkat sama) SAJA: saat
     guru submit / submit ulang. Admin **sengaja tidak** diberi notifikasi submit
     (mereka tetap bisa melihat daftar supervisi manual).
  3. **Modul baru diunggah** → ke SEMUA GURU aktif: saat admin menambah modul.
  4. **Pengingat terjadwal** → ke GURU yang belum mulai / draft mangkrak,
     **2× seminggu (Senin & Kamis)**.
- **Tampilan:** ikon lonceng di bilah atas + badge jumlah belum-dibaca + dropdown
  notifikasi terbaru + tautan "Lihat semua" ke halaman daftar penuh.
- **Perilaku:** klik notifikasi → tandai terbaca lalu menuju item terkait;
  tombol "tandai semua terbaca".

## Arsitektur

Pakai **sistem notifikasi bawaan Laravel** (`illuminate/notifications`, channel
`database`) — bukan membangun sendiri. Aplikasi memakai **Laravel 12**; model
`User` sudah memakai trait `Notifiable` (`app/Models/User.php:11`). Tabel
`notifications` tinggal dimigrasi (`php artisan notifications:table`).

### Kelas Notification (`app/Notifications/`)

Masing-masing `toArray()` mengisi kolom `data` (json): `judul`, `pesan`,
`ikon` (kunci untuk memilih SVG inline), `url` (rute tujuan).

| Kelas | Penerima | Isi/URL |
|---|---|---|
| `SupervisiDitanggapi` | guru pemilik | field `jenis` ∈ {feedback, revisi, selesai} menentukan judul/pesan; url = detail supervisi guru. Satu kelas untuk tiga varian (DRY — tujuan sama, beda kata). |
| `SupervisiPerluDireview` | kepala sekolah tingkat sama | url = halaman evaluasi kepala sekolah |
| `ModulBaruDiunggah` | semua guru aktif | url = daftar modul guru |
| `PengingatSupervisi` | guru sasaran | url = beranda / mulai supervisi |

### Penentuan penerima (pakai pola query yang sudah ada)

- Tanggapan → `$supervisi->user` (relasi `Supervisi::user()`).
- Perlu direview → `User::where('role','kepala_sekolah')
  ->where('tingkat',$supervisi->user->tingkat)->where('is_active',true)->get()`
  lalu `Notification::send($penerima, ...)`. Admin dikecualikan.
- Modul baru → `User::where('role','guru')->where('is_active',true)->get()`
  (pola persis `KepalaSekolah/ModulProgressController.php:16`); satu
  `Notification::send($semuaGuru, ...)` — hindari N+1 saat fan-out.

### Titik pemicu (inline di controller, tanpa event/observer baru)

- `Guru/ProsesController::submit` (setelah update status `submitted`).
- `KepalaSekolah/EvaluasiController::giveFeedback` / `requestRevision` /
  `complete`.
- `Admin/SupervisiController::storeFeedback` / `requestRevision`.
- `Admin/ModulController::store` (setelah `Modul::create`).

### Tampilan (lonceng)

Disisipkan **sekali** di topbar bersama `resources/views/layouts/modern.blade.php`
(sebelum blok Profile Dropdown) → berlaku untuk semua peran (topbar sama untuk
admin/guru/kepala). Data lonceng (jumlah belum-dibaca + daftar terbaru)
disediakan ke layout lewat **View Composer** yang di-bind ke `layouts.modern`,
sehingga tidak perlu mengubah setiap controller. Render server-side (tanpa AJAX
polling); dropdown toggle mengikuti pola JS dropdown profil yang sudah ada.
**Wajib** memastikan lonceng juga terjangkau di mobile 375px (dropdown profil
saat ini `hidden md:flex` — periksa nav mobile di bagian bawah file).

### Rute (bersama, semua peran ber-auth) — `App\Http\Controllers\NotifikasiController`

- `GET /notifikasi` → halaman daftar penuh (paginasi).
- `GET /notifikasi/{id}/buka` → tandai terbaca lalu redirect ke `data['url']`.
- `POST /notifikasi/baca-semua` → tandai semua terbaca.

### Penjadwal (Laravel 12)

Command Artisan baru `App\Console\Commands\KirimPengingatSupervisi` (signature
`notifikasi:pengingat-supervisi`) mengirim `PengingatSupervisi` ke guru sasaran.

- **Sasaran** = guru aktif yang **tidak** punya supervisi berstatus
  `submitted`/`under_review`/`completed` (artinya belum mulai, atau hanya
  draft/revision — butuh aksi mereka; sistem menolak >1 supervisi aktif per guru).
- **Guard anti-spam:** lewati guru yang masih punya `PengingatSupervisi`
  belum-dibaca.
- Dijadwalkan di `routes/console.php` (Laravel 12 memakai `routes/console.php`,
  bukan `Console/Kernel.php`):
  `Schedule::command('notifikasi:pengingat-supervisi')->twiceWeekly(1, 4, '07:00')`
  (Senin & Kamis 07:00).

## Penanganan Kesalahan

- Notifikasi hanya pelengkap — kegagalan mengirim notifikasi TIDAK boleh
  menggagalkan aksi utama (submit/feedback/unggah tetap sukses). Pemicu inline
  dibungkus supaya error notifikasi tidak melempar ke pengguna.
- URL notifikasi menunjuk rute yang mungkin berubah status (mis. supervisi sudah
  dihapus) → route `buka` tetap menandai terbaca lalu redirect; tujuan yang tak
  ada ditangani oleh guard rute tujuan yang sudah ada (404/redirect ramah).
- `buka`/`baca-semua` hanya boleh atas notifikasi milik pengguna yang login.

## Panduan UI

Menyatu dengan layout `modern.blade.php` (Bootstrap 5 + Tailwind): ikon SVG
inline (bukan emoji), dark mode diuji terpisah, responsif mulai 375 px, kontras
teks minimal 4.5:1, area sentuh minimal 44×44 px. Badge belum-dibaca memakai
angka + warna (warna bukan satu-satunya penanda). Keadaan kosong ("Belum ada
notifikasi") memakai komponen empty-state yang sudah ada.

## Pengujian (TDD ketat sesuai aturan proyek)

- `Notification::fake()` + `Notification::assertSentTo(...)` (penggunaan pertama
  di proyek ini) per pemicu: submit→kepsek (dan `assertNotSentTo` admin),
  feedback/revisi/complete→guru, modul baru→semua guru.
- Command pengingat: `Notification::fake()` + `artisan('notifikasi:pengingat-supervisi')`,
  assert terkirim ke guru sasaran, TIDAK ke guru bersupervisi aktif, dan guard
  anti-spam (tidak dobel bila sudah ada pengingat belum-dibaca).
- Feature: halaman `/notifikasi` tampil; `buka` menandai terbaca + redirect;
  `baca-semua` mengosongkan badge; lonceng tampil di topbar semua peran;
  `buka`/`baca-semua` menolak notifikasi milik orang lain.
- WAJIB `php artisan config:clear` sebelum test (insiden DB dev terhapus — lihat
  guard di TestCase). Perkiraan 15–25 test baru.

## Verifikasi Akhir

- `php artisan config:clear && php artisan test` (semua hijau) + `npm run test`
  + `npm run build`.
- Smoke test browser (pelajaran sub-proyek sebelumnya — bug UI hanya ketahuan di
  browser): guru dapat notifikasi setelah kepsek beri feedback; badge & dropdown
  muncul; klik → menuju supervisi + badge berkurang; admin unggah modul → semua
  guru dapat; jalankan `notifikasi:pengingat-supervisi` manual → guru sasaran
  dapat; cek lonceng di dark mode & 375 px.

## Di Luar Lingkup

Email/WA/Telegram, notifikasi real-time (websocket/broadcast), preferensi
notifikasi per pengguna, tanda tangan PDF.
