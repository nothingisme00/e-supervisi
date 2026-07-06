# Modul Ajar Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Admin mengunggah modul ajar PDF (+tautan YouTube), guru membacanya lewat penampil PDF.js yang melacak halaman terjauh, kepala sekolah melihat rekap progres baca semua guru.

**Architecture:** Empat tabel baru (`modul_kategoris`, `moduls`, `modul_videos`, `modul_progress`), tiga controller per peran mengikuti pola yang sudah ada (`Admin\RubrikItemController` dsb.), satu service `PdfPageCounter` (smalot/pdfparser) untuk menghitung jumlah halaman server-side, dan satu bundel JS `modul-reader.js` (pdfjs-dist via Vite) untuk penampil + pengiriman progres.

**Tech Stack:** Laravel 12 / PHP 8.2, Blade + Tailwind (layout `layouts.modern`), Vite, `pdfjs-dist` (npm), `smalot/pdfparser` (composer), PHPUnit 11.

**Spec:** `docs/superpowers/specs/2026-07-07-modul-ajar-design.md`

## Global Constraints

- **WAJIB `php artisan config:clear` sebelum setiap sesi test** — config cache dev pernah menghapus DB dev (guard ada di `tests/TestCase.php`; test gagal keras kalau koneksi bukan sqlite `:memory:`).
- **TDD-guard hook aktif**: implementasi ditolak tanpa test RED spesifik lebih dulu. Jika hook menolak batch test, tambahkan test satu per satu (tulis 1 test → RED → implement → GREEN → test berikutnya).
- Semua teks UI **Bahasa Indonesia**; semua elemen visual wajib punya varian **dark mode** (`dark:` classes); ikon **SVG inline** (pola Heroicons stroke), bukan emoji/font-icon.
- PDF disimpan di disk **`local`** (privat), folder `modul/`, maksimal **20 MB** (`max:20480`), hanya `mimes:pdf`.
- `jumlah_halaman` dihitung **server-side** dari file, tidak pernah dari input browser.
- Endpoint progres: **hanya menaikkan** `halaman_terjauh`, tolak di luar rentang `1..jumlah_halaman`.
- View extends `layouts.modern` dengan `@section('page-title', ...)` + `@section('content')`; reuse komponen `x-card-header`, `x-status-badge`, `x-empty-state`.
- Commit sering; pesan commit Bahasa Indonesia berprefiks `feat:`/`test:`/`chore:` sesuai kebiasaan repo, diakhiri `Co-Authored-By: Claude Fable 5 <noreply@anthropic.com>`.
- Jalankan test dengan `php artisan test --filter=NamaTest`.

---

### Task 1: Migrasi & model ModulKategori + Modul

**Files:**
- Create: `database/migrations/2026_07_07_100001_create_modul_kategoris_table.php`
- Create: `database/migrations/2026_07_07_100002_create_moduls_table.php`
- Create: `app/Models/ModulKategori.php`
- Create: `app/Models/Modul.php`
- Create: `database/factories/ModulKategoriFactory.php`
- Create: `database/factories/ModulFactory.php`
- Test: `tests/Unit/Models/ModulModelTest.php`

**Interfaces:**
- Consumes: —
- Produces: model `App\Models\Modul` (fillable: `judul, deskripsi, modul_kategori_id, file_path, jumlah_halaman, is_active`; scope `active()`; relasi `kategori(): BelongsTo`), model `App\Models\ModulKategori` (fillable: `nama, is_active`; scope `active()`; relasi `moduls(): HasMany`), factory `Modul::factory()` (default `jumlah_halaman = 10`, `is_active = true`) dan `ModulKategori::factory()`.

- [ ] **Step 1: Tulis test yang gagal**

`tests/Unit/Models/ModulModelTest.php`:

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Modul;
use App\Models\ModulKategori;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_active_excludes_inactive_modul(): void
    {
        Modul::factory()->create(['is_active' => true]);
        Modul::factory()->create(['is_active' => false]);

        $this->assertSame(1, Modul::active()->count());
    }

    public function test_modul_belongs_to_kategori(): void
    {
        $kategori = ModulKategori::factory()->create(['nama' => 'Pedagogik']);
        $modul = Modul::factory()->create(['modul_kategori_id' => $kategori->id]);

        $this->assertSame('Pedagogik', $modul->kategori->nama);
    }

    public function test_kategori_scope_active_excludes_inactive(): void
    {
        ModulKategori::factory()->create(['is_active' => true]);
        ModulKategori::factory()->create(['is_active' => false]);

        $this->assertSame(1, ModulKategori::active()->count());
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter=ModulModelTest`
Expected: FAIL — `Class "App\Models\Modul" not found`

- [ ] **Step 3: Buat migrasi, model, dan factory**

`database/migrations/2026_07_07_100001_create_modul_kategoris_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_kategoris');
    }
};
```

`database/migrations/2026_07_07_100002_create_moduls_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moduls', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->foreignId('modul_kategori_id')->constrained('modul_kategoris')->restrictOnDelete();
            $table->string('file_path');
            $table->unsignedSmallInteger('jumlah_halaman');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moduls');
    }
};
```

`app/Models/ModulKategori.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModulKategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function moduls(): HasMany
    {
        return $this->hasMany(Modul::class);
    }
}
```

`app/Models/Modul.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modul extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul', 'deskripsi', 'modul_kategori_id', 'file_path', 'jumlah_halaman', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jumlah_halaman' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(ModulKategori::class, 'modul_kategori_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ModulVideo::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ModulProgress::class);
    }
}
```

(Relasi `videos()`/`progress()` menunjuk model yang baru dibuat di Task 2 — tidak dipanggil oleh test Task 1, jadi aman.)

`database/factories/ModulKategoriFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ModulKategoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(2, true),
            'is_active' => true,
        ];
    }
}
```

`database/factories/ModulFactory.php`:

```php
<?php

namespace Database\Factories;

use App\Models\ModulKategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModulFactory extends Factory
{
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'modul_kategori_id' => ModulKategori::factory(),
            'file_path' => 'modul/' . fake()->uuid() . '.pdf',
            'jumlah_halaman' => 10,
            'is_active' => true,
        ];
    }
}
```

- [ ] **Step 4: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter=ModulModelTest`
Expected: PASS (3 tests)

- [ ] **Step 5: Commit**

```bash
git add database/migrations/2026_07_07_100001_create_modul_kategoris_table.php database/migrations/2026_07_07_100002_create_moduls_table.php app/Models/ModulKategori.php app/Models/Modul.php database/factories/ModulKategoriFactory.php database/factories/ModulFactory.php tests/Unit/Models/ModulModelTest.php
git commit -m "feat: tabel & model modul ajar + kategori"
```

---

### Task 2: Migrasi & model ModulVideo + ModulProgress

**Files:**
- Create: `database/migrations/2026_07_07_100003_create_modul_videos_table.php`
- Create: `database/migrations/2026_07_07_100004_create_modul_progress_table.php`
- Create: `app/Models/ModulVideo.php`
- Create: `app/Models/ModulProgress.php`
- Test: `tests/Unit/Models/ModulProgressModelTest.php`
- Test: `tests/Unit/Models/ModulVideoModelTest.php`

**Interfaces:**
- Consumes: `Modul::factory()` dari Task 1.
- Produces: `App\Models\ModulProgress` (table `modul_progress`; fillable `user_id, modul_id, halaman_terjauh, terakhir_dibuka_at`; method `persen(): int` — clamp 0–100, aman untuk pembagian nol; relasi `modul(): BelongsTo`, `user(): BelongsTo`), `App\Models\ModulVideo` (fillable `modul_id, judul, youtube_url`; accessor `youtube_embed_url` → `https://www.youtube.com/embed/{id}` atau `null` bila URL tak dikenali).

- [ ] **Step 1: Tulis test yang gagal**

`tests/Unit/Models/ModulProgressModelTest.php`:

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Modul;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulProgressModelTest extends TestCase
{
    use RefreshDatabase;

    private function makeProgress(int $halaman, int $jumlahHalaman): ModulProgress
    {
        $modul = Modul::factory()->create(['jumlah_halaman' => $jumlahHalaman]);
        $guru = User::factory()->guru()->create();

        return ModulProgress::create([
            'user_id' => $guru->id,
            'modul_id' => $modul->id,
            'halaman_terjauh' => $halaman,
        ]);
    }

    public function test_persen_dihitung_dari_halaman_terjauh(): void
    {
        $this->assertSame(30, $this->makeProgress(3, 10)->persen());
    }

    public function test_persen_dibatasi_100_saat_pdf_diganti_lebih_pendek(): void
    {
        // halaman_terjauh 8 tapi PDF baru hanya 4 halaman
        $this->assertSame(100, $this->makeProgress(8, 4)->persen());
    }

    public function test_pasangan_user_modul_unik(): void
    {
        $progress = $this->makeProgress(1, 10);

        $this->expectException(\Illuminate\Database\QueryException::class);
        ModulProgress::create([
            'user_id' => $progress->user_id,
            'modul_id' => $progress->modul_id,
            'halaman_terjauh' => 2,
        ]);
    }
}
```

`tests/Unit/Models/ModulVideoModelTest.php`:

```php
<?php

