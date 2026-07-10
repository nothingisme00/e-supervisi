# Video Praktik Guru Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Video praktik guru (`proses_pembelajaran.link_video` yang sudah ada) bisa langsung ditonton di dalam aplikasi lewat pemutar tertanam, plus thumbnail/badge di kartu timeline.

**Architecture:** Satu utilitas statis `App\Support\VideoEmbed` menerjemahkan URL (YouTube/Google Drive) menjadi URL embed + thumbnail; satu komponen Blade `<x-video-praktik>` menggantikan blok tautan di 4 halaman supervisi; kartu timeline beranda & "Supervisi Saya" mendapat thumbnail/badge. Tanpa tabel baru, tanpa route baru, tanpa perubahan alur pengisian. Sekalian melunasi utang: regex YouTube `ModulVideo` di-host-anchor + dukung `/shorts/`.

**Tech Stack:** Laravel 10, Blade, Tailwind (via Vite), PHPUnit. Tidak ada JS baru (iframe statis) — vitest tidak tersentuh.

**Spec:** `docs/superpowers/specs/2026-07-10-video-praktik-guru-design.md`

## Global Constraints

- **TDD-guard aktif dan sangat ketat**: setiap perubahan implementasi HARUS didahului test yang gagal (RED) spesifik. Tulis test → jalankan sampai FAIL → implementasi minimal → jalankan sampai PASS → commit. Jangan menulis implementasi sebelum test RED terlihat.
- **WAJIB `php artisan config:clear` sebelum menjalankan test apa pun** (insiden DB dev pernah terhapus oleh test dengan config cache; ada guard di `Tests\TestCase`).
- Jalankan test: `php artisan test --filter=NamaTest` (atau `vendor/bin/phpunit --filter`).
- Semua teks UI berbahasa Indonesia. Ikon = SVG inline (bukan emoji). Dark mode: setiap kelas warna punya varian `dark:`. Responsif mulai 375 px.
- Pesan commit berbahasa Indonesia gaya repo (`feat: ...`, `fix: ...`, `refactor: ...`), diakhiri baris `Co-Authored-By: Claude Fable 5 <noreply@anthropic.com>`.
- Branch kerja: `develop`.
- ID video YouTube contoh di test: `dQw4w9WgXcQ` (11 karakter, konsisten dengan test yang ada).

---

### Task 1: Utilitas `App\Support\VideoEmbed`

**Files:**
- Create: `app/Support/VideoEmbed.php`
- Test: `tests/Unit/Support/VideoEmbedTest.php`

**Interfaces:**
- Consumes: — (tidak bergantung task lain)
- Produces (dipakai Task 2–5):
  - `VideoEmbed::youtubeId(?string $url): ?string` — ID 11 karakter atau null
  - `VideoEmbed::youtubeEmbedUrl(?string $url): ?string` — `https://www.youtube.com/embed/{id}` atau null
  - `VideoEmbed::embedUrl(?string $url): ?string` — embed YouTube ATAU preview Drive (`https://drive.google.com/file/d/{id}/preview`) atau null
  - `VideoEmbed::thumbnailUrl(?string $url): ?string` — `https://img.youtube.com/vi/{id}/hqdefault.jpg` (YouTube saja) atau null

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Unit/Support/VideoEmbedTest.php`:

```php
<?php

namespace Tests\Unit\Support;

use App\Support\VideoEmbed;
use PHPUnit\Framework\TestCase;

