# Notifikasi In-App Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Notifikasi dalam aplikasi (lonceng + badge + halaman) untuk 4 pemicu (tanggapan supervisi, supervisi perlu direview, modul baru, pengingat terjadwal), memakai sistem notifikasi bawaan Laravel.

**Architecture:** Channel `database` dari `illuminate/notifications`. Empat kelas Notification menulis ke tabel `notifications`. Pemicu dipasang inline di controller yang sudah ada. Lonceng disisipkan sekali di topbar bersama (`layouts/modern.blade.php`), diberi data lewat View Composer. Pengingat lewat Artisan command + `Schedule` di `routes/console.php`.

**Tech Stack:** Laravel 12, PHP 8.2, Blade + Tailwind, PHPUnit, `Notification::fake()`.

**Spec:** `docs/superpowers/specs/2026-07-10-notifikasi-design.md`

## Global Constraints

- **TDD-guard aktif & ketat:** setiap perubahan logika HARUS didahului test RED spesifik. Tulis test → jalankan sampai FAIL → implementasi minimal → jalankan sampai PASS → commit. Migrasi (skema) boleh dibuat tanpa test; kelas/logic tidak.
- **WAJIB `php artisan config:clear` sebelum menjalankan test apa pun** (insiden DB dev terhapus; guard di `Tests\TestCase`).
- Jalankan test via path file bila `--filter` bermasalah di Windows: `php artisan test tests/Feature/Notifikasi/XxxTest.php`.
- Semua teks UI bahasa Indonesia. Ikon = SVG inline (bukan emoji). Dark mode: tiap kelas warna punya varian `dark:`. Responsif mulai 375 px; lonceng WAJIB terjangkau di mobile.
- Notifikasi hanya pelengkap: kegagalan mengirim TIDAK boleh menggagalkan aksi utama → bungkus tiap pengiriman `try { ... } catch (\Throwable $e) { report($e); }`.
- Pesan commit bahasa Indonesia (`feat:`/`test:`/`refactor:`) diakhiri baris kosong lalu `Co-Authored-By: Claude Opus 4.8 <noreply@anthropic.com>`.
- Branch kerja: `develop`.
- Nama kelas Notification (dipakai lintas task, HARUS konsisten): `App\Notifications\SupervisiPerluDireview`, `App\Notifications\SupervisiDitanggapi`, `App\Notifications\ModulBaruDiunggah`, `App\Notifications\PengingatSupervisi`.
- Bentuk `data` tiap notifikasi (json) selalu: `['judul'=>string, 'pesan'=>string, 'ikon'=>string, 'url'=>string]`.

---

### Task 1: Tabel `notifications` + `SupervisiPerluDireview` + pemicu saat guru submit

**Files:**
- Create: `database/migrations/2026_07_10_120000_create_notifications_table.php`
- Create: `app/Notifications/SupervisiPerluDireview.php`
- Modify: `app/Http/Controllers/Guru/ProsesController.php` (method `submit`, sekitar baris 124-130)
- Test: `tests/Feature/Notifikasi/SupervisiPerluDireviewTest.php`

**Interfaces:**
- Consumes: `User::supervisi()` (hasMany), `Supervisi::user()` (belongsTo).
- Produces: kelas `SupervisiPerluDireview` (konstruktor `__construct(public Supervisi $supervisi)`, `via()=['database']`, `toArray()` mengembalikan bentuk `data` standar dengan `url = route('kepala.evaluasi.show', $supervisi->id)`). Tabel `notifications` (skema Laravel standar) — dipakai semua task berikutnya.

- [ ] **Step 1: Tulis migrasi tabel `notifications` (skema, boleh tanpa test)**

Buat `database/migrations/2026_07_10_120000_create_notifications_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
```

- [ ] **Step 2: Tulis test yang gagal**

Buat `tests/Feature/Notifikasi/SupervisiPerluDireviewTest.php`.

Catatan penting: `ProsesController::submit` HANYA mensyaratkan `ProsesPembelajaran` terisi (`link_video` + `refleksi_1..5`) dan supervisi berstatus draft/revision milik guru — cek 7 dokumen ada di `show()`, BUKAN di `submit()`. Jadi test bisa menjalankan rute submit sungguhan dengan setup ringan (factory `ProsesPembelajaran` mengisi semua field default). Rute: `guru.supervisi.submit` (POST, `routes/web.php:96`); `submit()` mengembalikan JSON `{success:true}`.