namespace Tests\Unit\Models;

use App\Models\Modul;
use App\Models\ModulVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulVideoModelTest extends TestCase
{
    use RefreshDatabase;

    private function makeVideo(string $url): ModulVideo
    {
        return ModulVideo::create([
            'modul_id' => Modul::factory()->create()->id,
            'judul' => 'Video uji',
            'youtube_url' => $url,
        ]);
    }

    public function test_embed_url_dari_format_watch(): void
    {
        $video = $this->makeVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->youtube_embed_url);
    }

    public function test_embed_url_dari_format_pendek(): void
    {
        $video = $this->makeVideo('https://youtu.be/dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->youtube_embed_url);
    }

    public function test_embed_url_null_untuk_url_tak_dikenali(): void
    {
        $video = $this->makeVideo('https://example.com/video');

        $this->assertNull($video->youtube_embed_url);
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan test --filter=ModulProgressModelTest; php artisan test --filter=ModulVideoModelTest`
Expected: FAIL — `Class "App\Models\ModulProgress" not found` (dan ModulVideo)

- [ ] **Step 3: Buat migrasi dan model**

`database/migrations/2026_07_07_100003_create_modul_videos_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_id')->constrained('moduls')->cascadeOnDelete();
            $table->string('judul');
            $table->string('youtube_url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_videos');
    }
};
```

`database/migrations/2026_07_07_100004_create_modul_progress_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('modul_id')->constrained('moduls')->cascadeOnDelete();
            $table->unsignedSmallInteger('halaman_terjauh')->default(1);
            $table->timestamp('terakhir_dibuka_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'modul_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_progress');
    }
};
```

`app/Models/ModulVideo.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulVideo extends Model
{
    protected $fillable = ['modul_id', 'judul', 'youtube_url'];

    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (preg_match('#(?:youtube\.com/watch\?v=|youtu\.be/)([A-Za-z0-9_-]{11})#', $this->youtube_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        return null;
    }
}
```

`app/Models/ModulProgress.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModulProgress extends Model
{
    protected $table = 'modul_progress';

    protected $fillable = ['user_id', 'modul_id', 'halaman_terjauh', 'terakhir_dibuka_at'];

    protected $casts = [
        'halaman_terjauh' => 'integer',
        'terakhir_dibuka_at' => 'datetime',
    ];

    public function modul(): BelongsTo
    {
        return $this->belongsTo(Modul::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Persen baca 0-100, dihitung saat ditampilkan (tahan pergantian PDF). */
    public function persen(): int
    {
        $total = $this->modul->jumlah_halaman;

        if ($total < 1) {
            return 0;
        }

        return min(100, (int) round($this->halaman_terjauh / $total * 100));
    }
}
```

- [ ] **Step 4: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter=ModulProgressModelTest; php artisan test --filter=ModulVideoModelTest`
Expected: PASS (6 tests)

- [ ] **Step 5: Commit**

```bash
git add database/migrations/2026_07_07_100003_create_modul_videos_table.php database/migrations/2026_07_07_100004_create_modul_progress_table.php app/Models/ModulVideo.php app/Models/ModulProgress.php tests/Unit/Models/ModulProgressModelTest.php tests/Unit/Models/ModulVideoModelTest.php
git commit -m "feat: tabel & model video modul dan progres baca"
```

---

### Task 3: Service PdfPageCounter + trait fixture PDF

**Files:**
- Create: `app/Services/PdfPageCounter.php`
- Create: `tests/Support/MakesPdfFixture.php`
- Modify: `composer.json` (via `composer require smalot/pdfparser`)
- Test: `tests/Unit/Services/PdfPageCounterTest.php`

**Interfaces:**
- Consumes: —
- Produces: `App\Services\PdfPageCounter::count(string $absolutePath): int` — mengembalikan jumlah halaman ≥ 1, melempar `\InvalidArgumentException` bila file bukan PDF valid/terenkripsi/0 halaman. Trait test `Tests\Support\MakesPdfFixture::pdfContent(int $pages = 2): string` — konten biner PDF valid dengan N halaman (dibuat via dompdf yang sudah terpasang).

- [ ] **Step 1: Pasang dependency**

Run: `composer require smalot/pdfparser`
Expected: sukses, `smalot/pdfparser` masuk ke `composer.json` require.

- [ ] **Step 2: Buat trait fixture (infrastruktur test, bukan implementasi produksi)**

`tests/Support/MakesPdfFixture.php`:

```php
<?php

namespace Tests\Support;

use Barryvdh\DomPDF\Facade\Pdf;

trait MakesPdfFixture
{
    /** Hasilkan konten biner PDF valid dengan jumlah halaman tertentu (via dompdf). */
    protected function pdfContent(int $pages = 2): string
    {
        $html = implode(
            '<div style="page-break-after: always;"></div>',
            array_fill(0, $pages, '<p>Halaman uji</p>')
        );

        return Pdf::loadHTML($html)->output();
    }
}
```

- [ ] **Step 3: Tulis test yang gagal**

`tests/Unit/Services/PdfPageCounterTest.php`:

```php
<?php

namespace Tests\Unit\Services;

use App\Services\PdfPageCounter;
use Tests\Support\MakesPdfFixture;
use Tests\TestCase;

class PdfPageCounterTest extends TestCase
{
    use MakesPdfFixture;

    private function tempFile(string $content): string
    {
        $path = tempnam(sys_get_temp_dir(), 'pdftest');
        file_put_contents($path, $content);

        return $path;
    }

    public function test_menghitung_jumlah_halaman_pdf(): void
    {
        $path = $this->tempFile($this->pdfContent(3));

        $this->assertSame(3, (new PdfPageCounter())->count($path));

        unlink($path);
    }

    public function test_melempar_exception_untuk_file_bukan_pdf(): void
    {
        $path = $this->tempFile('ini bukan pdf');

        $this->expectException(\InvalidArgumentException::class);

        try {
            (new PdfPageCounter())->count($path);
        } finally {
            unlink($path);
        }
    }
}
```

- [ ] **Step 4: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter=PdfPageCounterTest`
Expected: FAIL — `Class "App\Services\PdfPageCounter" not found`

- [ ] **Step 5: Buat service**

`app/Services/PdfPageCounter.php`:

```php
<?php

namespace App\Services;

use Smalot\PdfParser\Parser;

class PdfPageCounter
{
    /**
     * Hitung jumlah halaman PDF di path absolut.
     *
     * @throws \InvalidArgumentException bila file bukan PDF valid atau 0 halaman
     */
    public function count(string $absolutePath): int
    {
        try {
            $document = (new Parser())->parseFile($absolutePath);
            $pages = count($document->getPages());
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException('PDF tidak dapat dibaca: ' . $e->getMessage(), 0, $e);
        }

        if ($pages < 1) {
            throw new \InvalidArgumentException('PDF tidak memiliki halaman.');
        }

        return $pages;
    }
}
```

- [ ] **Step 6: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter=PdfPageCounterTest`
Expected: PASS (2 tests)

- [ ] **Step 7: Commit**

```bash
git add composer.json composer.lock app/Services/PdfPageCounter.php tests/Support/MakesPdfFixture.php tests/Unit/Services/PdfPageCounterTest.php
git commit -m "feat: service penghitung halaman PDF (smalot/pdfparser)"
```

---

### Task 4: Admin CRUD modul & kategori

**Files:**
- Create: `app/Http/Controllers/Admin/ModulController.php`
- Create: `resources/views/admin/modul/index.blade.php`
- Modify: `routes/web.php` (blok admin, setelah grup `rubrik-items`)
- Test: `tests/Feature/Admin/ModulManagementTest.php`

**Interfaces:**
- Consumes: `Modul`, `ModulKategori`, `ModulVideo` (Task 1–2), `PdfPageCounter::count()` (Task 3), `MakesPdfFixture::pdfContent()` (Task 3).
- Produces: route names `admin.modul.index|store|update|toggle|kategori.store|kategori.toggle`. Konvensi penyimpanan file: `modul/{nama-acak}.pdf` di disk `local` (hasil `$file->store('modul', 'local')`) — dipakai Task 6 untuk streaming.

- [ ] **Step 1: Tulis test yang gagal**

`tests/Feature/Admin/ModulManagementTest.php`:

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\MakesPdfFixture;
use Tests\TestCase;

class ModulManagementTest extends TestCase
{
    use RefreshDatabase;
    use MakesPdfFixture;

    private function createAdmin(): User
    {
        return User::factory()->admin()->create(['must_change_password' => false]);
    }

    private function fakePdfUpload(int $pages = 3): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('modul.pdf', $this->pdfContent($pages));
    }

    public function test_admin_can_view_modul_index(): void
    {
        $response = $this->actingAs($this->createAdmin())->get(route('admin.modul.index'));

        $response->assertStatus(200);
    }

    public function test_guru_cannot_access_modul_management(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('admin.modul.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_modul_with_page_count(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Kurikulum Merdeka',
            'deskripsi' => 'Deskripsi singkat.',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(3),
        ]);

        $response->assertRedirect(route('admin.modul.index'));
        $this->assertDatabaseHas('moduls', [
            'judul' => 'Modul Kurikulum Merdeka',
            'jumlah_halaman' => 3,
            'is_active' => true,
        ]);
        $this->assertCount(1, Storage::disk('local')->allFiles('modul'));
    }

    public function test_corrupt_pdf_is_rejected_without_leftover_file(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Rusak',
            'modul_kategori_id' => $kategori->id,
            'file' => UploadedFile::fake()->createWithContent('rusak.pdf', 'bukan konten pdf'),
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('moduls', 0);
        $this->assertEmpty(Storage::disk('local')->allFiles('modul'));
    }

    public function test_admin_can_create_modul_with_video_links(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Bervideo',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(2),
            'videos' => [
                ['judul' => 'Pengantar', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ],
        ]);

        $this->assertDatabaseHas('modul_videos', ['judul' => 'Pengantar']);
    }

    public function test_invalid_youtube_url_is_rejected(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Video Salah',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(2),
            'videos' => [
                ['judul' => 'Salah', 'youtube_url' => 'https://vimeo.com/12345'],
            ],
        ]);

        $response->assertSessionHasErrors('videos.0.youtube_url');
    }

    public function test_replacing_pdf_keeps_progress_and_updates_page_count(): void
    {
        Storage::fake('local');
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);
        $guru = User::factory()->guru()->create();
        ModulProgress::create(['user_id' => $guru->id, 'modul_id' => $modul->id, 'halaman_terjauh' => 5]);

        $this->actingAs($this->createAdmin())->put(route('admin.modul.update', $modul->id), [
            'judul' => $modul->judul,
            'modul_kategori_id' => $modul->modul_kategori_id,
            'file' => $this->fakePdfUpload(4),
        ]);

        $modul->refresh();
        $this->assertSame(4, $modul->jumlah_halaman);
        $this->assertDatabaseHas('modul_progress', ['modul_id' => $modul->id, 'halaman_terjauh' => 5]);
    }

    public function test_admin_can_toggle_modul(): void
    {
        $modul = Modul::factory()->create(['is_active' => true]);

        $this->actingAs($this->createAdmin())->patch(route('admin.modul.toggle', $modul->id));

        $this->assertFalse($modul->refresh()->is_active);
    }

    public function test_admin_can_create_and_toggle_kategori(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post(route('admin.modul.kategori.store'), ['nama' => 'Literasi Digital']);
        $this->assertDatabaseHas('modul_kategoris', ['nama' => 'Literasi Digital', 'is_active' => true]);

        $kategori = ModulKategori::where('nama', 'Literasi Digital')->first();
        $this->actingAs($admin)->patch(route('admin.modul.kategori.toggle', $kategori->id));
        $this->assertFalse($kategori->refresh()->is_active);
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter=ModulManagementTest`
Expected: FAIL — `Route [admin.modul.index] not defined`

- [ ] **Step 3: Tambah route**

Di `routes/web.php`, dalam blok admin (setelah grup `rubrik-items`, sebelum penutup blok admin), tambahkan:

```php
        // Modul Ajar Management
        Route::prefix('modul')->name('modul.')->group(function () {
            Route::get('/', [AdminModulController::class, 'index'])->name('index');
            Route::post('/', [AdminModulController::class, 'store'])->name('store')->middleware('throttle:30,1');
            Route::put('/{modul}', [AdminModulController::class, 'update'])->name('update')->middleware('throttle:30,1');
            Route::patch('/{modul}/toggle', [AdminModulController::class, 'toggle'])->name('toggle');
            Route::post('/kategori', [AdminModulController::class, 'storeKategori'])->name('kategori.store');
            Route::patch('/kategori/{modulKategori}/toggle', [AdminModulController::class, 'toggleKategori'])->name('kategori.toggle');
        });
```

Dan di bagian atas file tambahkan import:

```php
use App\Http\Controllers\Admin\ModulController as AdminModulController;
```

- [ ] **Step 4: Buat controller**

`app/Http/Controllers/Admin/ModulController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\ModulKategori;
use App\Services\PdfPageCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    private const PESAN_PDF_RUSAK = 'File PDF tidak dapat dibaca. Pastikan PDF valid dan tidak terkunci kata sandi.';

    public function __construct(private PdfPageCounter $pageCounter)
    {
    }

    public function index()
    {
        $moduls = Modul::with(['kategori', 'videos'])->latest()->get();
        $kategoris = ModulKategori::orderBy('nama')->get();

        return view('admin.modul.index', compact('moduls', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateModul($request, fileRequired: true);

        $path = $request->file('file')->store('modul', 'local');

        try {
            $jumlahHalaman = $this->pageCounter->count(Storage::disk('local')->path($path));
        } catch (\InvalidArgumentException) {
            Storage::disk('local')->delete($path);

            return back()->withInput()->withErrors(['file' => self::PESAN_PDF_RUSAK]);
        }

        $modul = Modul::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'modul_kategori_id' => $validated['modul_kategori_id'],
            'file_path' => $path,
            'jumlah_halaman' => $jumlahHalaman,
            'is_active' => true,
        ]);

        $this->syncVideos($modul, $validated['videos'] ?? []);

        return redirect()->route('admin.modul.index')->with('success', 'Modul berhasil ditambahkan.');
    }

    public function update(Request $request, Modul $modul)
    {
        $validated = $this->validateModul($request, fileRequired: false);

        $data = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'modul_kategori_id' => $validated['modul_kategori_id'],
        ];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('modul', 'local');

            try {
                $data['jumlah_halaman'] = $this->pageCounter->count(Storage::disk('local')->path($path));
            } catch (\InvalidArgumentException) {
                Storage::disk('local')->delete($path);

                return back()->withInput()->withErrors(['file' => self::PESAN_PDF_RUSAK]);
            }

            Storage::disk('local')->delete($modul->file_path);
            $data['file_path'] = $path;
        }

        $modul->update($data);
        $this->syncVideos($modul, $validated['videos'] ?? []);

        return redirect()->route('admin.modul.index')->with('success', 'Modul berhasil diperbarui.');
    }

    public function toggle(Modul $modul)
    {
        $modul->update(['is_active' => ! $modul->is_active]);

        return redirect()->route('admin.modul.index')->with('success', 'Status modul berhasil diubah.');
    }

    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:modul_kategoris,nama',
        ]);

        ModulKategori::create($validated + ['is_active' => true]);

        return redirect()->route('admin.modul.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function toggleKategori(ModulKategori $modulKategori)
    {
        $modulKategori->update(['is_active' => ! $modulKategori->is_active]);

        return redirect()->route('admin.modul.index')->with('success', 'Status kategori berhasil diubah.');
    }

    private function validateModul(Request $request, bool $fileRequired): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'modul_kategori_id' => 'required|exists:modul_kategoris,id',
            'file' => ($fileRequired ? 'required' : 'nullable') . '|file|mimes:pdf|max:20480',
            'videos' => 'nullable|array|max:5',
            'videos.*.judul' => 'required_with:videos.*.youtube_url|string|max:255',
            'videos.*.youtube_url' => [
                'required_with:videos.*.judul',
                'url',
                'regex:#(youtube\.com/watch\?v=|youtu\.be/)[A-Za-z0-9_-]{11}#',
            ],
        ]);
    }

    /** Ganti seluruh daftar video modul dengan input baru (abaikan baris kosong). */
    private function syncVideos(Modul $modul, array $videos): void
    {
        $modul->videos()->delete();

        foreach ($videos as $video) {
            if (! empty($video['judul']) && ! empty($video['youtube_url'])) {
                $modul->videos()->create([
                    'judul' => $video['judul'],
                    'youtube_url' => $video['youtube_url'],
                ]);
            }
        }
    }
}
```

- [ ] **Step 5: Buat view admin**

`resources/views/admin/modul/index.blade.php` (struktur & kelas mengikuti `admin/rubrik-items/index.blade.php`; form edit per modul memakai `<details>` agar tanpa JS):

```blade
@extends('layouts.modern')