class VideoEmbedTest extends TestCase
{
    public function test_embed_dari_url_watch(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_watch_tanpa_www(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_watch_mobile(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://m.youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_watch_dengan_parameter_lain_di_depan(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://www.youtube.com/watch?feature=share&v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_pendek(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://youtu.be/dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_shorts(): void
    {
        $this->assertSame(
            'https://www.youtube.com/embed/dQw4w9WgXcQ',
            VideoEmbed::embedUrl('https://www.youtube.com/shorts/dQw4w9WgXcQ')
        );
    }

    public function test_host_palsu_ditolak(): void
    {
        $this->assertNull(VideoEmbed::embedUrl('https://evil.com/watch?v=dQw4w9WgXcQ'));
    }

    public function test_url_youtube_di_tengah_path_host_lain_ditolak(): void
    {
        $this->assertNull(
            VideoEmbed::embedUrl('https://evil.com/https://www.youtube.com/watch?v=dQw4w9WgXcQ')
        );
    }

    public function test_embed_dari_url_google_drive(): void
    {
        $this->assertSame(
            'https://drive.google.com/file/d/1AbC-dEf_123/preview',
            VideoEmbed::embedUrl('https://drive.google.com/file/d/1AbC-dEf_123/view?usp=sharing')
        );
    }

    public function test_url_folder_drive_tidak_dikenali(): void
    {
        $this->assertNull(VideoEmbed::embedUrl('https://drive.google.com/drive/folders/1AbC'));
    }

    public function test_url_lain_tidak_dikenali(): void
    {
        $this->assertNull(VideoEmbed::embedUrl('https://vimeo.com/12345'));
    }

    public function test_null_menghasilkan_null(): void
    {
        $this->assertNull(VideoEmbed::embedUrl(null));
        $this->assertNull(VideoEmbed::thumbnailUrl(null));
        $this->assertNull(VideoEmbed::youtubeId(null));
    }

    public function test_thumbnail_youtube(): void
    {
        $this->assertSame(
            'https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg',
            VideoEmbed::thumbnailUrl('https://youtu.be/dQw4w9WgXcQ')
        );
    }

    public function test_thumbnail_drive_null(): void
    {
        $this->assertNull(
            VideoEmbed::thumbnailUrl('https://drive.google.com/file/d/1AbC-dEf_123/view')
        );
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test --filter=VideoEmbedTest`
Expected: FAIL — `Class "App\Support\VideoEmbed" not found`

- [ ] **Step 3: Implementasi minimal**

Buat `app/Support/VideoEmbed.php`:

```php
<?php

namespace App\Support;

/**
 * Menerjemahkan URL video (YouTube / Google Drive) menjadi URL embed
 * dan thumbnail. URL yang tidak dikenali menghasilkan null — pemakai
 * jatuh kembali ke tautan keluar biasa.
 */
final class VideoEmbed
{
    /** Pola host-anchored: menolak youtube.com yang bukan host sebenarnya. */
    private const YOUTUBE_PATTERNS = [
        '#^https?://(?:www\.|m\.)?youtube\.com/watch\?(?:[^\s]*&)?v=([A-Za-z0-9_-]{11})#',
        '#^https?://(?:www\.|m\.)?youtube\.com/shorts/([A-Za-z0-9_-]{11})#',
        '#^https?://youtu\.be/([A-Za-z0-9_-]{11})#',
    ];

    private const DRIVE_PATTERN = '#^https?://drive\.google\.com/file/d/([A-Za-z0-9_-]+)#';

    public static function youtubeId(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        foreach (self::YOUTUBE_PATTERNS as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        return null;
    }

    public static function youtubeEmbedUrl(?string $url): ?string
    {
        $id = self::youtubeId($url);

        return $id === null ? null : 'https://www.youtube.com/embed/' . $id;
    }

    public static function embedUrl(?string $url): ?string
    {
        if ($embed = self::youtubeEmbedUrl($url)) {
            return $embed;
        }

        if ($url !== null && preg_match(self::DRIVE_PATTERN, $url, $m)) {
            return 'https://drive.google.com/file/d/' . $m[1] . '/preview';
        }

        return null;
    }

    public static function thumbnailUrl(?string $url): ?string
    {
        $id = self::youtubeId($url);

        return $id === null ? null : 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
    }
}
```

- [ ] **Step 4: Jalankan test, pastikan lulus**

Run: `php artisan test --filter=VideoEmbedTest`
Expected: PASS (15 test)

- [ ] **Step 5: Commit**

```bash
git add app/Support/VideoEmbed.php tests/Unit/Support/VideoEmbedTest.php
git commit -m "feat: utilitas VideoEmbed (YouTube host-anchored + shorts, Drive preview)"
```

---

### Task 2: Refactor `ModulVideo` + validasi `Admin\ModulController` ke `VideoEmbed`

Melunasi utang tercatat: regex lama tidak host-anchored dan tanpa `/shorts/`.

**Files:**
- Modify: `app/Models/ModulVideo.php:17-24` (accessor `getYoutubeEmbedUrlAttribute`)
- Modify: `app/Http/Controllers/Admin/ModulController.php:121-126` (rule regex `videos.*.youtube_url`)
- Test: `tests/Unit/Models/ModulVideoModelTest.php` (tambah kasus), `tests/Feature/Admin/ModulManagementTest.php` (tambah kasus)

**Interfaces:**
- Consumes: `VideoEmbed::youtubeEmbedUrl(?string): ?string` (Task 1)
- Produces: perilaku `$modulVideo->youtube_embed_url` tidak berubah untuk URL valid; kini juga mendukung `/shorts/` dan menolak host palsu.

- [ ] **Step 1: Tulis test yang gagal**

Tambahkan di `tests/Unit/Models/ModulVideoModelTest.php` (di dalam kelas, setelah test yang ada):

```php
    public function test_embed_url_dari_format_shorts(): void
    {
        $video = $this->makeVideo('https://www.youtube.com/shorts/dQw4w9WgXcQ');

        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $video->youtube_embed_url);
    }

    public function test_embed_url_null_untuk_host_palsu(): void
    {
        // Regex lama tidak host-anchored: substring "youtube.com/watch?v=" di
        // tengah path host lain ikut lolos. Harus null setelah refactor.
        $video = $this->makeVideo('https://evil.com/youtube.com/watch?v=dQw4w9WgXcQ');

        $this->assertNull($video->youtube_embed_url);
    }
```

Tambahkan di `tests/Feature/Admin/ModulManagementTest.php` (pola `createAdmin()`/`fakePdfUpload()` sudah ada di file itu):

```php
    public function test_url_youtube_shorts_diterima(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Shorts',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(2),
            'videos' => [
                ['judul' => 'Cuplikan', 'youtube_url' => 'https://www.youtube.com/shorts/dQw4w9WgXcQ'],
            ],
        ]);

        $response->assertSessionDoesntHaveErrors();
        $this->assertDatabaseHas('modul_videos', ['judul' => 'Cuplikan']);
    }

    public function test_url_youtube_host_palsu_ditolak(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Host Palsu',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(2),
            'videos' => [
                ['judul' => 'Palsu', 'youtube_url' => 'https://evil.com/youtube.com/watch?v=dQw4w9WgXcQ'],
            ],
        ]);

        $response->assertSessionHasErrors('videos.0.youtube_url');
    }
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test --filter=ModulVideoModelTest`
Expected: FAIL — `test_embed_url_dari_format_shorts` (null) dan `test_embed_url_null_untuk_host_palsu` mungkin sudah lulus/gagal sesuai regex lama; minimal shorts FAIL.

Run: `php artisan test --filter=ModulManagementTest`
Expected: FAIL — `test_url_youtube_shorts_diterima` (regex lama menolak shorts). `test_url_youtube_host_palsu_ditolak` FAIL karena regex lama tidak host-anchored (evil.com lolos).

- [ ] **Step 3: Implementasi**

Di `app/Models/ModulVideo.php`, ganti accessor:

```php
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        return \App\Support\VideoEmbed::youtubeEmbedUrl($this->youtube_url);
    }