```php
<?php

namespace Tests\Feature\Notifikasi;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use App\Models\User;
use App\Notifications\SupervisiPerluDireview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SupervisiPerluDireviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_submit_mengirim_notifikasi_ke_kepala_sekolah_tingkat_sama_saja(): void
    {
        Notification::fake();

        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $kepalaSD = User::factory()->kepalaSekolah()->create(['is_active' => true, 'tingkat' => 'SD']);
        $kepalaSMP = User::factory()->kepalaSekolah()->create(['is_active' => true, 'tingkat' => 'SMP']);
        $kepalaNonaktif = User::factory()->kepalaSekolah()->create(['is_active' => false, 'tingkat' => 'SD']);
        $admin = User::factory()->admin()->create();

        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);
        ProsesPembelajaran::factory()->create(['supervisi_id' => $supervisi->id]);

        $this->actingAs($guru)
            ->post(route('guru.supervisi.submit', $supervisi->id))
            ->assertOk()
            ->assertJson(['success' => true]);

        Notification::assertSentTo($kepalaSD, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($kepalaSMP, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($kepalaNonaktif, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($admin, SupervisiPerluDireview::class);
        Notification::assertNotSentTo($guru, SupervisiPerluDireview::class);
    }
}
```

- [ ] **Step 3: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test tests/Feature/Notifikasi/SupervisiPerluDireviewTest.php`
Expected: FAIL — `Class "App\Notifications\SupervisiPerluDireview" not found`.

- [ ] **Step 4: Buat kelas notifikasi**

Buat `app/Notifications/SupervisiPerluDireview.php`:

```php
<?php

namespace App\Notifications;

use App\Models\Supervisi;
use Illuminate\Notifications\Notification;

class SupervisiPerluDireview extends Notification
{
    public function __construct(public Supervisi $supervisi)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => 'Supervisi perlu direview',
            'pesan' => $this->supervisi->user->name . ' mengirim supervisi untuk ditinjau.',
            'ikon' => 'review',
            'url' => route('kepala.evaluasi.show', $this->supervisi->id),
        ];
    }
}
```

- [ ] **Step 5: Jalankan test, pastikan lulus**

Run: `php artisan test tests/Feature/Notifikasi/SupervisiPerluDireviewTest.php`
Expected: PASS.

- [ ] **Step 6: Pasang pemicu di `submit`**

Di `app/Http/Controllers/Guru/ProsesController.php`, method `submit`, setelah blok `$supervisi->update([... 'status' => Supervisi::STATUS_SUBMITTED ...])` (sekitar baris 124-130) dan SEBELUM `return response()->json(...)`, tambahkan:

```php
        try {
            $penerima = \App\Models\User::where('role', 'kepala_sekolah')
                ->where('tingkat', $supervisi->user->tingkat)
                ->where('is_active', true)
                ->get();
            \Illuminate\Support\Facades\Notification::send($penerima, new \App\Notifications\SupervisiPerluDireview($supervisi));
        } catch (\Throwable $e) {
            report($e);
        }
```

(Pastikan `$supervisi->user` termuat — `$supervisi` sudah di-`firstOrFail()` di awal method; relasi `user` di-lazy-load otomatis.)

- [ ] **Step 7: Commit**

```bash
git add database/migrations/2026_07_10_120000_create_notifications_table.php app/Notifications/SupervisiPerluDireview.php app/Http/Controllers/Guru/ProsesController.php tests/Feature/Notifikasi/SupervisiPerluDireviewTest.php
git commit -m "feat: notifikasi supervisi perlu direview ke kepala sekolah saat guru submit"
```

---

### Task 2: `SupervisiDitanggapi` + pemicu di evaluasi kepsek & admin → guru

**Files:**
- Create: `app/Notifications/SupervisiDitanggapi.php`
- Modify: `app/Http/Controllers/KepalaSekolah/EvaluasiController.php` (`giveFeedback` ~113-142, `complete` ~167, `requestRevision` ~198-209)
- Modify: `app/Http/Controllers/Admin/SupervisiController.php` (`storeFeedback` ~90-115, `requestRevision` ~140-152)
- Test: `tests/Feature/Notifikasi/SupervisiDitanggapiTest.php`

**Interfaces:**
- Consumes: tabel `notifications` + pola `try/catch report` dari Task 1.
- Produces: kelas `SupervisiDitanggapi` — `__construct(public Supervisi $supervisi, public string $jenis)` dengan `$jenis` ∈ {`feedback`,`revisi`,`selesai`}; `via()=['database']`; `url = route('guru.supervisi.detail', $supervisi->id)`.

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/Notifikasi/SupervisiDitanggapiTest.php`:

```php
<?php

namespace Tests\Feature\Notifikasi;

use App\Models\RubrikItem;
use App\Models\EvaluasiRubrik;
use App\Models\Supervisi;
use App\Models\User;
use App\Notifications\SupervisiDitanggapi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SupervisiDitanggapiTest extends TestCase
{
    use RefreshDatabase;

    private function guruDanKepala(): array
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);

        return [$guru, $kepala];
    }

    public function test_kepala_feedback_mengirim_jenis_feedback_ke_guru(): void
    {
        Notification::fake();
        [$guru, $kepala] = $this->guruDanKepala();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Ini komentar minimal sepuluh karakter.',
        ])->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, function ($n) {
            return $n->jenis === 'feedback';
        });
    }

    public function test_kepala_request_revision_mengirim_jenis_revisi(): void
    {
        Notification::fake();
        [$guru, $kepala] = $this->guruDanKepala();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $this->actingAs($kepala)->post(route('kepala.evaluasi.revision', $supervisi->id), [
            'revision_notes' => 'Mohon perbaiki bagian ini ya.',
        ])->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, fn ($n) => $n->jenis === 'revisi');
    }

    public function test_kepala_complete_mengirim_jenis_selesai(): void
    {
        Notification::fake();
        [$guru, $kepala] = $this->guruDanKepala();

        RubrikItem::query()->delete();
        $item = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id, 'status' => 'under_review', 'reviewed_by' => $kepala->id]);
        EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [$item->id => 2], null);

        $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id))->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, fn ($n) => $n->jenis === 'selesai');
    }

    public function test_admin_feedback_mengirim_jenis_feedback(): void
    {
        Notification::fake();
        [$guru] = $this->guruDanKepala();
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $this->actingAs($admin)->post(route('admin.supervisi.feedback', $supervisi->id), [
            'komentar' => 'Komentar admin minimal sepuluh.',
        ])->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, fn ($n) => $n->jenis === 'feedback');
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test tests/Feature/Notifikasi/SupervisiDitanggapiTest.php`
Expected: FAIL — `Class "App\Notifications\SupervisiDitanggapi" not found`.

- [ ] **Step 3: Buat kelas notifikasi**

Buat `app/Notifications/SupervisiDitanggapi.php`:

```php
<?php

namespace App\Notifications;

use App\Models\Supervisi;
use Illuminate\Notifications\Notification;

class SupervisiDitanggapi extends Notification
{
    /** @param string $jenis feedback|revisi|selesai */
    public function __construct(public Supervisi $supervisi, public string $jenis)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $map = [
            'feedback' => ['Ada tanggapan pada supervisi Anda', 'Supervisi Anda mendapat tanggapan dari peninjau.', 'feedback'],
            'revisi' => ['Supervisi Anda perlu direvisi', 'Peninjau meminta revisi pada supervisi Anda.', 'revisi'],
            'selesai' => ['Supervisi Anda telah dinilai', 'Penilaian supervisi Anda sudah selesai.', 'nilai'],
        ];
        [$judul, $pesan, $ikon] = $map[$this->jenis];

        return [
            'judul' => $judul,
            'pesan' => $pesan,
            'ikon' => $ikon,
            'url' => route('guru.supervisi.detail', $this->supervisi->id),
        ];
    }
}
```

- [ ] **Step 4: Pasang pemicu (kepsek)**

Di `app/Http/Controllers/KepalaSekolah/EvaluasiController.php`:

`giveFeedback` — di dalam blok `if ($isRevisionRequest) { ... }` (setelah `$supervisi->update(['status'=>'revision', ...])`, sebelum `return`) tambahkan kirim jenis `revisi`; dan di jalur non-revisi (setelah blok promote `under_review`, sebelum `return redirect()->route('kepala.evaluasi.show', $id)->with('success', 'Feedback berhasil diberikan')`) tambahkan kirim jenis `feedback`. Pola kirim (ganti `$jenis`):

```php
        try {
            $supervisi->user->notify(new \App\Notifications\SupervisiDitanggapi($supervisi, 'feedback'));
        } catch (\Throwable $e) {
            report($e);
        }
```

`requestRevision` — setelah `$supervisi->update(['status'=>'revision', ...])`, sebelum `return`, kirim jenis `revisi`.

`complete` — setelah `$supervisi->update(['status'=>'completed', ...])`, sebelum `return`, kirim jenis `selesai`.

- [ ] **Step 5: Pasang pemicu (admin)**

Di `app/Http/Controllers/Admin/SupervisiController.php`:

`storeFeedback` — di jalur `mark_completed` (setelah update `STATUS_COMPLETED`, sebelum return) kirim jenis `selesai`; di jalur biasa (sebelum `return ...('Feedback berhasil diberikan!')`) kirim jenis `feedback`.

`requestRevision` — setelah `Feedback::create([...])`, sebelum `return`, kirim jenis `revisi`.

Pola sama seperti Step 4 (`$supervisi->user->notify(new \App\Notifications\SupervisiDitanggapi($supervisi, '<jenis>'))` dibungkus try/catch).