@section('page-title', 'Modul Ajar')

@section('content')
<div class="max-w-5xl mx-auto pb-24 md:pb-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Kelola Modul Ajar</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Modul yang dinonaktifkan tidak tampil di daftar guru, tapi progres baca yang sudah ada tetap tersimpan.</p>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-sm text-red-700 dark:text-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Kelola Kategori --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <x-card-header title="Kategori Modul" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3">
            <form method="POST" action="{{ route('admin.modul.kategori.store') }}" class="flex items-center gap-3">
                @csrf
                <input type="text" name="nama" required maxlength="255" placeholder="Nama kategori baru"
                       class="flex-1 px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Tambah</button>
            </form>
            <div class="flex flex-wrap gap-2">
                @forelse ($kategoris as $kategori)
                    <form method="POST" action="{{ route('admin.modul.kategori.toggle', $kategori->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-600 text-xs {{ $kategori->is_active ? 'text-gray-700 dark:text-gray-300' : 'opacity-50 text-gray-500 dark:text-gray-400' }}">
                        @csrf @method('PATCH')
                        <span>{{ $kategori->nama }}</span>
                        <button type="submit" class="font-semibold text-primary-600 dark:text-primary-400 hover:underline">{{ $kategori->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada kategori. Tambahkan minimal satu sebelum mengunggah modul.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Tambah Modul --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <x-card-header title="Tambah Modul Baru" />
        <form method="POST" action="{{ route('admin.modul.store') }}" enctype="multipart/form-data" class="p-3 sm:p-4 md:p-6 space-y-4">
            @csrf
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" required maxlength="255" value="{{ old('judul') }}"
                       class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
            </div>
            <div>
                <label for="modul_kategori_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select id="modul_kategori_id" name="modul_kategori_id" required
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    <option value="">Pilih kategori</option>
                    @foreach ($kategoris->where('is_active', true) as $kategori)
                        <option value="{{ $kategori->id }}" @selected(old('modul_kategori_id') == $kategori->id)>{{ $kategori->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="2" maxlength="2000"
                          class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">{{ old('deskripsi') }}</textarea>
            </div>
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">File PDF <span class="text-red-500">*</span></label>
                <input type="file" id="file" name="file" accept="application/pdf" required class="w-full text-sm text-gray-700 dark:text-gray-300">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya PDF, maksimal 20 MB. Jumlah halaman dihitung otomatis.</p>
            </div>
            <fieldset class="space-y-2">
                <legend class="text-sm font-medium text-gray-700 dark:text-gray-300">Video YouTube (opsional, kosongkan jika tidak ada)</legend>
                @for ($i = 0; $i < 2; $i++)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <input type="text" name="videos[{{ $i }}][judul]" maxlength="255" placeholder="Judul video {{ $i + 1 }}" value="{{ old("videos.$i.judul") }}"
                               class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                        <input type="url" name="videos[{{ $i }}][youtube_url]" placeholder="https://www.youtube.com/watch?v=..." value="{{ old("videos.$i.youtube_url") }}"
                               class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    </div>
                @endfor
            </fieldset>
            <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700">Unggah Modul</button>
        </form>
    </div>

    {{-- Daftar Modul --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <x-card-header title="Daftar Modul" />
        <div class="p-3 sm:p-4 md:p-6 space-y-3">
            @forelse ($moduls as $modul)
                <details class="border border-gray-100 dark:border-gray-700/50 rounded-lg {{ ! $modul->is_active ? 'opacity-50' : '' }}">
                    <summary class="flex items-center justify-between gap-3 px-4 py-3 cursor-pointer select-none">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate">{{ $modul->judul }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $modul->kategori->nama }} • {{ $modul->jumlah_halaman }} halaman • {{ $modul->videos->count() }} video</p>
                        </div>
                        <x-status-badge :status="$modul->is_active ? 'aktif' : 'nonaktif'" />
                    </summary>
                    <div class="px-4 pb-4 border-t border-gray-100 dark:border-gray-700/50 pt-3 space-y-4">
                        <form method="POST" action="{{ route('admin.modul.update', $modul->id) }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" name="judul" required maxlength="255" value="{{ $modul->judul }}"
                                       class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                <select name="modul_kategori_id" required
                                        class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" @selected($modul->modul_kategori_id === $kategori->id)>{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea name="deskripsi" rows="2" maxlength="2000"
                                      class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">{{ $modul->deskripsi }}</textarea>
                            <div>
                                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Ganti PDF (kosongkan bila tidak diganti — progres guru tetap tersimpan)</label>
                                <input type="file" name="file" accept="application/pdf" class="w-full text-sm text-gray-700 dark:text-gray-300">
                            </div>
                            @foreach ($modul->videos as $i => $video)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" name="videos[{{ $i }}][judul]" maxlength="255" value="{{ $video->judul }}"
                                           class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                    <input type="url" name="videos[{{ $i }}][youtube_url]" value="{{ $video->youtube_url }}"
                                           class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                </div>
                            @endforeach
                            @php $next = $modul->videos->count(); @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="text" name="videos[{{ $next }}][judul]" maxlength="255" placeholder="Judul video baru"
                                       class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                                <input type="url" name="videos[{{ $next }}][youtube_url]" placeholder="https://www.youtube.com/watch?v=..."
                                       class="px-3 py-2 border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                            </div>
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Simpan Perubahan</button>
                        </form>
                        <form method="POST" action="{{ route('admin.modul.toggle', $modul->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                                {{ $modul->is_active ? 'Nonaktifkan Modul' : 'Aktifkan Modul' }}
                            </button>
                        </form>
                    </div>
                </details>
            @empty
                <x-empty-state title="Belum ada modul" description="Unggah modul pertama lewat formulir di atas." />
            @endforelse
        </div>
    </div>
</div>
@endsection
```

Catatan: sebelum memakai `x-empty-state`, baca `resources/views/components/empty-state.blade.php` untuk memastikan nama prop (`title`/`description`) — sesuaikan bila berbeda.

- [ ] **Step 6: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter=ModulManagementTest`
Expected: PASS (9 tests)

- [ ] **Step 7: Commit**

```bash
git add routes/web.php app/Http/Controllers/Admin/ModulController.php resources/views/admin/modul/index.blade.php tests/Feature/Admin/ModulManagementTest.php
git commit -m "feat: admin kelola modul ajar (unggah PDF, video YouTube, kategori)"
```

---

### Task 5: Guru — daftar modul dengan filter & progres

**Files:**
- Create: `app/Http/Controllers/Guru/ModulController.php`
- Create: `resources/views/guru/modul/index.blade.php`
- Modify: `routes/web.php` (blok guru)
- Test: `tests/Feature/Guru/ModulTest.php`

**Interfaces:**
- Consumes: `Modul` + scope `active()`, `ModulKategori::active()`, `ModulProgress::persen()`.
- Produces: route `guru.modul.index` (GET, query param opsional `kategori` = id kategori). Controller `Guru\ModulController` — Task 6 menambahkan method `show/file/saveProgress` ke class yang sama.

- [ ] **Step 1: Tulis test yang gagal**

`tests/Feature/Guru/ModulTest.php`:

```php
<?php

namespace Tests\Feature\Guru;

use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    public function test_guru_can_view_modul_list_with_progress(): void
    {
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['judul' => 'Modul Literasi', 'jumlah_halaman' => 10]);
        ModulProgress::create(['user_id' => $guru->id, 'modul_id' => $modul->id, 'halaman_terjauh' => 6]);

        $response = $this->actingAs($guru)->get(route('guru.modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Modul Literasi');
        $response->assertSee('60%');
    }

    public function test_inactive_modul_hidden_from_list(): void
    {
        Modul::factory()->create(['judul' => 'Modul Nonaktif', 'is_active' => false]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index'));

        $response->assertDontSee('Modul Nonaktif');
    }

    public function test_list_can_be_filtered_by_kategori(): void
    {
        $kategoriA = ModulKategori::factory()->create(['nama' => 'Pedagogik']);
        $kategoriB = ModulKategori::factory()->create(['nama' => 'Numerasi']);
        Modul::factory()->create(['judul' => 'Modul Pedagogik Satu', 'modul_kategori_id' => $kategoriA->id]);
        Modul::factory()->create(['judul' => 'Modul Numerasi Satu', 'modul_kategori_id' => $kategoriB->id]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index', ['kategori' => $kategoriA->id]));

        $response->assertSee('Modul Pedagogik Satu');
        $response->assertDontSee('Modul Numerasi Satu');
    }

    public function test_empty_state_shown_when_no_modul(): void
    {
        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada modul');
    }

    public function test_admin_cannot_access_guru_modul_list(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $response = $this->actingAs($admin)->get(route('guru.modul.index'));

        $response->assertStatus(403);
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter='Guru\\ModulTest'`
Expected: FAIL — `Route [guru.modul.index] not defined`

- [ ] **Step 3: Tambah route**

Di `routes/web.php`, dalam blok guru (setelah grup `supervisi`, masih di dalam prefix `guru`), tambahkan:

```php
        // Modul Ajar Routes
        Route::prefix('modul')->name('modul.')->group(function () {
            Route::get('/', [GuruModulController::class, 'index'])->name('index');
        });
```

Import di atas file:

```php
use App\Http\Controllers\Guru\ModulController as GuruModulController;
```

- [ ] **Step 4: Buat controller (baru method index)**

`app/Http/Controllers/Guru/ModulController.php`:

```php
<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use Illuminate\Http\Request;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = ModulKategori::active()->orderBy('nama')->get();

        $moduls = Modul::active()
            ->with('kategori')
            ->when($request->filled('kategori'), fn ($q) => $q->where('modul_kategori_id', $request->integer('kategori')))
            ->orderBy('judul')
            ->get();

        $progressByModul = ModulProgress::where('user_id', auth()->id())
            ->whereIn('modul_id', $moduls->pluck('id'))
            ->get()
            ->keyBy('modul_id');

        return view('guru.modul.index', compact('moduls', 'kategoris', 'progressByModul'));
    }
}
```

- [ ] **Step 5: Buat view daftar**

`resources/views/guru/modul/index.blade.php`:

```blade
@extends('layouts.modern')

@section('page-title', 'Modul Ajar')

@section('content')
<div class="max-w-5xl mx-auto pb-24 md:pb-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Modul Ajar</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Pelajari modul secara mandiri. Progres baca Anda tersimpan otomatis.</p>

    <form method="GET" action="{{ route('guru.modul.index') }}" class="flex items-center gap-2 mb-6">
        <label for="kategori" class="text-sm text-gray-700 dark:text-gray-300">Kategori:</label>
        <select id="kategori" name="kategori"
                class="px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
            <option value="">Semua</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected(request('kategori') == $kategori->id)>{{ $kategori->nama }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Terapkan</button>
    </form>

    @if ($moduls->isEmpty())
        <x-empty-state title="Belum ada modul" description="Modul ajar yang diunggah admin akan tampil di sini." />
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($moduls as $modul)
                @php
                    $progress = $progressByModul->get($modul->id);
                    $persen = $progress ? $progress->persen() : 0;
                @endphp
                <a href="{{ route('guru.modul.show', $modul->id) }}"
                   class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition-shadow cursor-pointer">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $modul->judul }}</h3>
                        <span class="shrink-0 px-2 py-0.5 rounded-full text-xs bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400">{{ $modul->kategori->nama }}</span>
                    </div>
                    @if ($modul->deskripsi)
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $modul->deskripsi }}</p>
                    @endif
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                        <span>{{ $modul->jumlah_halaman }} halaman</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $persen }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden" role="progressbar" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100" aria-label="Progres baca {{ $modul->judul }}">
                        <div class="h-full rounded-full bg-primary-600 dark:bg-primary-500" style="width: {{ $persen }}%"></div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
```

Catatan: route `guru.modul.show` baru didefinisikan di Task 6 — supaya view tidak error saat test Task 5, **tambahkan route show sekaligus di Step 3 Task 6**, ATAU untuk sementara di Step 5 ini pakai `href="#"` lalu ganti di Task 6. Pilihan paling bersih: kerjakan Step 3 Task 6 (route + method `show` stub) bersamaan — tapi TDD-guard menuntut test dulu. **Solusi yang dipakai: di Task 5 gunakan `href="#"`, Task 6 menggantinya menjadi `route('guru.modul.show', $modul->id)`.**

- [ ] **Step 6: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter='Guru\\ModulTest'`
Expected: PASS (5 tests)

- [ ] **Step 7: Commit**

```bash
git add routes/web.php app/Http/Controllers/Guru/ModulController.php resources/views/guru/modul/index.blade.php tests/Feature/Guru/ModulTest.php
git commit -m "feat: guru lihat daftar modul ajar dengan filter kategori dan progres"
```

---

### Task 6: Guru — halaman baca, streaming PDF, endpoint progres

**Files:**
- Modify: `app/Http/Controllers/Guru/ModulController.php` (tambah `show`, `file`, `saveProgress`)
- Create: `resources/views/guru/modul/baca.blade.php`
- Modify: `resources/views/guru/modul/index.blade.php` (ganti `href="#"` → `route('guru.modul.show', ...)`)
- Modify: `routes/web.php` (route show + progress di blok guru; route file di grup preview tanpa `prevent.back`)
- Test: `tests/Feature/Guru/ModulTest.php` (tambah test)

**Interfaces:**
- Consumes: `Modul`, `ModulProgress`, disk `local` path `modul/*.pdf` (Task 4).
- Produces: route `guru.modul.show` (GET `/guru/modul/{modul}`), `guru.modul.file` (GET `/guru/modul/{modul}/file`, streaming PDF), `guru.modul.progress` (POST `/guru/modul/{modul}/progress`, body JSON `{halaman: int}` → respons `{success: true, halaman_terjauh: int}`). View `guru/modul/baca.blade.php` memuat elemen `#modul-reader` dengan data attributes `data-pdf-url`, `data-progress-url`, `data-halaman-terjauh`, `data-jumlah-halaman` — kontrak untuk JS Task 7.

- [ ] **Step 1: Tambah test yang gagal ke `tests/Feature/Guru/ModulTest.php`**

```php
    public function test_opening_baca_page_creates_progress_row(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create();

        $response = $this->actingAs($guru)->get(route('guru.modul.show', $modul->id));

        $response->assertStatus(200);
        $this->assertDatabaseHas('modul_progress', [
            'user_id' => $guru->id,
            'modul_id' => $modul->id,
            'halaman_terjauh' => 1,
        ]);
    }

    public function test_baca_page_contains_reader_contract_attributes(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 7]);
        \Illuminate\Support\Facades\Storage::disk('local')->put($modul->file_path, 'dummy');

        $response = $this->actingAs($guru)->get(route('guru.modul.show', $modul->id));

        $response->assertSee('id="modul-reader"', false);
        $response->assertSee('data-jumlah-halaman="7"', false);
        $response->assertSee(route('guru.modul.file', $modul->id));
    }

    public function test_missing_pdf_file_shows_friendly_message(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(); // file tidak pernah ditulis ke storage

        $response = $this->actingAs($guru)->get(route('guru.modul.show', $modul->id));

        $response->assertStatus(200);
        $response->assertSee('File modul tidak ditemukan');
    }

    public function test_inactive_modul_returns_404_on_baca(): void
    {
        $modul = Modul::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.show', $modul->id));

        $response->assertStatus(404);
    }

    public function test_progress_only_increases(): void
    {
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);

        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 5]);
        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 3]);

        $this->assertDatabaseHas('modul_progress', [
            'user_id' => $guru->id,
            'modul_id' => $modul->id,
            'halaman_terjauh' => 5,
        ]);
    }

    public function test_progress_rejects_page_out_of_range(): void
    {
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);

        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 0])->assertStatus(422);
        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 11])->assertStatus(422);
    }

    public function test_progress_is_per_user(): void
    {
        $guruA = $this->createGuru();
        $guruB = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);

        $this->actingAs($guruA)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 8]);
        $this->actingAs($guruB)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 2]);

        $this->assertDatabaseHas('modul_progress', ['user_id' => $guruA->id, 'halaman_terjauh' => 8]);
        $this->assertDatabaseHas('modul_progress', ['user_id' => $guruB->id, 'halaman_terjauh' => 2]);
    }

    public function test_file_endpoint_streams_pdf(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create();
        \Illuminate\Support\Facades\Storage::disk('local')->put($modul->file_path, '%PDF-1.4 dummy');

        $response = $this->actingAs($guru)->get(route('guru.modul.file', $modul->id));

        $response->assertStatus(200);
    }

    public function test_file_endpoint_404_when_file_missing(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create();

        $response = $this->actingAs($guru)->get(route('guru.modul.file', $modul->id));

        $response->assertStatus(404);
    }
```

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter='Guru\\ModulTest'`
Expected: FAIL — `Route [guru.modul.show] not defined` (test lama tetap PASS)

- [ ] **Step 3: Tambah route**

Di blok guru `routes/web.php`, lengkapi grup modul menjadi:

```php
        // Modul Ajar Routes
        Route::prefix('modul')->name('modul.')->group(function () {
            Route::get('/', [GuruModulController::class, 'index'])->name('index');
            Route::get('/{modul}', [GuruModulController::class, 'show'])->name('show');
            Route::post('/{modul}/progress', [GuruModulController::class, 'saveProgress'])->name('progress')->middleware('throttle:60,1');
        });
```

Di grup **File Download & Preview** (blok `auth + must.change.password` TANPA `prevent.back`, di dalam prefix `guru` yang sudah ada), tambahkan:

```php
        Route::get('/modul/{modul}/file', [GuruModulController::class, 'file'])->name('modul.file');
```

- [ ] **Step 4: Implementasi method controller**

Tambahkan ke `app/Http/Controllers/Guru/ModulController.php` (import `Illuminate\Support\Facades\Storage`):

```php
    public function show(Modul $modul)
    {
        abort_unless($modul->is_active, 404);

        $modul->load('videos');

        $progress = ModulProgress::firstOrCreate(
            ['user_id' => auth()->id(), 'modul_id' => $modul->id],
            ['halaman_terjauh' => 1]
        );
        $progress->update(['terakhir_dibuka_at' => now()]);

        $fileMissing = ! Storage::disk('local')->exists($modul->file_path);

        return view('guru.modul.baca', compact('modul', 'progress', 'fileMissing'));
    }

    public function file(Modul $modul)
    {
        abort_unless($modul->is_active, 404);

        $path = Storage::disk('local')->path($modul->file_path);

        abort_unless(file_exists($path), 404, 'File modul tidak ditemukan');

        return response()->file($path);
    }

    public function saveProgress(Request $request, Modul $modul)
    {
        abort_unless($modul->is_active, 404);

        $validated = $request->validate([
            'halaman' => 'required|integer|min:1|max:' . $modul->jumlah_halaman,
        ]);

        $progress = ModulProgress::firstOrCreate(
            ['user_id' => auth()->id(), 'modul_id' => $modul->id],
            ['halaman_terjauh' => 1]
        );

        if ($validated['halaman'] > $progress->halaman_terjauh) {
            $progress->halaman_terjauh = $validated['halaman'];
        }
        $progress->terakhir_dibuka_at = now();
        $progress->save();

        return response()->json(['success' => true, 'halaman_terjauh' => $progress->halaman_terjauh]);
    }
```

- [ ] **Step 5: Buat view baca (tanpa JS dulu — JS di Task 7)**

`resources/views/guru/modul/baca.blade.php`:

```blade
@extends('layouts.modern')

@section('page-title', $modul->judul)

@section('content')
<div class="max-w-4xl mx-auto pb-24 md:pb-8">
    <div class="flex items-center justify-between gap-3 mb-4">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $modul->judul }}</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $modul->kategori->nama }} • {{ $modul->jumlah_halaman }} halaman</p>
        </div>
        <a href="{{ route('guru.modul.index') }}" wire:navigate class="shrink-0 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:underline">&larr; Kembali</a>
    </div>

    @if ($fileMissing)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">File modul tidak ditemukan</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Hubungi admin untuk mengunggah ulang file modul ini.</p>
        </div>
    @else
        <div id="modul-reader"
             data-pdf-url="{{ route('guru.modul.file', $modul->id) }}"
             data-progress-url="{{ route('guru.modul.progress', $modul->id) }}"
             data-halaman-terjauh="{{ $progress->halaman_terjauh }}"
             data-jumlah-halaman="{{ $modul->jumlah_halaman }}"
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex items-center justify-between gap-2 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <button id="pdf-prev" type="button" disabled aria-label="Halaman sebelumnya"
                        class="min-w-11 min-h-11 px-3 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">&larr;</button>
                <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input id="pdf-page-input" type="number" min="1" max="{{ $modul->jumlah_halaman }}" value="{{ $progress->halaman_terjauh }}" aria-label="Loncat ke halaman"
                           class="w-16 px-2 py-1 text-center border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-lg text-sm">
                    <span id="page-info" aria-live="polite">dari {{ $modul->jumlah_halaman }}</span>
                </div>
                <button id="pdf-next" type="button" disabled aria-label="Halaman berikutnya"
                        class="min-w-11 min-h-11 px-3 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer">&rarr;</button>
            </div>
            <div class="bg-gray-100 dark:bg-gray-900 p-2 sm:p-4">
                <div id="pdf-skeleton" class="animate-pulse bg-gray-200 dark:bg-gray-700 rounded w-full" style="aspect-ratio: 1 / 1.414;"></div>
                <canvas id="pdf-canvas" class="w-full h-auto mx-auto hidden rounded shadow"></canvas>
            </div>
        </div>
    @endif

    @if ($modul->videos->isNotEmpty())
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <x-card-header title="Video Pembelajaran" />
            <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ($modul->videos as $video)
                    @if ($video->youtube_embed_url)
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-200 mb-2">{{ $video->judul }}</p>
                            <div class="rounded-lg overflow-hidden" style="aspect-ratio: 16 / 9;">
                                <iframe src="{{ $video->youtube_embed_url }}" title="{{ $video->judul }}" class="w-full h-full" frameborder="0" allowfullscreen loading="lazy"></iframe>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
```

- [ ] **Step 6: Ganti tautan kartu di `resources/views/guru/modul/index.blade.php`**

Ganti `href="#"` menjadi:

```blade
<a href="{{ route('guru.modul.show', $modul->id) }}"
```

(Tautan ini **tanpa** `wire:navigate` — halaman baca memuat bundel JS sendiri dan lebih aman dengan full page load.)

- [ ] **Step 7: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter='Guru\\ModulTest'`
Expected: PASS (14 tests)

- [ ] **Step 8: Commit**

```bash
git add routes/web.php app/Http/Controllers/Guru/ModulController.php resources/views/guru/modul/baca.blade.php resources/views/guru/modul/index.blade.php tests/Feature/Guru/ModulTest.php
git commit -m "feat: guru baca modul - streaming PDF privat & endpoint progres halaman"
```

---

### Task 7: Penampil PDF.js + pengiriman progres (frontend)

**Files:**
- Create: `resources/js/modul-reader.js`
- Modify: `vite.config.js` (tambah input)
- Modify: `resources/views/guru/modul/baca.blade.php` (tambah `@vite`)
- Modify: `package.json` (via `npm install pdfjs-dist`)

**Interfaces:**
- Consumes: kontrak DOM dari Task 6 — `#modul-reader[data-pdf-url][data-progress-url][data-halaman-terjauh][data-jumlah-halaman]`, `#pdf-canvas`, `#pdf-skeleton`, `#pdf-prev`, `#pdf-next`, `#pdf-page-input`, `#page-info`; meta `csrf-token` dari layout.
- Produces: bundel `resources/js/modul-reader.js` yang ter-build Vite.

- [ ] **Step 1: Pasang dependency**

Run: `npm install pdfjs-dist`
Expected: sukses, masuk `package.json` dependencies.

- [ ] **Step 2: Tulis JS reader**

`resources/js/modul-reader.js`:

```js
import * as pdfjsLib from 'pdfjs-dist';
import workerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

pdfjsLib.GlobalWorkerOptions.workerSrc = workerUrl;

function initModulReader() {
    const el = document.getElementById('modul-reader');
    if (!el || el.dataset.readerInitialized) return;
    el.dataset.readerInitialized = 'true';

    const pdfUrl = el.dataset.pdfUrl;
    const progressUrl = el.dataset.progressUrl;
    const jumlahHalaman = parseInt(el.dataset.jumlahHalaman, 10);
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const canvas = document.getElementById('pdf-canvas');
    const skeleton = document.getElementById('pdf-skeleton');
    const btnPrev = document.getElementById('pdf-prev');
    const btnNext = document.getElementById('pdf-next');
    const pageInput = document.getElementById('pdf-page-input');
    const pageInfo = document.getElementById('page-info');

    let pdfDoc = null;
    let pageNum = Math.min(jumlahHalaman, Math.max(1, parseInt(el.dataset.halamanTerjauh, 10) || 1));
    let rendering = false;
    let pendingPage = null;
    let sendTimer = null;
    let gagalTerkirim = null; // halaman yang gagal dikirim, dicoba ulang diam-diam

    pdfjsLib.getDocument(pdfUrl).promise.then((doc) => {
        pdfDoc = doc;
        skeleton.classList.add('hidden');
        canvas.classList.remove('hidden');
        renderPage(pageNum);
    }).catch(() => {
        skeleton.classList.remove('animate-pulse');
        skeleton.innerHTML = '<p class="p-6 text-sm text-center text-gray-600 dark:text-gray-400">PDF gagal dimuat. Periksa koneksi lalu muat ulang halaman.</p>';
        skeleton.style.aspectRatio = 'auto';
    });

    function renderPage(num) {
        if (rendering) { pendingPage = num; return; }
        rendering = true;
        setNavDisabled(true);

        pdfDoc.getPage(num).then((page) => {
            const containerWidth = canvas.parentElement.clientWidth;
            const viewport = page.getViewport({ scale: 1 });
            const scale = containerWidth / viewport.width;
            const scaled = page.getViewport({ scale: scale * (window.devicePixelRatio || 1) });

            canvas.width = scaled.width;
            canvas.height = scaled.height;

            return page.render({ canvasContext: canvas.getContext('2d'), viewport: scaled }).promise;
        }).then(() => {
            rendering = false;
            pageNum = num;
            updateControls();
            queueProgress(num);
            if (pendingPage !== null) { const p = pendingPage; pendingPage = null; renderPage(p); }
        }).catch(() => {
            rendering = false;
            setNavDisabled(false);
        });
    }

    function updateControls() {
        pageInput.value = pageNum;
        const persen = Math.min(100, Math.round(pageNum / jumlahHalaman * 100));
        pageInfo.textContent = `dari ${jumlahHalaman} • ${persen}%`;
        btnPrev.disabled = pageNum <= 1;
        btnNext.disabled = pageNum >= jumlahHalaman;
    }

    function setNavDisabled(disabled) {
        btnPrev.disabled = disabled || pageNum <= 1;
        btnNext.disabled = disabled || pageNum >= jumlahHalaman;
    }

    function goTo(num) {
        const target = Math.min(jumlahHalaman, Math.max(1, num));
        if (pdfDoc && target !== pageNum) renderPage(target);
    }

    // Debounce 2 detik supaya server tidak dibanjiri saat guru membalik cepat.
    function queueProgress(page) {
        clearTimeout(sendTimer);
        sendTimer = setTimeout(() => sendProgress(page), 2000);
    }

    function sendProgress(page) {
        const halaman = Math.max(page, gagalTerkirim ?? 0);
        fetch(progressUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ halaman }),
        }).then((res) => {
            gagalTerkirim = res.ok ? null : halaman;
        }).catch(() => {
            gagalTerkirim = halaman; // dicoba ulang pada perpindahan halaman berikutnya
        });
    }

    btnPrev.addEventListener('click', () => goTo(pageNum - 1));
    btnNext.addEventListener('click', () => goTo(pageNum + 1));
    pageInput.addEventListener('change', () => goTo(parseInt(pageInput.value, 10) || 1));
    document.addEventListener('keydown', (e) => {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (e.key === 'ArrowLeft') goTo(pageNum - 1);
        if (e.key === 'ArrowRight') goTo(pageNum + 1);
    });
}

initModulReader();
// Halaman bisa dimasuki lewat navigasi Livewire (wire:navigate) dari halaman lain.
document.addEventListener('livewire:navigated', initModulReader);
```

- [ ] **Step 3: Daftarkan di Vite**

`vite.config.js` — ubah array input menjadi:

```js
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/modul-reader.js',
            ],
```

- [ ] **Step 4: Muat di view baca**

Di `resources/views/guru/modul/baca.blade.php`, tepat sebelum `@endsection`, tambahkan:

```blade
@if (! $fileMissing)
    @vite('resources/js/modul-reader.js')
@endif
```

- [ ] **Step 5: Verifikasi build & test tetap hijau**

Run: `npm run build`
Expected: build sukses, bundle `modul-reader` muncul di output.

Run: `php artisan test --filter='Guru\\ModulTest'`
Expected: PASS (14 tests, tidak ada yang pecah)

- [ ] **Step 6: Commit**

```bash
git add package.json package-lock.json vite.config.js resources/js/modul-reader.js resources/views/guru/modul/baca.blade.php
git commit -m "feat: penampil PDF.js dengan pelacakan halaman terjauh (debounce + retry)"
```

---

### Task 8: Kepala sekolah — rekap progres

**Files:**
- Create: `app/Http/Controllers/KepalaSekolah/ModulProgressController.php`
- Create: `resources/views/kepala/modul-progress/index.blade.php`
- Modify: `routes/web.php` (blok kepala sekolah)
- Test: `tests/Feature/KepalaSekolah/ModulProgressRekapTest.php`

**Interfaces:**
- Consumes: `Modul::active()`, `ModulProgress::persen()`, `User` (role `guru`, `is_active`).
- Produces: route `kepala.modul-progress.index` (GET, query param `mode` = `modul` (default) | `guru`, plus `modul_id` / `guru_id`).

- [ ] **Step 1: Tulis test yang gagal**

`tests/Feature/KepalaSekolah/ModulProgressRekapTest.php`:

```php
<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Modul;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulProgressRekapTest extends TestCase
{
    use RefreshDatabase;

    private function createKepala(): User
    {
        return User::factory()->kepalaSekolah()->create(['must_change_password' => false]);
    }

    public function test_kepala_can_view_rekap(): void
    {
        Modul::factory()->create();

        $response = $this->actingAs($this->createKepala())->get(route('kepala.modul-progress.index'));

        $response->assertStatus(200);
    }

    public function test_guru_cannot_access_rekap(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('kepala.modul-progress.index'));

        $response->assertStatus(403);
    }

    public function test_guru_without_progress_shown_with_zero_percent(): void
    {
        $modul = Modul::factory()->create();
        $guruRajin = User::factory()->guru()->create(['name' => 'Guru Rajin']);
        $guruBelum = User::factory()->guru()->create(['name' => 'Guru Belum Baca']);
        ModulProgress::create(['user_id' => $guruRajin->id, 'modul_id' => $modul->id, 'halaman_terjauh' => 10]);

        $response = $this->actingAs($this->createKepala())
            ->get(route('kepala.modul-progress.index', ['mode' => 'modul', 'modul_id' => $modul->id]));

        $response->assertSee('Guru Rajin');
        $response->assertSee('100%');
        $response->assertSee('Guru Belum Baca');
        $response->assertSee('0%');
    }

    public function test_mode_guru_shows_all_moduls_for_selected_guru(): void
    {
        $guru = User::factory()->guru()->create(['name' => 'Guru Dipantau']);
        $modulA = Modul::factory()->create(['judul' => 'Modul Alpha', 'jumlah_halaman' => 10]);
        $modulB = Modul::factory()->create(['judul' => 'Modul Beta', 'jumlah_halaman' => 10]);
        ModulProgress::create(['user_id' => $guru->id, 'modul_id' => $modulA->id, 'halaman_terjauh' => 5]);

        $response = $this->actingAs($this->createKepala())
            ->get(route('kepala.modul-progress.index', ['mode' => 'guru', 'guru_id' => $guru->id]));

        $response->assertSee('Modul Alpha');
        $response->assertSee('50%');
        $response->assertSee('Modul Beta');
        $response->assertSee('0%');
    }

    public function test_empty_state_when_no_modul(): void
    {
        $response = $this->actingAs($this->createKepala())->get(route('kepala.modul-progress.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada modul');
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter=ModulProgressRekapTest`
Expected: FAIL — `Route [kepala.modul-progress.index] not defined`

- [ ] **Step 3: Tambah route**

Di blok kepala sekolah `routes/web.php` (setelah grup `evaluasi`), tambahkan:

```php
        // Rekap Progres Modul Ajar
        Route::get('/modul-progress', [ModulProgressController::class, 'index'])->name('modul-progress.index');
```

Import:

```php
use App\Http\Controllers\KepalaSekolah\ModulProgressController;
```

- [ ] **Step 4: Buat controller**

`app/Http/Controllers/KepalaSekolah/ModulProgressController.php`:

```php
<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Http\Request;

class ModulProgressController extends Controller
{
    public function index(Request $request)
    {
        $moduls = Modul::active()->with('kategori')->orderBy('judul')->get();
        $gurus = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();

        $mode = $request->input('mode') === 'guru' ? 'guru' : 'modul';
        $selectedModul = null;
        $selectedGuru = null;
        $rows = collect();

        if ($mode === 'modul' && $moduls->isNotEmpty()) {
            $selectedModul = $moduls->firstWhere('id', $request->integer('modul_id')) ?? $moduls->first();
            $progressByUser = ModulProgress::with('modul')
                ->where('modul_id', $selectedModul->id)
                ->get()
                ->keyBy('user_id');
            // Guru tanpa progres tetap tampil 0% — ketiadaan data adalah informasi.
            $rows = $gurus->map(fn (User $guru) => [
                'label' => $guru->name,
                'persen' => $progressByUser->get($guru->id)?->persen() ?? 0,
                'terakhir' => $progressByUser->get($guru->id)?->terakhir_dibuka_at,
            ]);
        }

        if ($mode === 'guru' && $gurus->isNotEmpty()) {
            $selectedGuru = $gurus->firstWhere('id', $request->integer('guru_id')) ?? $gurus->first();
            $progressByModul = ModulProgress::with('modul')
                ->where('user_id', $selectedGuru->id)
                ->get()
                ->keyBy('modul_id');
            $rows = $moduls->map(fn (Modul $modul) => [
                'label' => $modul->judul,
                'persen' => $progressByModul->get($modul->id)?->persen() ?? 0,
                'terakhir' => $progressByModul->get($modul->id)?->terakhir_dibuka_at,
            ]);
        }

        return view('kepala.modul-progress.index', compact('moduls', 'gurus', 'mode', 'selectedModul', 'selectedGuru', 'rows'));
    }
}
```

- [ ] **Step 5: Buat view rekap**

`resources/views/kepala/modul-progress/index.blade.php`:

```blade
@extends('layouts.modern')

@section('page-title', 'Progres Modul')

@section('content')
<div class="max-w-5xl mx-auto pb-24 md:pb-8">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Progres Baca Modul Ajar</h2>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Pantau sejauh mana setiap guru mempelajari modul. Penilaian formal menyusul di fase berikutnya.</p>

    @if ($moduls->isEmpty())
        <x-empty-state title="Belum ada modul" description="Rekap muncul setelah admin mengunggah modul ajar." />
    @else
        <div class="flex items-center gap-2 mb-4" role="tablist" aria-label="Sudut pandang rekap">
            <a href="{{ route('kepala.modul-progress.index', ['mode' => 'modul']) }}" role="tab" aria-selected="{{ $mode === 'modul' ? 'true' : 'false' }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold {{ $mode === 'modul' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Per Modul</a>
            <a href="{{ route('kepala.modul-progress.index', ['mode' => 'guru']) }}" role="tab" aria-selected="{{ $mode === 'guru' ? 'true' : 'false' }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold {{ $mode === 'guru' ? 'bg-primary-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">Per Guru</a>
        </div>

        <form method="GET" action="{{ route('kepala.modul-progress.index') }}" class="flex items-center gap-2 mb-4">
            <input type="hidden" name="mode" value="{{ $mode }}">
            @if ($mode === 'modul')
                <label for="modul_id" class="text-sm text-gray-700 dark:text-gray-300">Modul:</label>
                <select id="modul_id" name="modul_id" class="flex-1 max-w-md px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    @foreach ($moduls as $modul)
                        <option value="{{ $modul->id }}" @selected($selectedModul && $selectedModul->id === $modul->id)>{{ $modul->judul }}</option>
                    @endforeach
                </select>
            @else
                <label for="guru_id" class="text-sm text-gray-700 dark:text-gray-300">Guru:</label>
                <select id="guru_id" name="guru_id" class="flex-1 max-w-md px-3 py-1.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100">
                    @foreach ($gurus as $guru)
                        <option value="{{ $guru->id }}" @selected($selectedGuru && $selectedGuru->id === $guru->id)>{{ $guru->name }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-primary-600 hover:bg-primary-700">Tampilkan</button>
        </form>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <x-card-header :title="$mode === 'modul' ? ($selectedModul->judul ?? '') : ($selectedGuru->name ?? '')" />
            <div class="p-3 sm:p-4 md:p-6 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-2 pr-2">{{ $mode === 'modul' ? 'Guru' : 'Modul' }}</th>
                            <th class="pb-2 pr-2 w-1/3">Progres Baca</th>
                            <th class="pb-2">Terakhir Dibuka</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr class="border-b border-gray-100 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="py-2.5 pr-2 text-gray-900 dark:text-gray-200">{{ $row['label'] }}</td>
                                <td class="py-2.5 pr-2">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">
                                            <div class="h-full rounded-full bg-primary-600 dark:bg-primary-500" style="width: {{ $row['persen'] }}%"></div>
                                        </div>
                                        <span class="w-12 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 tabular-nums">{{ $row['persen'] }}%</span>
                                    </div>
                                </td>
                                <td class="py-2.5 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $row['terakhir'] ? $row['terakhir']->translatedFormat('d F Y H:i') : 'Belum dibuka' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
```

- [ ] **Step 6: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter=ModulProgressRekapTest`
Expected: PASS (5 tests)

- [ ] **Step 7: Commit**

```bash
git add routes/web.php app/Http/Controllers/KepalaSekolah/ModulProgressController.php resources/views/kepala/modul-progress/index.blade.php tests/Feature/KepalaSekolah/ModulProgressRekapTest.php
git commit -m "feat: rekap progres baca modul untuk kepala sekolah (per modul / per guru)"
```

---

### Task 9: Navigasi sidebar untuk tiga peran

**Files:**
- Modify: `resources/views/layouts/modern.blade.php`
- Test: `tests/Feature/ModulNavigationTest.php`

**Interfaces:**
- Consumes: route `admin.modul.index`, `guru.modul.index`, `kepala.modul-progress.index` (Task 4, 5, 8).
- Produces: tautan sidebar per peran.

- [ ] **Step 1: Tulis test yang gagal**

`tests/Feature/ModulNavigationTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sidebar_has_modul_link(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertSee(route('admin.modul.index'));
    }

    public function test_guru_sidebar_has_modul_link(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee(route('guru.modul.index'));
    }

    public function test_kepala_sidebar_has_progres_modul_link(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false]);

        $response = $this->actingAs($kepala)->get(route('kepala.dashboard'));

        $response->assertSee(route('kepala.modul-progress.index'));
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter=ModulNavigationTest`
Expected: FAIL — link belum ada di sidebar

- [ ] **Step 3: Tambah tautan sidebar**

Di `resources/views/layouts/modern.blade.php`. Ada tiga blok sidebar per peran (cari `admin.rubrik-items.index` ± baris 507, blok guru `guru.home` ± baris 517, blok kepala `kepala.dashboard` ± baris 536; nomor bergeser setelah edit — gunakan pencarian teks).

**(a) Blok admin — setelah tautan Rubrik Penilaian:**

```blade
                <a href="{{ route('admin.modul.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.modul.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="flex-1">Modul Ajar</span>
                    @if(request()->routeIs('admin.modul.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    @endif
                </a>
```

**(b) Blok guru — setelah tautan yang ada di grup guru** (ikon buku sama, ganti route):

```blade
                <a href="{{ route('guru.modul.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('guru.modul.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="flex-1">Modul Ajar</span>
                    @if(request()->routeIs('guru.modul.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    @endif
                </a>
```

**(c) Blok kepala sekolah — setelah tautan yang ada di grup kepala:**

```blade
                <a href="{{ route('kepala.modul-progress.index') }}" wire:navigate class="group flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg text-sm font-medium transition-all duration-200 {{ request()->routeIs('kepala.modul-progress.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="flex-1">Progres Modul</span>
                    @if(request()->routeIs('kepala.modul-progress.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                    @endif
                </a>
```

PENTING: file ini pernah kena insiden markup tak seimbang (memory `div-balance-dan-insiden-db`) — setelah edit, jalankan juga `php artisan test --filter=LayoutDivBalanceTest`.

- [ ] **Step 4: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter=ModulNavigationTest; php artisan test --filter=LayoutDivBalanceTest`
Expected: keduanya PASS

- [ ] **Step 5: Commit**

```bash
git add resources/views/layouts/modern.blade.php tests/Feature/ModulNavigationTest.php
git commit -m "feat: tautan sidebar modul ajar untuk admin, guru, kepala sekolah"
```

---

### Task 10: Verifikasi akhir

**Files:**
- Tidak ada file baru; verifikasi menyeluruh.

**Interfaces:**
- Consumes: seluruh hasil Task 1–9.
- Produces: suite hijau penuh + build produksi sukses.

- [ ] **Step 1: Jalankan seluruh suite**

Run: `php artisan config:clear; php artisan test`
Expected: PASS semua (225 test lama + 42 test baru, 0 failure). Bila ada test lama yang pecah, perbaiki penyebabnya (bukan test-nya) kecuali test lama memang mengasumsikan sesuatu yang berubah secara sah.

- [ ] **Step 2: Build produksi**

Run: `npm run build`
Expected: sukses tanpa error.

- [ ] **Step 3: Smoke test manual (gunakan skill superpowers:verification-before-completion / verify)**

Jalankan `composer dev` (atau `php artisan serve` + `npm run dev`), lalu:
1. Login admin → Modul Ajar → buat kategori → unggah PDF asli beberapa halaman + 1 tautan YouTube → modul muncul.
2. Login guru → Modul Ajar → buka modul → PDF tampil dengan skeleton dulu → balik beberapa halaman → indikator "dari N • X%" berubah → muat ulang halaman → posisi/progres tersimpan.
3. Cek dark mode di halaman baca dan rekap.
4. Login kepala sekolah → Progres Modul → guru tadi tampil dengan persen benar; guru lain 0%.
5. Uji di lebar 375px (devtools) — tidak ada scroll horizontal halaman.

- [ ] **Step 4: Commit penutup bila ada perbaikan**

```bash
git add -A
git commit -m "fix: penyesuaian hasil verifikasi akhir modul ajar"
```

(Lewati bila tidak ada perubahan.)