```

Di `app/Http/Controllers/Admin/ModulController.php`, ganti baris regex di `validateModul` (rule array `videos.*.youtube_url`):

```php
            'videos.*.youtube_url' => [
                'nullable',
                'required_with:videos.*.judul',
                'url',
                'regex:#^https?://(?:(?:www\.|m\.)?youtube\.com/(?:watch\?(?:[^\s]*&)?v=|shorts/)|youtu\.be/)[A-Za-z0-9_-]{11}#',
            ],
```

- [ ] **Step 4: Jalankan test, pastikan lulus (termasuk regresi)**

Run: `php artisan test --filter="ModulVideoModelTest|ModulManagementTest"`
Expected: PASS semua — test lama (watch, youtu.be, vimeo ditolak, baris kosong diabaikan) tetap hijau.

- [ ] **Step 5: Commit**

```bash
git add app/Models/ModulVideo.php app/Http/Controllers/Admin/ModulController.php tests/Unit/Models/ModulVideoModelTest.php tests/Feature/Admin/ModulManagementTest.php
git commit -m "refactor: ModulVideo & validasi modul pakai VideoEmbed (host-anchored + shorts)"
```

---

### Task 3: Komponen `<x-video-praktik>` + pasang di detail supervisi guru

**Files:**
- Create: `resources/views/components/video-praktik.blade.php`
- Modify: `resources/views/guru/supervisi/detail.blade.php:124-141` (blok "Link Video" di Card 2)
- Test: `tests/Feature/VideoPraktikEmbedTest.php` (file baru — Task 4 menambah kasus di file yang sama)

**Interfaces:**
- Consumes: `VideoEmbed::embedUrl(?string): ?string` (Task 1)
- Produces: komponen `<x-video-praktik :url="..." />` — prop tunggal `url` (string URL video, boleh apa pun; komponen sendiri yang memutuskan embed/fallback). Task 4 dan halaman lain memakai tag ini persis.

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/VideoPraktikEmbedTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoPraktikEmbedTest extends TestCase
{
    use RefreshDatabase;

    private const YOUTUBE_URL = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    private const YOUTUBE_EMBED = 'https://www.youtube.com/embed/dQw4w9WgXcQ';
    private const DRIVE_URL = 'https://drive.google.com/file/d/1AbC-dEf_123/view?usp=sharing';
    private const DRIVE_EMBED = 'https://drive.google.com/file/d/1AbC-dEf_123/preview';
    private const UNKNOWN_URL = 'https://contoh-sekolah.sch.id/video/praktik.mp4';

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    private function supervisiDenganVideo(User $guru, string $url): Supervisi
    {
        $supervisi = Supervisi::factory()->completed()->create(['user_id' => $guru->id]);
        ProsesPembelajaran::factory()->create([
            'supervisi_id' => $supervisi->id,
            'link_video' => $url,
        ]);

        return $supervisi;
    }

    public function test_detail_guru_menampilkan_embed_youtube(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::YOUTUBE_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertStatus(200);
        $response->assertSee(self::YOUTUBE_EMBED);
        $response->assertSee('<iframe', false);
    }

    public function test_detail_guru_menampilkan_preview_drive(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::DRIVE_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertSee(self::DRIVE_EMBED);
    }

    public function test_detail_guru_url_tak_dikenal_jatuh_ke_tautan(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($guru, self::UNKNOWN_URL);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));

        $response->assertSee(self::UNKNOWN_URL);
        $response->assertDontSee('<iframe', false);
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test --filter=VideoPraktikEmbedTest`
Expected: FAIL — `assertSee('https://www.youtube.com/embed/...')` tidak ditemukan (halaman masih menampilkan tautan mentah).