- [ ] **Step 6: Jalankan test, pastikan lulus + regresi**

Run: `php artisan test tests/Feature/Notifikasi/SupervisiDitanggapiTest.php`
Expected: PASS (4 test).

Run: `php artisan test tests/Feature/KepalaSekolah/EvaluasiTest.php tests/Feature/Admin/SupervisiReviewTest.php`
Expected: PASS (perilaku redirect/status controller tak berubah).

- [ ] **Step 7: Commit**

```bash
git add app/Notifications/SupervisiDitanggapi.php app/Http/Controllers/KepalaSekolah/EvaluasiController.php app/Http/Controllers/Admin/SupervisiController.php tests/Feature/Notifikasi/SupervisiDitanggapiTest.php
git commit -m "feat: notifikasi tanggapan supervisi (feedback/revisi/selesai) ke guru"
```

---

### Task 3: `ModulBaruDiunggah` + pemicu saat admin unggah modul → semua guru aktif

**Files:**
- Create: `app/Notifications/ModulBaruDiunggah.php`
- Modify: `app/Http/Controllers/Admin/ModulController.php` (method `store`, setelah `Modul::create` ~49-51)
- Test: `tests/Feature/Notifikasi/ModulBaruDiunggahTest.php`

**Interfaces:**
- Consumes: tabel `notifications`.
- Produces: kelas `ModulBaruDiunggah` — `__construct(public Modul $modul)`, `via()=['database']`, `url = route('guru.modul.index')`.

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/Notifikasi/ModulBaruDiunggahTest.php`:

```php
<?php

namespace Tests\Feature\Notifikasi;

use App\Models\ModulKategori;
use App\Models\User;
use App\Notifications\ModulBaruDiunggah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ModulBaruDiunggahTest extends TestCase
{
    use RefreshDatabase;

    private function pdf(): UploadedFile
    {
        // PDF minimal 1 halaman valid untuk smalot/pdfparser
        $isi = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]>>endobj\nxref\n0 4\n0000000000 65535 f \ntrailer<</Root 1 0 R/Size 4>>\nstartxref\n0\n%%EOF";
        return UploadedFile::fake()->createWithContent('modul.pdf', $isi);
    }

    public function test_unggah_modul_mengirim_notifikasi_ke_semua_guru_aktif(): void
    {
        Notification::fake();
        Storage::fake('local');

        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $guruA = User::factory()->guru()->create(['is_active' => true]);
        $guruB = User::factory()->guru()->create(['is_active' => true]);
        $guruNonaktif = User::factory()->guru()->create(['is_active' => false]);
        $kepala = User::factory()->kepalaSekolah()->create();
        $kategori = ModulKategori::factory()->create();

        $this->actingAs($admin)->post(route('admin.modul.store'), [
            'judul' => 'Modul Notifikasi',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->pdf(),
        ]);

        Notification::assertSentTo($guruA, ModulBaruDiunggah::class);
        Notification::assertSentTo($guruB, ModulBaruDiunggah::class);
        Notification::assertNotSentTo($guruNonaktif, ModulBaruDiunggah::class);
        Notification::assertNotSentTo($kepala, ModulBaruDiunggah::class);
    }
}
```

Catatan: bila PDF minimal di atas gagal dihitung `smalot/pdfparser` (unggahan ditolak), pakai pola `pdfContent()` yang sudah ada di `tests/Feature/Admin/ModulManagementTest.php` — salin helper pembuat PDF-nya agar unggahan lolos hitung halaman.

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test tests/Feature/Notifikasi/ModulBaruDiunggahTest.php`
Expected: FAIL — `Class "App\Notifications\ModulBaruDiunggah" not found`.

- [ ] **Step 3: Buat kelas notifikasi**

Buat `app/Notifications/ModulBaruDiunggah.php`:

```php
<?php

namespace App\Notifications;

use App\Models\Modul;
use Illuminate\Notifications\Notification;

class ModulBaruDiunggah extends Notification
{
    public function __construct(public Modul $modul)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => 'Modul ajar baru',
            'pesan' => 'Modul baru "' . $this->modul->judul . '" telah tersedia untuk dibaca.',
            'ikon' => 'modul',
            'url' => route('guru.modul.index'),
        ];
    }
}
```

- [ ] **Step 4: Pasang pemicu di `store`**

Di `app/Http/Controllers/Admin/ModulController.php`, method `store`, setelah `$this->syncVideos($modul, ...)` (baris ~51) dan SEBELUM `return redirect()->route('admin.modul.index')...`:

```php
        try {
            $guru = \App\Models\User::where('role', 'guru')->where('is_active', true)->get();
            \Illuminate\Support\Facades\Notification::send($guru, new \App\Notifications\ModulBaruDiunggah($modul));
        } catch (\Throwable $e) {
            report($e);
        }
```

