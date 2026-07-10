# Soal/Kuis Modul Ajar — Rencana Implementasi

**Goal:** Guru mengerjakan kuis pilihan ganda per modul setelah progres baca
mencapai 100%, hasil auto-grade dengan riwayat percobaan lengkap dan skor
tertinggi ditampilkan; admin mengelola bank soal per modul; kepala sekolah
melihat skor terbaik tiap guru sebagai kolom tambahan di rekap progres yang
sudah ada.

**Architecture:** Tiga tabel baru (`modul_soal`, `modul_soal_attempts`,
`modul_soal_attempt_jawaban`), satu controller admin baru
(`Admin\ModulSoalController`, pola sama dengan `RubrikItemController`),
perluasan `Guru\ModulController` (2 route baru: tampilkan form kuis, submit
kuis) atau controller baru `Guru\ModulSoalController` bila lebih rapi
dipisah, dan perluasan `KepalaSekolah\ModulProgressController` untuk kolom
skor.

**Tech Stack:** Laravel 12 / PHP 8.2, Blade + Tailwind (`layouts.modern`),
PHPUnit 11 — tidak ada dependency baru.

**Spec:** `docs/superpowers/specs/2026-07-10-modul-soal-design.md`

## Global Constraints

- **WAJIB `php artisan config:clear` sebelum setiap sesi test.**
- **TDD ketat**: tulis test RED lebih dulu untuk setiap unit sebelum
  implementasi, satu per satu.
- Semua teks UI Bahasa Indonesia; semua elemen wajib varian dark mode
  (`dark:` classes); ikon SVG inline, bukan emoji.
- Guard 100% progres dan kepemilikan attempt **wajib server-side** — jangan
  hanya di client/view.
- `is_benar` pada jawaban dan `skor_maksimal` pada attempt disimpan saat
  submit, tidak pernah dihitung ulang dari state soal saat ini (histori
  tahan perubahan kunci jawaban/nonaktifnya soal).
- View extends `layouts.modern`; reuse `x-card-header`, `x-status-badge`,
  `x-empty-state`.
- Commit sering, Bahasa Indonesia, prefiks `feat:`/`test:`/`chore:`.
- Jalankan test dengan `php artisan test --filter=NamaTest`.

---

## Task 1: Migrasi, model, dan factory `ModulSoal`

**Files:**
- Create: `database/migrations/2026_07_10_100001_create_modul_soal_table.php`
- Create: `app/Models/ModulSoal.php`
- Create: `database/factories/ModulSoalFactory.php`
- Test: `tests/Unit/Models/ModulSoalModelTest.php`