- [ ] **Step 3: Buat komponen**

Buat `resources/views/components/video-praktik.blade.php`:

```blade
@props(['url'])

@php($embedUrl = \App\Support\VideoEmbed::embedUrl($url))

<div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 rounded-r-lg p-3 sm:p-4">
    <div class="flex items-center gap-2 mb-2">
        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-white">Video Praktik Pembelajaran</span>
    </div>

    @if($embedUrl)
        <div class="relative w-full overflow-hidden rounded-lg bg-gray-900 mb-2" style="padding-bottom: 56.25%;">
            <iframe src="{{ $embedUrl }}"
                    class="absolute inset-0 w-full h-full border-0"
                    title="Video praktik pembelajaran"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    @endif

    <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-blue-600 dark:text-blue-400 hover:underline max-w-full">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
        </svg>
        <span class="truncate">{{ $url }}</span>
    </a>
</div>
```

Catatan: rasio 16:9 memakai trik `padding-bottom: 56.25%` (bukan kelas `aspect-video`) agar tidak bergantung versi/konfigurasi Tailwind.

- [ ] **Step 4: Pasang di detail guru**

Di `resources/views/guru/supervisi/detail.blade.php`, ganti seluruh blok `<!-- Link Video -->` (div `border-l-4 border-red-500 ...` berisi anchor `Link Video Pembelajaran`, lines 124–141) dengan:

```blade
                @if($supervisi->prosesPembelajaran->link_video)
                <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
                @endif
```

(Blok lama tidak punya guard `@if link_video` — guard baru ini mencegah kartu kosong bila video belum diisi.)

- [ ] **Step 5: Jalankan test, pastikan lulus**

Run: `php artisan test --filter=VideoPraktikEmbedTest`
Expected: PASS (3 test)

Run juga regresi halaman: `php artisan test --filter="SupervisiFlowTest|LayoutDivBalanceTest|DarkModeCoverageTest"`
Expected: PASS

- [ ] **Step 6: Commit**

```bash
git add resources/views/components/video-praktik.blade.php resources/views/guru/supervisi/detail.blade.php tests/Feature/VideoPraktikEmbedTest.php
git commit -m "feat: pemutar video praktik tertanam di detail supervisi guru"
```

---

### Task 4: Pasang `<x-video-praktik>` di lihat-guru-lain, evaluasi kepsek, detail admin

**Files:**
- Modify: `resources/views/guru/supervisi/view.blade.php:117-136` (blok `<!-- Link Video -->`)
- Modify: `resources/views/kepala/evaluasi/show.blade.php:158-178` (blok link video)
- Modify: `resources/views/admin/supervisi/detail.blade.php:89-109` (blok link video)
- Test: `tests/Feature/VideoPraktikEmbedTest.php` (tambah kasus di file Task 3)

**Interfaces:**
- Consumes: komponen `<x-video-praktik :url="..." />` (Task 3).
- Catatan akses (untuk setup test): `kepala.evaluasi.show` menuntut `tingkat` kepala == `tingkat` guru DAN status supervisi ∈ {submitted, under_review, revision, completed}; `guru.supervisi.view` menuntut pemilik ≠ user login; `admin.supervisi.show` bebas.

- [ ] **Step 1: Tulis test yang gagal**

Tambahkan di `tests/Feature/VideoPraktikEmbedTest.php`:

```php
    public function test_lihat_supervisi_guru_lain_menampilkan_embed_youtube(): void
    {
        $pemilik = $this->createGuru();
        $penonton = $this->createGuru();
        $supervisi = $this->supervisiDenganVideo($pemilik, self::YOUTUBE_URL);

        $response = $this->actingAs($penonton)->get(route('guru.supervisi.view', $supervisi->id));

        $response->assertStatus(200);
        $response->assertSee(self::YOUTUBE_EMBED);
    }

    public function test_evaluasi_kepala_sekolah_menampilkan_embed_youtube(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $supervisi = $this->supervisiDenganVideo($guru, self::YOUTUBE_URL);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertStatus(200);
        $response->assertSee(self::YOUTUBE_EMBED);
    }

    public function test_detail_admin_menampilkan_embed_youtube(): void
    {
        $guru = $this->createGuru();
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $supervisi = $this->supervisiDenganVideo($guru, self::YOUTUBE_URL);

        $response = $this->actingAs($admin)->get(route('admin.supervisi.show', $supervisi->id));

        $response->assertStatus(200);
        $response->assertSee(self::YOUTUBE_EMBED);
    }
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test --filter=VideoPraktikEmbedTest`
Expected: 3 test baru FAIL (embed tidak ditemukan); 3 test Task 3 tetap PASS.