- [ ] **Step 5: Jalankan test, pastikan lulus + regresi**

Run: `php artisan test tests/Feature/Notifikasi/ModulBaruDiunggahTest.php tests/Feature/Admin/ModulManagementTest.php`
Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add app/Notifications/ModulBaruDiunggah.php app/Http/Controllers/Admin/ModulController.php tests/Feature/Notifikasi/ModulBaruDiunggahTest.php
git commit -m "feat: notifikasi modul baru ke semua guru aktif saat admin unggah"
```

---

### Task 4: `PengingatSupervisi` + command terjadwal 2×/minggu

**Files:**
- Create: `app/Notifications/PengingatSupervisi.php`
- Create: `app/Console/Commands/KirimPengingatSupervisi.php`
- Modify: `routes/console.php`
- Test: `tests/Feature/Notifikasi/PengingatSupervisiTest.php`

**Interfaces:**
- Consumes: `User::supervisi()` (hasMany), trait `Notifiable` (`unreadNotifications()`).
- Produces: kelas `PengingatSupervisi` (tanpa argumen konstruktor), command signature `notifikasi:pengingat-supervisi`.

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/Notifikasi/PengingatSupervisiTest.php`:

```php
<?php

namespace Tests\Feature\Notifikasi;

use App\Models\Supervisi;
use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PengingatSupervisiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengingat_terkirim_ke_guru_tanpa_supervisi_aktif(): void
    {
        Notification::fake();

        $belumMulai = User::factory()->guru()->create(['is_active' => true]);
        $draftSaja = User::factory()->guru()->create(['is_active' => true]);
        Supervisi::factory()->draft()->create(['user_id' => $draftSaja->id]);

        $sudahSubmit = User::factory()->guru()->create(['is_active' => true]);
        Supervisi::factory()->submitted()->create(['user_id' => $sudahSubmit->id]);
        $sudahSelesai = User::factory()->guru()->create(['is_active' => true]);
        Supervisi::factory()->completed()->create(['user_id' => $sudahSelesai->id]);
        $nonaktif = User::factory()->guru()->create(['is_active' => false]);

        $this->artisan('notifikasi:pengingat-supervisi')->assertExitCode(0);

        Notification::assertSentTo($belumMulai, PengingatSupervisi::class);
        Notification::assertSentTo($draftSaja, PengingatSupervisi::class);
        Notification::assertNotSentTo($sudahSubmit, PengingatSupervisi::class);
        Notification::assertNotSentTo($sudahSelesai, PengingatSupervisi::class);
        Notification::assertNotSentTo($nonaktif, PengingatSupervisi::class);
    }

    public function test_guru_dengan_pengingat_belum_dibaca_tidak_dapat_dobel(): void
    {
        $guru = User::factory()->guru()->create(['is_active' => true]);
        $guru->notify(new PengingatSupervisi()); // 1 pengingat belum dibaca tersimpan

        Notification::fake(); // hitung hanya pengiriman SETELAH ini
        $this->artisan('notifikasi:pengingat-supervisi')->assertExitCode(0);

        Notification::assertNotSentTo($guru, PengingatSupervisi::class);
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test tests/Feature/Notifikasi/PengingatSupervisiTest.php`
Expected: FAIL — command `notifikasi:pengingat-supervisi` tidak ditemukan / kelas `PengingatSupervisi` tidak ada.

- [ ] **Step 3: Buat kelas notifikasi**

Buat `app/Notifications/PengingatSupervisi.php`:

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PengingatSupervisi extends Notification
{
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'judul' => 'Pengingat pengisian supervisi',
            'pesan' => 'Jangan lupa melengkapi dan mengirim supervisi Anda.',
            'ikon' => 'pengingat',
            'url' => route('guru.home'),
        ];
    }
}
```

- [ ] **Step 4: Buat command**

Buat `app/Console/Commands/KirimPengingatSupervisi.php`:

```php
<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Console\Command;

class KirimPengingatSupervisi extends Command
{
    protected $signature = 'notifikasi:pengingat-supervisi';

    protected $description = 'Kirim pengingat pengisian supervisi ke guru yang belum mulai atau draftnya mangkrak';