**Skema:**
```php
Schema::create('modul_soal', function (Blueprint $table) {
    $table->id();
    $table->foreignId('modul_id')->constrained('moduls')->cascadeOnDelete();
    $table->text('pertanyaan');
    $table->string('opsi_a');
    $table->string('opsi_b');
    $table->string('opsi_c');
    $table->string('opsi_d');
    $table->enum('kunci_jawaban', ['a', 'b', 'c', 'd']);
    $table->unsignedSmallInteger('urutan')->default(1);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

**Model:** fillable `modul_id, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d,
kunci_jawaban, urutan, is_active`; cast `is_active` boolean; scope
`active()`; relasi `modul(): BelongsTo`.

- [ ] Test RED: `scopeActive` menyaring soal nonaktif; `belongsTo(Modul)`;
  factory default `is_active = true`, `kunci_jawaban` acak dari 4 opsi.
- [ ] Jalankan, pastikan gagal (`Class "App\Models\ModulSoal" not found`).
- [ ] Implementasi migrasi + model + factory, jalankan lagi sampai GREEN.

## Task 2: Migrasi, model, factory `ModulSoalAttempt` + `ModulSoalAttemptJawaban`

**Files:**
- Create: `database/migrations/2026_07_10_100002_create_modul_soal_attempts_table.php`
- Create: `database/migrations/2026_07_10_100003_create_modul_soal_attempt_jawaban_table.php`
- Create: `app/Models/ModulSoalAttempt.php`, `app/Models/ModulSoalAttemptJawaban.php`
- Create: `database/factories/ModulSoalAttemptFactory.php`
- Test: `tests/Unit/Models/ModulSoalAttemptModelTest.php`

**Skema attempt:**
```php
Schema::create('modul_soal_attempts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('modul_id')->constrained('moduls')->cascadeOnDelete();
    $table->unsignedSmallInteger('skor');
    $table->unsignedSmallInteger('skor_maksimal');
    $table->timestamp('submitted_at');
    $table->timestamps();
    $table->index(['user_id', 'modul_id']);
});
```

**Skema jawaban:**
```php
Schema::create('modul_soal_attempt_jawaban', function (Blueprint $table) {
    $table->id();
    $table->foreignId('modul_soal_attempt_id')->constrained('modul_soal_attempts')->cascadeOnDelete();
    $table->foreignId('modul_soal_id')->constrained('modul_soal')->cascadeOnDelete();
    $table->enum('jawaban_dipilih', ['a', 'b', 'c', 'd']);
    $table->boolean('is_benar');
    $table->timestamps();
});
```

**Model `ModulSoalAttempt`:** fillable `user_id, modul_id, skor,
skor_maksimal, submitted_at`; cast `submitted_at` datetime; relasi
`jawaban(): HasMany`, `user(): BelongsTo`, `modul(): BelongsTo`; scope
statis/helper `terbaikUntuk(int $userId, int $modulId): ?self` (ambil
`orderByDesc('skor')->first()`).

**Model `ModulSoalAttemptJawaban`:** fillable `modul_soal_attempt_id,
modul_soal_id, jawaban_dipilih, is_benar`; relasi `soal(): BelongsTo` (ke
`ModulSoal::class` via `modul_soal_id`).

- [ ] Test RED: relasi attempt↔jawaban, `terbaikUntuk()` mengembalikan skor
  tertinggi dari beberapa attempt milik user+modul yang sama, `null` jika
  belum ada attempt.
- [ ] Implementasi sampai GREEN.

## Task 3: Guard "boleh kerjakan kuis" di `ModulProgress` + helper skor di `Modul`

**Files:**
- Edit: `app/Models/ModulProgress.php` (tambah `sudahSelesai(): bool` —
  `persen() === 100`)
- Edit: `app/Models/Modul.php` (tambah relasi `soal(): HasMany` ke
  `ModulSoal`, scope/helper `soal()->active()` untuk dipakai controller)
- Test: tambahkan ke `tests/Unit/Models/ModulModelTest.php` dan
  `tests/Unit/Models/ModulProgressTest.php` (sesuaikan nama file test yang
  sudah ada untuk `ModulProgress` jika sudah tersedia — cek dulu sebelum
  membuat file baru)

- [ ] Test RED: `sudahSelesai()` true saat `halaman_terjauh == jumlah_halaman`,
  false jika kurang; `Modul::soal` relasi mengembalikan hanya soal aktif
  saat discope.
- [ ] Implementasi sampai GREEN.

## Task 4: Admin — CRUD Bank Soal per Modul

**Files:**
- Create: `app/Http/Controllers/Admin/ModulSoalController.php`
- Create: `resources/views/admin/modul/soal.blade.php` (atau bagian baru di
  `admin/modul/index.blade.php` — putuskan saat implementasi mana yang lebih
  bersih; cek dulu ukuran view yang sudah ada)
- Edit: `routes/web.php` (grup admin, di bawah `modul.*`)
- Test: `tests/Feature/Admin/ModulSoalControllerTest.php`

**Routes (di dalam grup admin `prefix('modul')->name('modul.')`):**
```php
Route::prefix('{modul}/soal')->name('soal.')->group(function () {
    Route::get('/', [AdminModulSoalController::class, 'index'])->name('index');
    Route::post('/', [AdminModulSoalController::class, 'store'])->name('store');
    Route::put('/{modulSoal}', [AdminModulSoalController::class, 'update'])->name('update');
    Route::patch('/{modulSoal}/toggle', [AdminModulSoalController::class, 'toggle'])->name('toggle');
});
```

**Validasi store/update:** `pertanyaan required|string|max:2000`,
`opsi_a..d required|string|max:500`, `kunci_jawaban required|in:a,b,c,d`,
`urutan required|integer|min:1`. Guard: `modulSoal` route-model-binding
harus milik `{modul}` di URL (`abort_unless($modulSoal->modul_id ===
$modul->id, 404)`), sama pola pengecekan kepemilikan yang dipakai di tempat
lain pada codebase ini (cek pola di `DokumenEvaluasi`/`Feedback` kalau ada).

- [ ] Test RED: admin bisa tambah/ubah/nonaktifkan soal; guru/kepala
  sekolah ditolak (403/404 sesuai middleware role yang sudah ada); soal
  dengan `modulSoal` milik modul lain ditolak.
- [ ] Implementasi sampai GREEN.

## Task 5: Guru — Status kuis + halaman kerjakan kuis

**Files:**
- Create: `app/Http/Controllers/Guru/ModulSoalController.php`
- Create: `resources/views/guru/modul/kuis.blade.php`
- Edit: `resources/views/guru/modul/baca.blade.php` (tambah bagian status
  kuis: terkunci / tersedia / sudah dikerjakan, sesuai spec)
- Edit: `routes/web.php` (grup guru, di bawah `modul.*`)
- Test: `tests/Feature/Guru/ModulSoalControllerTest.php`

**Routes:**
```php
Route::prefix('{modul}/soal')->name('soal.')->group(function () {
    Route::get('/', [GuruModulSoalController::class, 'create'])->name('create');
    Route::post('/', [GuruModulSoalController::class, 'store'])->name('store')->middleware('throttle:20,1');
});
```

**`create()`:** `abort_unless($modul->is_active, 404)`; guard progres 100%
→ redirect balik ke `guru.modul.show` dengan flash error jika belum; guard
`$modul->soal()->active()->exists()` → jika tidak ada soal, redirect dengan
flash info "Modul ini belum memiliki soal kuis."; tampilkan semua soal aktif
urut `urutan`.

**`store()`:** validasi `jawaban.*` array `[modul_soal_id => a|b|c|d]` sesuai
soal aktif modul ini saja (bangun aturan `in:` dinamis dari daftar id soal
aktif modul, atau validasi manual per key ada di `$modul->soal()->active()
->pluck('id')`); guard progres 100% ulang di server (jangan percaya bahwa
guru datang dari halaman yang benar); hitung skor, buat `ModulSoalAttempt` +
`ModulSoalAttemptJawaban` per soal (termasuk soal yang tidak dijawab →
`jawaban_dipilih` tidak valid, perlakukan sebagai salah — lihat catatan di
bawah); redirect ke halaman hasil attempt.

> Catatan implementasi: karena `jawaban_dipilih` di skema adalah enum wajib
> (`a|b|c|d`), soal yang tidak dijawab guru **tidak membuat baris jawaban**
> (bukan dipaksa jadi nilai enum palsu) — cukup tidak dihitung sebagai
> benar saat kalkulasi skor. Sebutkan ini eksplisit di test agar perilaku
> terkunci.

- [ ] Test RED: akses `create()` ditolak+redirect jika progres < 100%;
  ditolak jika modul tanpa soal aktif; `store()` menyimpan attempt dengan
  skor benar untuk kombinasi jawaban benar/salah/kosong; `store()` menolak
  progres < 100% walau request dikirim langsung (bukan lewat form);
  `store()` menolak `modul_soal_id` yang bukan milik modul ini.
- [ ] Implementasi sampai GREEN.

## Task 6: Guru — Halaman hasil kuis + riwayat, tombol ulangi

**Files:**
- Edit: `app/Http/Controllers/Guru/ModulSoalController.php` (tambah
  `hasil(ModulSoalAttempt $attempt)`)
- Create: `resources/views/guru/modul/hasil-kuis.blade.php`
- Edit: `routes/web.php`
- Test: lanjut di `tests/Feature/Guru/ModulSoalControllerTest.php`

**Route:** `GET {modul}/soal/hasil/{attempt}` → `name('hasil')`.

**Guard:** `abort_unless($attempt->user_id === auth()->id() &&
$attempt->modul_id === $modul->id, 404)` — guru hanya bisa melihat attempt
miliknya sendiri pada modul yang sesuai di URL.

**View:** skor besar (`skor / skor_maksimal`), daftar soal dengan
jawaban guru vs kunci jawaban benar/salah, tombol "Ulangi Kuis" ke
`guru.modul.soal.create`, tombol kembali ke halaman baca modul.

- [ ] Test RED: guru pemilik attempt bisa lihat hasil; guru lain ditolak
  404; hasil menampilkan skor & rincian benar/salah dengan benar.
- [ ] Implementasi sampai GREEN.

## Task 7: Kepala Sekolah — Kolom skor terbaik di rekap progres

**Files:**
- Edit: `app/Http/Controllers/KepalaSekolah/ModulProgressController.php`
- Edit: `resources/views/kepala/modul-progress/index.blade.php`
- Test: tambahkan ke test feature kepala sekolah rekap yang sudah ada
  (cek nama file dulu, kemungkinan
  `tests/Feature/KepalaSekolah/ModulProgressControllerTest.php`)

Tambah `'skor_terbaik' => ModulSoalAttempt::terbaikUntuk($guru->id,
$modul->id)?->skor` (dan `skor_maksimal` pendampingnya) ke tiap baris `$rows`
di kedua mode (`per-modul` dan `per-guru`). Tampilkan "—" bila `null`
(belum ada soal atau belum dikerjakan) di view, konsisten dengan pola kolom
persen yang sudah ada.

- [ ] Test RED: kolom skor terbaik muncul benar untuk guru yang sudah
  mengerjakan (beberapa attempt → ambil yang tertinggi), "—" untuk yang
  belum/modul tanpa soal.
- [ ] Implementasi sampai GREEN.

## Task 8: Navigasi & polish akhir

**Files:**
- Edit: `resources/views/admin/modul/index.blade.php` (tautan ke kelola
  soal per modul)
- Cek seluruh flow manual: admin tambah soal → guru baca modul 100% → guru
  kerjakan kuis → lihat hasil → ulangi → kepala sekolah lihat skor terbaik.
- Jalankan seluruh suite: `php artisan config:clear; php artisan test`.

- [ ] Tidak ada task baru — verifikasi end-to-end dan bersihkan sisa TODO.

---

## Di Luar Lingkup (ingatkan diri sendiri saat implementasi)

Esai/penilaian manual, integrasi ke `EvaluasiRubrik`/predikat, role
validator, video praktik guru + komentar, notifikasi, tanda tangan PDF —
semua ditolak masuk ke scope task-task di atas.