- [ ] **Step 3: Pasang komponen di 3 halaman**

`resources/views/guru/supervisi/view.blade.php` — ganti blok di dalam `@if($supervisi->prosesPembelajaran->link_video) ... @endif` (div `border-l-4 border-red-500` berisi "Video Pembelajaran", lines 118–135) sehingga menjadi:

```blade
                    @if($supervisi->prosesPembelajaran->link_video)
                    <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
                    @endif
```

`resources/views/kepala/evaluasi/show.blade.php` — ganti isi `@if($supervisi->prosesPembelajaran->link_video) ... @endif` (div `border-l-4 border-red-500` berisi "Link Video Pembelajaran", lines 159–177) sehingga menjadi:

```blade
                            @if($supervisi->prosesPembelajaran->link_video)
                                <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
                            @endif
```

`resources/views/admin/supervisi/detail.blade.php` — ganti isi `@if($supervisi->prosesPembelajaran->link_video) ... @endif` (div gradient `from-red-50 to-pink-50` berisi "Link Video Pembelajaran", lines 90–108) sehingga menjadi:

```blade
                            @if($supervisi->prosesPembelajaran->link_video)
                                <x-video-praktik :url="$supervisi->prosesPembelajaran->link_video" />
                            @endif
```

- [ ] **Step 4: Jalankan test, pastikan lulus + regresi**

Run: `php artisan test --filter=VideoPraktikEmbedTest`
Expected: PASS (6 test)

Run: `php artisan test --filter="EvaluasiTest|SupervisiReviewTest|LayoutDivBalanceTest|DarkModeCoverageTest"`
Expected: PASS

- [ ] **Step 5: Commit**

```bash
git add resources/views/guru/supervisi/view.blade.php resources/views/kepala/evaluasi/show.blade.php resources/views/admin/supervisi/detail.blade.php tests/Feature/VideoPraktikEmbedTest.php
git commit -m "feat: pemutar video praktik di halaman lihat guru lain, evaluasi kepsek, detail admin"
```

---

### Task 5: Thumbnail/badge video di kartu timeline (beranda + Supervisi Saya)

**Files:**
- Modify: `resources/views/guru/home.blade.php` (baris info-cards, setelah badge Proses — anchor: div `@if($hasProses) ... @endif` sekitar line 177–191)
- Modify: `resources/views/guru/my-supervisi.blade.php` (setelah grid kotak status yang memuat `{{ $hasProses ? '✓' : '✗' }}`, sekitar line 117–124)
- Test: `tests/Feature/Guru/TimelineVideoBadgeTest.php`

**Interfaces:**
- Consumes: `VideoEmbed::thumbnailUrl(?string): ?string` (Task 1); route `guru.supervisi.detail` (sudah ada).
- Produces: — (tugas terakhir yang mengubah UI).

- [ ] **Step 1: Tulis test yang gagal**

Buat `tests/Feature/Guru/TimelineVideoBadgeTest.php`:

```php
<?php

namespace Tests\Feature\Guru;

use App\Models\ProsesPembelajaran;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimelineVideoBadgeTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    private function supervisiDenganVideo(User $guru, ?string $url): Supervisi
    {
        $supervisi = Supervisi::factory()->completed()->create(['user_id' => $guru->id]);

        if ($url !== null) {
            ProsesPembelajaran::factory()->create([
                'supervisi_id' => $supervisi->id,
                'link_video' => $url,
            ]);
        }

        return $supervisi;
    }

    public function test_beranda_menampilkan_thumbnail_youtube(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee('img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
    }

    public function test_beranda_menampilkan_badge_untuk_url_tanpa_thumbnail(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://drive.google.com/file/d/1AbC-dEf_123/view');

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertSee('Video Praktik');
        $response->assertDontSee('img.youtube.com');
    }

    public function test_beranda_tanpa_video_tanpa_badge(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, null);

        $response = $this->actingAs($guru)->get(route('guru.home'));

        $response->assertDontSee('Video Praktik');
    }

    public function test_supervisi_saya_menampilkan_thumbnail_youtube(): void
    {
        $guru = $this->createGuru();
        $this->supervisiDenganVideo($guru, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this->actingAs($guru)->get(route('guru.my-supervisi'));

        $response->assertSee('img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
    }
}
```