    public function handle(): int
    {
        $guru = User::where('role', 'guru')
            ->where('is_active', true)
            ->whereDoesntHave('supervisi', function ($q) {
                $q->whereIn('status', ['submitted', 'under_review', 'completed']);
            })
            ->get();

        $terkirim = 0;
        foreach ($guru as $g) {
            $sudahAda = $g->unreadNotifications()
                ->where('type', PengingatSupervisi::class)
                ->exists();
            if ($sudahAda) {
                continue;
            }
            $g->notify(new PengingatSupervisi());
            $terkirim++;
        }

        $this->info("Pengingat terkirim ke {$terkirim} guru.");

        return self::SUCCESS;
    }
}
```

- [ ] **Step 5: Jalankan test, pastikan lulus**

Run: `php artisan test tests/Feature/Notifikasi/PengingatSupervisiTest.php`
Expected: PASS (2 test).

- [ ] **Step 6: Jadwalkan command**

Ganti isi `routes/console.php` menjadi:

```php
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Pengingat pengisian supervisi: Senin (1) & Kamis (4) pukul 07:00
Schedule::command('notifikasi:pengingat-supervisi')->twiceWeekly(1, 4, '07:00');
```

- [ ] **Step 7: Verifikasi jadwal terdaftar + commit**

Run: `php artisan schedule:list`
Expected: menampilkan `notifikasi:pengingat-supervisi` dengan jadwal Senin & Kamis 07:00.

```bash
git add app/Notifications/PengingatSupervisi.php app/Console/Commands/KirimPengingatSupervisi.php routes/console.php tests/Feature/Notifikasi/PengingatSupervisiTest.php
git commit -m "feat: command pengingat supervisi terjadwal 2x seminggu (Senin & Kamis)"
```

---

### Task 5: NotifikasiController + rute + View Composer + partial item + halaman daftar

**Files:**
- Create: `app/Http/Controllers/NotifikasiController.php`
- Create: `resources/views/notifikasi/index.blade.php`
- Create: `resources/views/notifikasi/_item.blade.php`
- Modify: `app/Providers/AppServiceProvider.php` (method `boot` — View Composer)
- Modify: `routes/web.php` (grup auth bersama, setelah rute settings ~71)
- Test: `tests/Feature/Notifikasi/NotifikasiHalamanTest.php`

**Interfaces:**
- Consumes: tabel `notifications`, trait `Notifiable` (`notifications()`, `unreadNotifications()`, `markAsRead()`).
- Produces: rute bernama `notifikasi.index`, `notifikasi.buka`, `notifikasi.baca-semua`. View Composer men-share `$unreadNotifCount` (int) dan `$recentNotifs` (Collection of DatabaseNotification) ke view `layouts.modern` — dipakai Task 6. Partial `notifikasi._item` menerima variabel `$n` (satu DatabaseNotification) — dipakai halaman ini & dropdown Task 6.

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/Notifikasi/NotifikasiHalamanTest.php`:

```php
<?php

namespace Tests\Feature\Notifikasi;

use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifikasiHalamanTest extends TestCase
{
    use RefreshDatabase;

    private function guru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    public function test_halaman_notifikasi_tampil(): void
    {
        $guru = $this->guru();
        $guru->notify(new PengingatSupervisi());

        $this->actingAs($guru)->get(route('notifikasi.index'))
            ->assertStatus(200)
            ->assertSee('Pengingat pengisian supervisi');
    }

    public function test_buka_menandai_terbaca_dan_redirect(): void
    {
        $guru = $this->guru();
        $guru->notify(new PengingatSupervisi());
        $notif = $guru->notifications()->first();

        $this->actingAs($guru)->get(route('notifikasi.buka', $notif->id))
            ->assertRedirect(route('guru.home'));

        $this->assertNotNull($notif->fresh()->read_at);
    }

    public function test_baca_semua_mengosongkan_belum_dibaca(): void
    {
        $guru = $this->guru();
        $guru->notify(new PengingatSupervisi());
        $guru->notify(new PengingatSupervisi());

        $this->actingAs($guru)->post(route('notifikasi.baca-semua'))->assertRedirect();

        $this->assertSame(0, $guru->unreadNotifications()->count());
    }

    public function test_tidak_bisa_buka_notifikasi_milik_orang_lain(): void
    {
        $guru = $this->guru();
        $lain = $this->guru();
        $lain->notify(new PengingatSupervisi());
        $notif = $lain->notifications()->first();

        $this->actingAs($guru)->get(route('notifikasi.buka', $notif->id))->assertNotFound();
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test tests/Feature/Notifikasi/NotifikasiHalamanTest.php`
Expected: FAIL — rute `notifikasi.index` tidak terdefinisi.

- [ ] **Step 3: Buat controller**

Buat `app/Http/Controllers/NotifikasiController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Auth::user()->notifications()->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function buka(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        $url = $notif->data['url'] ?? route('notifikasi.index');

        return redirect($url);
    }

    public function bacaSemua()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
```

- [ ] **Step 4: Daftarkan rute**

Di `routes/web.php`, di dalam grup `Route::middleware(['auth', 'prevent.back', 'must.change.password'])` (tempat rute `settings.*` berada, sekitar baris 68-71), tambahkan:

```php
    // Notifikasi (semua peran)
    Route::get('/notifikasi', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/{id}/buka', [\App\Http\Controllers\NotifikasiController::class, 'buka'])->name('notifikasi.buka');
    Route::post('/notifikasi/baca-semua', [\App\Http\Controllers\NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca-semua');
```

- [ ] **Step 5: Buat partial item + halaman daftar**

Buat `resources/views/notifikasi/_item.blade.php` (satu baris notifikasi; `$n` = DatabaseNotification):

```blade
<a href="{{ route('notifikasi.buka', $n->id) }}"
   class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $n->read_at ? '' : 'bg-primary-50/60 dark:bg-primary-900/10' }}">
    <span class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
    </span>
    <span class="flex-1 min-w-0">
        <span class="block text-sm font-semibold text-gray-900 dark:text-white">{{ $n->data['judul'] ?? 'Notifikasi' }}</span>
        <span class="block text-xs text-gray-600 dark:text-gray-400">{{ $n->data['pesan'] ?? '' }}</span>
        <span class="block text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $n->created_at->diffForHumans() }}</span>
    </span>
    @unless($n->read_at)
        <span class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-primary-500" aria-label="Belum dibaca"></span>
    @endunless
</a>
```

Buat `resources/views/notifikasi/index.blade.php`:

```blade
@extends('layouts.modern')

@section('title', 'Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto px-3 sm:px-4 py-4 sm:py-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">Notifikasi</h1>
        @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
            @csrf
            <button type="submit" class="text-xs sm:text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">Tandai semua terbaca</button>
        </form>
        @endif
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden divide-y divide-gray-100 dark:divide-gray-700">
        @forelse($notifikasi as $n)
            @include('notifikasi._item', ['n' => $n])
        @empty
            <div class="text-center py-10 px-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada notifikasi.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifikasi->links() }}
    </div>
</div>
@endsection
```

- [ ] **Step 6: Tambah View Composer**

Di `app/Providers/AppServiceProvider.php`, di method `boot()`, tambahkan (tambahkan `use Illuminate\Support\Facades\View;` di atas bila belum ada):

```php
        \Illuminate\Support\Facades\View::composer('layouts.modern', function ($view) {
            $user = auth()->user();
            $view->with('unreadNotifCount', $user ? $user->unreadNotifications()->count() : 0);
            $view->with('recentNotifs', $user ? $user->notifications()->take(5)->get() : collect());
        });
```

- [ ] **Step 7: Jalankan test, pastikan lulus**

Run: `php artisan test tests/Feature/Notifikasi/NotifikasiHalamanTest.php`
Expected: PASS (4 test).

- [ ] **Step 8: Commit**

```bash
git add app/Http/Controllers/NotifikasiController.php resources/views/notifikasi/ app/Providers/AppServiceProvider.php routes/web.php tests/Feature/Notifikasi/NotifikasiHalamanTest.php
git commit -m "feat: halaman notifikasi, rute buka/baca-semua, view composer badge"
```

---

### Task 6: Lonceng di topbar + dropdown + badge + menu sidebar

**Files:**
- Modify: `resources/views/layouts/modern.blade.php` (topbar ~348-349, sidebar nav ~479, opsional bottom-nav mobile ~677)
- Test: `tests/Feature/Notifikasi/LoncengTopbarTest.php`

**Interfaces:**
- Consumes: `$unreadNotifCount` & `$recentNotifs` dari View Composer (Task 5); partial `notifikasi._item` (Task 5); rute `notifikasi.index`, `notifikasi.baca-semua` (Task 5).
- Produces: — (task UI terakhir).

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/Notifikasi/LoncengTopbarTest.php`:

```php
<?php

namespace Tests\Feature\Notifikasi;