- [ ] **Step 2: Jalankan test, pastikan gagal**

Run: `php artisan config:clear && php artisan test --filter=TimelineVideoBadgeTest`
Expected: FAIL — thumbnail/badge belum ada di kedua halaman.

Catatan: bila `test_beranda_tanpa_video_tanpa_badge` PASS sejak awal itu wajar (fitur belum ada); ia menjadi guard regresi setelah implementasi.

- [ ] **Step 3: Implementasi di beranda**

Di `resources/views/guru/home.blade.php`, tepat SETELAH blok `@if($hasProses) ... @endif` (badge "Proses Selesai"/"Proses Belum", sebelum `@if($item->feedback->count() > 0)`), tambahkan:

```blade
                        @php($videoUrl = $item->prosesPembelajaran->link_video ?? null)
                        @if($videoUrl)
                            @php($videoThumb = \App\Support\VideoEmbed::thumbnailUrl($videoUrl))
                            <a href="{{ route('guru.supervisi.detail', $item->id) }}"
                               class="flex items-center gap-1.5 sm:gap-1.5 md:gap-2 px-2 py-1 sm:px-2.5 sm:py-1.5 md:px-3 bg-red-50 dark:bg-red-900/20 rounded-md md:rounded-lg border border-red-100 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                                @if($videoThumb)
                                    <img src="{{ $videoThumb }}" alt="Thumbnail video praktik" loading="lazy"
                                         class="w-12 h-7 sm:w-14 sm:h-8 object-cover rounded flex-shrink-0">
                                @else
                                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                                <span class="text-[10px] sm:text-xs font-semibold text-red-700 dark:text-red-300">Video Praktik</span>
                            </a>
                        @endif
```

- [ ] **Step 4: Implementasi di Supervisi Saya**

Di `resources/views/guru/my-supervisi.blade.php`, tepat SETELAH penutup div grid kotak status (grid yang memuat kotak `Proses` dengan `{{ $hasProses ? '✓' : '✗' }}`), tambahkan blok yang sama persis seperti Step 3 (salin utuh, variabel `$item` sama).

- [ ] **Step 5: Jalankan test, pastikan lulus + regresi**

Run: `php artisan test --filter=TimelineVideoBadgeTest`
Expected: PASS (4 test)

Run: `php artisan test --filter="SupervisiFlowTest|LayoutDivBalanceTest|DarkModeCoverageTest|StatusBadgeConsistencyTest"`
Expected: PASS

- [ ] **Step 6: Commit**

```bash
git add resources/views/guru/home.blade.php resources/views/guru/my-supervisi.blade.php tests/Feature/Guru/TimelineVideoBadgeTest.php
git commit -m "feat: thumbnail/badge video praktik di kartu timeline beranda & supervisi saya"
```

---

### Task 6: Verifikasi penuh

**Files:** — (tidak ada perubahan kode; hanya verifikasi)

- [ ] **Step 1: Suite PHP penuh**

Run: `php artisan config:clear && php artisan test`
Expected: PASS semua (±275 test lama + ±24 baru), 0 failure.

- [ ] **Step 2: Suite JS + build**

Run: `npm run test`
Expected: PASS (13 vitest lama — tidak ada JS baru).

Run: `npm run build`
Expected: build sukses tanpa error.

- [ ] **Step 3: Laporkan hasil**

Tidak ada commit di task ini. Laporkan jumlah test dan status build. Smoke test browser dilakukan sesi utama setelah semua task selesai (lihat spec bagian Verifikasi Akhir): login guru → beranda (thumbnail) → detail (pemutar YouTube), kasus Drive & URL lain, halaman kepsek & admin, dark mode, 375 px.