use App\Models\User;
use App\Notifications\PengingatSupervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoncengTopbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_lonceng_tampil_di_topbar_guru(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $this->actingAs($guru)->get(route('guru.home'))
            ->assertStatus(200)
            ->assertSee('id="notif-dropdown-btn"', false);
    }

    public function test_badge_menampilkan_jumlah_belum_dibaca(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $guru->notify(new PengingatSupervisi());
        $guru->notify(new PengingatSupervisi());

        $this->actingAs($guru)->get(route('guru.home'))
            ->assertSee('data-notif-badge', false)
            ->assertSee('Pengingat pengisian supervisi');
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test tests/Feature/Notifikasi/LoncengTopbarTest.php`
Expected: FAIL — `id="notif-dropdown-btn"` belum ada di topbar.

- [ ] **Step 3: Sisipkan lonceng di topbar**

Di `resources/views/layouts/modern.blade.php`, di dalam `<header>`, TEPAT SEBELUM blok `<!-- Right: Profile Dropdown (Hidden on mobile, show on md+) --> <div class="hidden md:flex ...">` (baris ~348), sisipkan wadah lonceng yang **selalu terlihat** (termasuk mobile). Bungkus keduanya dalam satu flex bila perlu; contoh sisipan:

```blade
                <!-- Notifikasi Bell (semua ukuran layar) -->
                <div class="relative ml-auto md:ml-0">
                    <button id="notif-dropdown-btn" type="button" aria-label="Notifikasi"
                            class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if(($unreadNotifCount ?? 0) > 0)
                        <span data-notif-badge class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-red-600 text-white text-[10px] font-bold flex items-center justify-center">
                            {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                        </span>
                        @endif
                    </button>

                    <div id="notif-dropdown-menu" class="hidden absolute right-0 mt-2 w-80 max-w-[calc(100vw-1.5rem)] bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">Notifikasi</span>
                            @if(($unreadNotifCount ?? 0) > 0)
                            <form method="POST" action="{{ route('notifikasi.baca-semua') }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">Tandai semua</button>
                            </form>
                            @endif
                        </div>
                        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse(($recentNotifs ?? collect()) as $n)
                                @include('notifikasi._item', ['n' => $n])
                            @empty
                                <p class="text-center text-sm text-gray-500 dark:text-gray-400 py-8">Belum ada notifikasi.</p>
                            @endforelse
                        </div>
                        <a href="{{ route('notifikasi.index') }}" class="block text-center text-sm font-semibold text-primary-600 dark:text-primary-400 py-3 border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">Lihat semua</a>
                    </div>
                </div>
```

Catatan penempatan: wadah lonceng harus terlihat di mobile (jangan taruh di dalam `hidden md:flex`). Bila perlu, bungkus lonceng + blok profil dalam satu `<div class="flex items-center gap-1">` agar sejajar di desktop; lonceng tetap tampil sendiri saat profil tersembunyi di mobile.

- [ ] **Step 4: Tambah toggle JS dropdown**

Ikuti pola dropdown profil yang sudah ada (`profile-dropdown-btn` / `profile-dropdown-menu`, lihat sekitar baris 1010+ dan handler klik di ~1258). Tambahkan handler serupa untuk `notif-dropdown-btn` / `notif-dropdown-menu`: toggle class `hidden` saat tombol diklik, dan tutup saat klik di luar. Sisipkan di blok `<script>` yang sama tempat dropdown profil ditangani (cari `profile-dropdown-menu` untuk menemukan lokasinya) agar konsisten.

- [ ] **Step 5: Tambah menu "Notifikasi" di sidebar (bersama semua peran)**

Di `<nav>` sidebar (mulai ~478), tambahkan satu item bersama SEBELUM percabangan `@if(Auth::user()->isAdmin())` (baris ~479), mengikuti gaya item lain (SVG inline + label). Contoh:

```blade
                    <a href="{{ route('notifikasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('notifikasi.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300' : '' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="flex-1">Notifikasi</span>
                        @if(($unreadNotifCount ?? 0) > 0)
                        <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-red-600 text-white text-[10px] font-bold flex items-center justify-center">{{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}</span>
                        @endif
                    </a>
```

- [ ] **Step 6: Jalankan test, pastikan lulus + regresi**

Run: `php artisan test tests/Feature/Notifikasi/LoncengTopbarTest.php`
Expected: PASS (2 test).

Run: `php artisan test tests/Feature/LayoutDivBalanceTest.php tests/Feature/DarkModeCoverageTest.php`
Expected: PASS (div seimbang, dark mode).

- [ ] **Step 7: Commit**

```bash
git add resources/views/layouts/modern.blade.php tests/Feature/Notifikasi/LoncengTopbarTest.php
git commit -m "feat: lonceng notifikasi + badge + dropdown di topbar & menu sidebar"
```

---

### Task 7: Verifikasi penuh

**Files:** — (tidak ada perubahan kode; hanya verifikasi)

- [ ] **Step 1: Suite PHP penuh**

Run: `php artisan config:clear && php artisan test`
Expected: PASS semua (305 lama + ~14 baru), 0 failure.

- [ ] **Step 2: Suite JS + build**

Run: `npm run test` → PASS (13 vitest, tak ada JS baru signifikan).
Run: `npm run build` → sukses.

- [ ] **Step 3: Cek jadwal**

Run: `php artisan schedule:list`
Expected: `notifikasi:pengingat-supervisi` terjadwal Senin & Kamis 07:00.

- [ ] **Step 4: Laporkan hasil**

Tidak ada commit. Laporkan jumlah test, status build, dan jadwal. Smoke test browser dilakukan sesi utama (lihat Verifikasi Akhir di spec).
