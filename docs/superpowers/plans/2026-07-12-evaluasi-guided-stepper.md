# Alur Evaluasi Berpandu (Guided Stepper) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengubah tinjauan supervisi kepala sekolah menjadi alur berpandu 4 langkah (Tinjau Materi → Isi Rubrik → Feedback → Selesai) dengan rubrik sebagai fokus utama.

**Architecture:** Stepper lintas halaman server-rendered: `show` = langkah 1, `rubrik` = langkah 2, halaman GET baru `feedback` = langkah 3, langkah 4 = end-state. Status langkah dihitung dari data (status supervisi, jumlah skor rubrik, ada/tidaknya feedback kepala). Instrumen rubrik 47 aspek skor 0/1/2 tidak berubah.

**Tech Stack:** Laravel 11 Blade + Tailwind v4 (token `primary` teal), PHPUnit. TDD-guard aktif: SATU test baru per edit file test, RED dulu sebelum implementasi.

## Global Constraints

- `php artisan config:clear` WAJIB sebelum menjalankan test (insiden DB).
- Radius: kartu/panel `rounded-xl`, tombol/input `rounded-lg`, badge/pill/avatar `rounded-full`; `rounded-md`/`rounded-3xl` dilarang (RadiusConsistencyTest).
- Warna indigo/purple/violet dilarang (ColorTokenMigrationTest) — pakai token `primary-*`.
- Setiap `bg-white` di view wajib ada varian `dark:` di file yang sama (DarkModeCoverageTest).
- Ikon hanya via `<x-icon>` ([resources/views/components/icon.blade.php](../../resources/views/components/icon.blade.php)) — jangan tempel SVG mentah.
- Teks UI bahasa Indonesia; tanggal `translatedFormat`.
- Setelah menambah kelas Tailwind baru: `npm run build`.

---

### Task 1: Komponen `x-evaluasi-stepper` + pasang di halaman show

**Files:**
- Create: `resources/views/components/evaluasi-stepper.blade.php`
- Modify: `resources/views/kepala/evaluasi/show.blade.php` (sisip di bawah header section)
- Test: `tests/Feature/KepalaSekolah/EvaluasiStepperTest.php` (baru)

**Interfaces:**
- Produces: komponen Blade `<x-evaluasi-stepper :supervisi="$supervisi" :aktif="1|2|3" />`; atribut DOM `data-stepper-step="N" data-status="selesai|aktif|mendatang"` untuk assert test.
- Consumes: `RubrikItem::active()`, relasi `evaluasiRubrik.scores`, `feedback.user`, route `kepala.evaluasi.{show,rubrik}` (route `feedback.show` baru dipakai bila sudah ada — sampai Task 3, node 3 pakai `route('kepala.evaluasi.show', …)` placeholder TIDAK boleh — lihat catatan di Step 3: node 3 link ke `#` dulu, diganti Task 3).

- [ ] **Step 1: Tulis test gagal pertama (stepper tampil, langkah 1 aktif)**

```php
<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiStepperTest extends TestCase
{
    use RefreshDatabase;

    private function kepalaDanSupervisi(string $status = 'submitted'): array
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id, 'status' => $status]);

        return [$kepala, $supervisi];
    }

    public function test_halaman_show_menampilkan_stepper_langkah_1_aktif(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('data-stepper-step="1" data-status="aktif"', false);
        $response->assertSee('Tinjau Materi');
        $response->assertSee('Isi Rubrik');
    }
}
```

Catatan: cek dulu nama state factory kepala (`kepalaSekolah()` — verifikasi di `database/factories/UserFactory.php`; kalau namanya lain, pakai yang ada). Status `submitted` mungkin butuh factory state `submitted()` — samakan dengan pola `RubrikPenilaianTest`.

- [ ] **Step 2: Jalankan test, pastikan RED**

Run: `php artisan config:clear; php artisan test --filter EvaluasiStepperTest`
Expected: FAIL (string `data-stepper-step` tidak ada).

- [ ] **Step 3: Implementasi komponen + sisip di show**

`resources/views/components/evaluasi-stepper.blade.php`:

```blade
{{--
    Stepper 4 langkah alur evaluasi kepala sekolah.
    Status langkah dihitung dari data supervisi; node 1-3 adalah link halaman,
    node 4 (Selesai) indikator saja.

    Props:
        - supervisi (Supervisi, wajib)
        - aktif (int 1-3, wajib): langkah halaman yang sedang dibuka.
--}}
@props(['supervisi', 'aktif'])

@php
    $jumlahItemAktif = \App\Models\RubrikItem::active()->count();
    $rubrikLengkap = $supervisi->evaluasiRubrik
        && $supervisi->evaluasiRubrik->scores->count() >= $jumlahItemAktif
        && $jumlahItemAktif > 0;
    $adaFeedbackKepala = $supervisi->feedback
        ->contains(fn ($f) => $f->user && $f->user->role === 'kepala_sekolah');

    $langkah = [
        1 => ['label' => 'Tinjau Materi',
              'selesai' => in_array($supervisi->status, ['under_review', 'revision', 'completed']),
              'url' => route('kepala.evaluasi.show', $supervisi->id)],
        2 => ['label' => 'Isi Rubrik',
              'selesai' => $rubrikLengkap,
              'url' => route('kepala.evaluasi.rubrik', $supervisi->id)],
        3 => ['label' => 'Feedback',
              'selesai' => $adaFeedbackKepala,
              'url' => null], // diisi route feedback.show pada Task 3
        4 => ['label' => 'Selesai',
              'selesai' => $supervisi->status === 'completed',
              'url' => null],
    ];
@endphp

<x-card class="mb-4 sm:mb-6 px-4 py-4 sm:px-6">
    <ol class="flex items-start">
        @foreach ($langkah as $n => $step)
            @php
                $status = $step['selesai'] ? 'selesai' : ($n === (int) $aktif ? 'aktif' : 'mendatang');
            @endphp
            @if ($n > 1)
                <div aria-hidden="true"
                     class="flex-1 h-0.5 mt-4 sm:mt-5 {{ $langkah[$n - 1]['selesai'] ? 'bg-primary-600' : 'bg-gray-200 dark:bg-gray-700' }}"></div>
            @endif
            <li class="flex flex-col items-center gap-1.5 shrink-0 px-1 sm:px-3"
                data-stepper-step="{{ $n }}" data-status="{{ $status }}">
                @php
                    $lingkaran = match ($status) {
                        'selesai' => 'bg-primary-600 text-white',
                        'aktif' => 'bg-white dark:bg-gray-800 text-primary-700 dark:text-primary-300 border-2 border-primary-600 ring-4 ring-primary-100 dark:ring-primary-900/40',
                        default => 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500',
                    };
                    $labelCls = match ($status) {
                        'selesai' => 'text-primary-700 dark:text-primary-300',
                        'aktif' => 'text-primary-700 dark:text-primary-300 font-bold',
                        default => 'text-gray-400 dark:text-gray-500',
                    };
                @endphp
                @if ($step['url'] && $status !== 'aktif')
                    <a href="{{ $step['url'] }}" wire:navigate
                       class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $lingkaran }}">
                        @if ($step['selesai']) <x-icon name="check" class="w-4 h-4 sm:w-5 sm:h-5" /> @else {{ $n }} @endif
                    </a>
                @else
                    <span class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $lingkaran }}">
                        @if ($step['selesai'] && $status !== 'aktif') <x-icon name="check" class="w-4 h-4 sm:w-5 sm:h-5" /> @else {{ $n }} @endif
                    </span>
                @endif
                <span class="text-[11px] sm:text-xs font-semibold text-center {{ $labelCls }}">{{ $step['label'] }}</span>
            </li>
        @endforeach
    </ol>
</x-card>
```

Di `show.blade.php`, sisip tepat setelah `</div>` penutup Header Section (sebelum `<!-- Vertical Card Layout -->`):

```blade
<x-evaluasi-stepper :supervisi="$supervisi" :aktif="1" />
```

- [ ] **Step 4: Jalankan test, pastikan GREEN**

Run: `php artisan test --filter EvaluasiStepperTest`
Expected: PASS.

- [ ] **Step 5: Test kedua — langkah selesai (satu per satu; TDD-guard menolak >1 test sekaligus)**

Tambah SATU test:

```php
    public function test_stepper_menandai_langkah_selesai_sesuai_data(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('data-stepper-step="1" data-status="selesai"', false);
        $response->assertSee('data-stepper-step="2" data-status="mendatang"', false);
        $response->assertSee('data-stepper-step="4" data-status="mendatang"', false);
    }
```

Run RED bila perlu (kemungkinan langsung GREEN karena implementasi sudah ada — itu sah, test regresi). Lalu tambah SATU test lagi:

```php
    public function test_stepper_langkah_2_selesai_saat_rubrik_lengkap(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');
        $item = \App\Models\RubrikItem::factory()->create(['is_active' => true]);
        \App\Models\EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [$item->id => 2], null);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('data-stepper-step="2" data-status="selesai"', false);
    }
```

Catatan: pola pembuatan RubrikItem/EvaluasiRubrik contek dari `tests/Feature/KepalaSekolah/RubrikPenilaianTest.php` (helper yang sudah terbukti). Jika `RubrikItem::factory()` tidak ada, buat item via `RubrikItem::create([...])` mengikuti pola test tsb.

- [ ] **Step 6: Jalankan test file penuh + commit**

Run: `php artisan test --filter EvaluasiStepperTest`
Expected: 3 PASS.

```bash
git add resources/views/components/evaluasi-stepper.blade.php resources/views/kepala/evaluasi/show.blade.php tests/Feature/KepalaSekolah/EvaluasiStepperTest.php
git commit -m "feat: stepper alur evaluasi berpandu di halaman tinjau materi"
```

---

### Task 2: Header ringkas + rampingkan show jadi Langkah 1 + bar aksi

**Files:**
- Create: `resources/views/components/evaluasi-guru-header.blade.php`
- Create: `resources/views/components/evaluasi-action-bar.blade.php`
- Modify: `resources/views/kepala/evaluasi/show.blade.php`
- Test: `tests/Feature/KepalaSekolah/EvaluasiStepperTest.php` (tambah test)

**Interfaces:**
- Produces: `<x-evaluasi-guru-header :supervisi="$supervisi" />`; `<x-evaluasi-action-bar :langkah="1" judul="Tinjau Materi">{slot tombol}</x-evaluasi-action-bar>`.
- Konten yang DIHAPUS dari show dipindah ke halaman feedback pada Task 3 (kartu rubrik-summary, thread, form feedback, modal revisi, form complete + JS terkait). Sampai Task 3 selesai, konten itu hilang sementara dari UI — kerjakan Task 2 dan 3 dalam sesi yang sama sebelum push.

- [ ] **Step 1: Test gagal — show menampilkan bar aksi & tidak lagi menampilkan form feedback**

Tambah SATU test di `EvaluasiStepperTest`:

```php
    public function test_show_menjadi_langkah_tinjau_materi_dengan_bar_aksi(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('Langkah 1');
        $response->assertSee('Lanjut: Isi Rubrik');
        $response->assertDontSee('Berikan Feedback');
    }
```

- [ ] **Step 2: Run RED**

Run: `php artisan test --filter test_show_menjadi_langkah_tinjau_materi`
Expected: FAIL ("Langkah 1" belum ada / "Berikan Feedback" masih ada).

- [ ] **Step 3: Implementasi**

`resources/views/components/evaluasi-guru-header.blade.php`:

```blade
{{--
    Header ringkas guru untuk halaman alur evaluasi (show / rubrik / feedback).

    Props:
        - supervisi (Supervisi, wajib): relasi user harus ter-load.
--}}
@props(['supervisi'])

<x-card class="mb-4 sm:mb-6 p-4 sm:p-5">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold text-base shadow-md ring-2 ring-primary-100 dark:ring-primary-900/50 shrink-0">
                {{ strtoupper(substr($supervisi->user->name, 0, 2)) }}
            </div>
            <div class="min-w-0">
                <h1 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate">{{ $supervisi->user->name }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 truncate">NIK {{ $supervisi->user->nik }}</p>
            </div>
        </div>
        <div class="shrink-0">
            <x-status-badge :status="$supervisi->status" />
        </div>
    </div>
</x-card>
```

`resources/views/components/evaluasi-action-bar.blade.php`:

```blade
{{--
    Bar aksi sticky bawah untuk halaman alur evaluasi.

    Props:
        - langkah (int, wajib), judul (string, wajib) — teks kiri "Langkah N · Judul".
        - slot: tombol aksi (kanan).
    bottom-20 di mobile memberi ruang bottom-nav; md:bottom-4 di desktop.
--}}
@props(['langkah', 'judul'])

<div class="sticky bottom-20 md:bottom-4 z-20 mt-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 shadow-[0_-6px_16px_-8px_rgba(15,23,42,0.2),0_4px_12px_-6px_rgba(15,23,42,0.15)] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Langkah {{ $langkah }} · {{ $judul }}</p>
    <div class="flex items-center justify-end gap-2 sm:gap-3">
        {{ $slot }}
    </div>
</div>
```

Perubahan `show.blade.php`:
1. Ganti seluruh blok "Header Section" (div `bg-white … mb-4 sm:mb-6` berisi avatar/nama/email/NIK/status/Mulai Review/Disubmit) dengan `<x-evaluasi-guru-header :supervisi="$supervisi" />` — stepper tetap di bawahnya.
2. Hapus: Card 3.5 Rubrik Penilaian, Card 4 Diskusi & Feedback, Card 5 Berikan Feedback (beserta `@if($supervisi->status !== 'submitted')` pembungkusnya), form `completeForm`, modal `revisionModal`, dan seluruh `<script>` KECUALI tidak ada yang tersisa dipakai (hapus blok script; `toggleReplyForm` ikut pindah ke halaman feedback di Task 3).
3. Tambah sebelum `</div>` penutup container utama:

```blade
    <x-evaluasi-action-bar :langkah="1" judul="Tinjau Materi">
        @if ($supervisi->status === 'submitted')
            <form action="{{ route('kepala.evaluasi.startReview', $supervisi->id) }}" method="POST">
                @csrf
                <x-button type="submit">
                    Mulai Review & Lanjut
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-button>
            </form>
        @else
            <x-button href="{{ route('kepala.evaluasi.rubrik', $supervisi->id) }}">
                Lanjut: Isi Rubrik
                <x-icon name="arrow-right" class="w-4 h-4" />
            </x-button>
        @endif
    </x-evaluasi-action-bar>
```

(Verifikasi dulu API `x-button` di `resources/views/components/button.blade.php` — prop `href`/`variant`/`size` — dan sesuaikan.)

- [ ] **Step 4: Run GREEN + cek regresi evaluasi**

Run: `php artisan test --filter "EvaluasiStepperTest|EvaluasiTest"`
Expected: EvaluasiStepperTest PASS. Bila EvaluasiTest ada yang FAIL karena assertSee konten yang pindah (mis. teks feedback di show), catat — perbaikan assertion dilakukan di Task 3 Step 4 setelah halaman feedback ada. Jangan ubah perilaku POST.

- [ ] **Step 5: Commit**

```bash
git add resources/views/components/evaluasi-guru-header.blade.php resources/views/components/evaluasi-action-bar.blade.php resources/views/kepala/evaluasi/show.blade.php tests/Feature/KepalaSekolah/EvaluasiStepperTest.php
git commit -m "feat: show jadi langkah 1 tinjau materi (header ringkas + bar aksi)"
```

---

### Task 3: Halaman Feedback (Langkah 3) — route + controller + view

**Files:**
- Modify: `app/Http/Controllers/KepalaSekolah/EvaluasiController.php` (method `showFeedback`)
- Modify: `routes/web.php` (dalam group `kepala` → `evaluasi`, setelah baris `->name('rubrik.pdf')`)
- Create: `resources/views/kepala/evaluasi/feedback.blade.php`
- Modify: `resources/views/components/evaluasi-stepper.blade.php` (node 3 diberi URL)
- Test: `tests/Feature/KepalaSekolah/EvaluasiFeedbackPageTest.php` (baru)

**Interfaces:**
- Produces: route GET `kepala/evaluasi/{id}/feedback` bernama `kepala.evaluasi.feedback.show`; view `kepala.evaluasi.feedback`.
- Consumes: partial `supervisi._feedback-thread` (parameter sama seperti pemakaian lama di show), komponen Task 1–2.

- [ ] **Step 1: Test gagal — halaman feedback bisa diakses**

```php
<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiFeedbackPageTest extends TestCase
{
    use RefreshDatabase;

    private function kepalaDanSupervisi(string $status = 'under_review', string $tingkatKepala = 'SD'): array
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => $tingkatKepala]);
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id, 'status' => $status]);

        return [$kepala, $supervisi];
    }

    public function test_halaman_feedback_tampil_dengan_stepper_langkah_3(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertOk();
        $response->assertSee('data-stepper-step="3" data-status="aktif"', false);
        $response->assertSee('Berikan Feedback');
        $response->assertSee('Langkah 3');
    }
}
```

- [ ] **Step 2: Run RED**

Run: `php artisan config:clear; php artisan test --filter EvaluasiFeedbackPageTest`
Expected: FAIL (route tidak terdefinisi).

- [ ] **Step 3: Implementasi**

Route (`routes/web.php`, setelah baris `rubrik.pdf`):

```php
            Route::get('/{id}/feedback', [EvaluasiController::class, 'showFeedback'])->name('feedback.show');
```

PENTING: letakkan SEBELUM `Route::get('/{id}', …)->name('show')` ATAU biarkan di bawah — keduanya aman karena `/{id}` hanya cocok satu segmen; ikuti penempatan setelah `rubrik.pdf` agar rapi.

Controller — tambahkan setelah method `show`:

```php
    public function showFeedback($id)
    {
        $supervisi = Supervisi::with([
            'user',
            'evaluasiRubrik.scores',
            'feedback.user',
            'feedback.replies.user',
        ])->findOrFail($id);

        if ($supervisi->user->tingkat !== auth()->user()->tingkat) {
            abort(403, 'Anda tidak memiliki akses ke supervisi ini');
        }

        if (!in_array($supervisi->status, ['submitted', 'under_review', 'revision', 'completed'])) {
            abort(403, 'Anda tidak memiliki akses ke supervisi ini');
        }

        return view('kepala.evaluasi.feedback', compact('supervisi'));
    }
```

View `resources/views/kepala/evaluasi/feedback.blade.php` — struktur (konten kartu dipindah dari show lama; ambil markup persis dari git history `git show HEAD~1:resources/views/kepala/evaluasi/show.blade.php` bila perlu):

```blade
@extends('layouts.modern')

@section('page-title', 'Feedback Supervisi - ' . $supervisi->user->name)

@section('content')
<div class="w-full lg:w-3/4 mx-auto px-0 sm:px-4 pb-24 md:pb-0">

    <x-evaluasi-guru-header :supervisi="$supervisi" />
    <x-evaluasi-stepper :supervisi="$supervisi" :aktif="3" />

    <div class="space-y-4 sm:space-y-6">
        {{-- Kartu ringkasan rubrik: pindahan "Card 3.5" show lama, apa adanya
             (nilai akhir, skor, predikat, tombol Edit Rubrik & Unduh PDF) --}}

        {{-- Kartu "Diskusi & Feedback": pindahan Card 4 (include supervisi._feedback-thread
             dengan parameter sama persis seperti di show lama) --}}

        {{-- Kartu "Berikan Feedback": pindahan Card 5 lengkap dengan form,
             checkbox minta revisi, dan blok completed-state --}}
    </div>

    <x-evaluasi-action-bar :langkah="3" judul="Feedback">
        <x-button href="{{ route('kepala.evaluasi.rubrik', $supervisi->id) }}" variant="secondary">
            <x-icon name="arrow-left" class="w-4 h-4" />
            Kembali
        </x-button>
        @if ($supervisi->status === 'under_review')
            <x-button type="button" id="completeButton" onclick="confirmComplete()">
                <x-icon name="check-circle" class="w-4 h-4" />
                Tandai Selesai
            </x-button>
        @endif
    </x-evaluasi-action-bar>
</div>

{{-- Pindahan dari show lama: completeForm tersembunyi, revisionModal,
     dan seluruh <script> (confirmComplete, show/hideRevisionModal,
     toggle complete-button saat checkbox revisi, toggleReplyForm). --}}
@endsection
```

Tombol "Tandai Selesai" di Card 5 lama DIHAPUS dari form (sudah di bar aksi); tombol "Kirim Feedback" tetap di dalam form.

Update stepper node 3 di `evaluasi-stepper.blade.php`:

```php
        3 => ['label' => 'Feedback',
              'selesai' => $adaFeedbackKepala,
              'url' => route('kepala.evaluasi.feedback.show', $supervisi->id)],
```

- [ ] **Step 4: Run GREEN + test lanjutan satu-per-satu**

Run: `php artisan test --filter EvaluasiFeedbackPageTest` → PASS.

Tambah SATU per satu (run di antaranya):

```php
    public function test_halaman_feedback_403_untuk_kepala_beda_tingkat(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review', 'SMP');

        $this->actingAs($kepala)
            ->get(route('kepala.evaluasi.feedback.show', $supervisi->id))
            ->assertForbidden();
    }
```

```php
    public function test_stepper_langkah_3_selesai_setelah_kepala_memberi_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();
        \App\Models\Feedback::create([
            'supervisi_id' => $supervisi->id,
            'user_id' => $kepala->id,
            'komentar' => 'Feedback dari kepala sekolah untuk stepper.',
        ]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertSee('data-stepper-step="3" data-status="selesai"', false);
    }
```

Lalu jalankan `php artisan test --filter "EvaluasiTest|EvaluasiStepperTest|EvaluasiFeedbackPageTest"` — perbaiki assertion EvaluasiTest yang menunjuk konten pindahan (arahkan GET-nya ke `feedback.show` bila test menguji konten feedback di show).

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/KepalaSekolah/EvaluasiController.php routes/web.php resources/views/kepala/evaluasi/feedback.blade.php resources/views/components/evaluasi-stepper.blade.php tests/
git commit -m "feat: halaman feedback (langkah 3) alur evaluasi berpandu"
```

---

### Task 4: Redirect controller mengikuti alur

**Files:**
- Modify: `app/Http/Controllers/KepalaSekolah/EvaluasiController.php` (`storeRubrik`, `giveFeedback`)
- Test: `tests/Feature/KepalaSekolah/EvaluasiFeedbackPageTest.php` (tambah test)

**Interfaces:**
- Produces: `storeRubrik` menerima field opsional `lanjut` (`"1"` = redirect ke `feedback.show`; tanpa itu redirect kembali ke `rubrik` dengan pesan "Draf rubrik tersimpan"); `giveFeedback` (kedua cabang: revisi & normal) redirect ke `feedback.show`.

- [ ] **Step 1: Test gagal — simpan rubrik dengan `lanjut=1` diarahkan ke halaman feedback**

Tambah SATU test:

```php
    public function test_simpan_rubrik_dengan_lanjut_redirect_ke_halaman_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();
        $item = \App\Models\RubrikItem::factory()->create(['is_active' => true]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), [
            'skor' => [$item->id => 2],
            'lanjut' => '1',
        ]);

        $response->assertRedirect(route('kepala.evaluasi.feedback.show', $supervisi->id));
    }
```

(Sama seperti Task 1: kalau `RubrikItem::factory()` tidak ada, ikuti pola pembuatan item di `RubrikPenilaianTest`.)

- [ ] **Step 2: Run RED**

Run: `php artisan test --filter test_simpan_rubrik_dengan_lanjut`
Expected: FAIL (redirect ke `kepala.evaluasi.show`).

- [ ] **Step 3: Implementasi — ganti blok return `storeRubrik`**

```php
        if ($request->boolean('lanjut')) {
            return redirect()->route('kepala.evaluasi.feedback.show', $id)
                ->with('success', 'Rubrik penilaian berhasil disimpan');
        }

        return redirect()->route('kepala.evaluasi.rubrik', $id)
            ->with('success', 'Draf rubrik tersimpan');
```

- [ ] **Step 4: Run GREEN, lalu test giveFeedback (satu test, lalu implementasi)**

```php
    public function test_kirim_feedback_redirect_ke_halaman_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Feedback minimal sepuluh karakter.',
        ]);

        $response->assertRedirect(route('kepala.evaluasi.feedback.show', $supervisi->id));
    }
```

RED → ganti DUA return di `giveFeedback` (cabang revisi dan cabang normal) dari `route('kepala.evaluasi.show', $id)` menjadi `route('kepala.evaluasi.feedback.show', $id)` (pesan `with` tetap) → GREEN.

- [ ] **Step 5: Regression + commit**

Run: `php artisan test --filter "RubrikPenilaianTest|EvaluasiTest|EvaluasiFeedbackPageTest"`
Expected: semua PASS (assertRedirect lama generik).

```bash
git add app/Http/Controllers/KepalaSekolah/EvaluasiController.php tests/Feature/KepalaSekolah/EvaluasiFeedbackPageTest.php
git commit -m "feat: redirect alur rubrik->feedback mengikuti stepper"
```

---

### Task 5: Halaman rubrik — stepper aktif 2 + bar aksi + tombol lanjut ter-guard

**Files:**
- Modify: `resources/views/kepala/evaluasi/rubrik.blade.php`
- Test: `tests/Feature/KepalaSekolah/EvaluasiStepperTest.php` (tambah test)

**Interfaces:**
- Consumes: komponen Task 1–2, perilaku `lanjut=1` Task 4, JS `rubrikUpdateProgress()` existing.
- Produces: tombol `#btnLanjutFeedback` (submit, `name="lanjut" value="1"`, disabled sampai semua item terisi), tombol "Simpan Draf" (submit tanpa `lanjut`).

- [ ] **Step 1: Test gagal**

Tambah SATU test di `EvaluasiStepperTest`:

```php
    public function test_halaman_rubrik_menampilkan_stepper_dan_tombol_lanjut_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.rubrik', $supervisi->id));

        $response->assertSee('data-stepper-step="2" data-status="aktif"', false);
        $response->assertSee('Simpan & Lanjut Feedback');
        $response->assertSee('id="btnLanjutFeedback"', false);
        $response->assertSee('Simpan Draf');
    }
```

- [ ] **Step 2: Run RED**

Run: `php artisan test --filter test_halaman_rubrik_menampilkan_stepper`
Expected: FAIL.

- [ ] **Step 3: Implementasi di `rubrik.blade.php`**

1. Setelah `<x-page-header … />`, tambah:

```blade
    <x-evaluasi-guru-header :supervisi="$supervisi" />
    <x-evaluasi-stepper :supervisi="$supervisi" :aktif="2" />
```

2. Di step 4 (Ringkasan), HAPUS `<x-button type="submit" class="w-full justify-center">Simpan Rubrik Penilaian</x-button>`.
3. Sebelum `</form>`, tambah bar aksi (masih DI DALAM form agar tombol submit bekerja):

```blade
        <x-evaluasi-action-bar :langkah="2" judul="Isi Rubrik Penilaian">
            <x-button href="{{ route('kepala.evaluasi.show', $supervisi->id) }}" variant="secondary">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Kembali
            </x-button>
            <x-button type="submit" variant="secondary">Simpan Draf</x-button>
            <x-button type="submit" id="btnLanjutFeedback" name="lanjut" value="1" disabled>
                Simpan &amp; Lanjut Feedback
                <x-icon name="arrow-right" class="w-4 h-4" />
            </x-button>
        </x-evaluasi-action-bar>
```

(Cek `x-button` meneruskan atribut `name`/`value`/`disabled` — komponen memakai `$attributes->merge`, harusnya lolos. Kalau `disabled` styling tidak ada, tambah kelas `disabled:opacity-50 disabled:cursor-not-allowed` via atribut class.)

4. Extend JS `rubrikUpdateProgress()` — setelah baris update progress bar, tambah:

```js
    const btnLanjut = document.getElementById('btnLanjutFeedback');
    if (btnLanjut) btnLanjut.disabled = filled < groups.length;
```

- [ ] **Step 4: Run GREEN**

Run: `php artisan test --filter "EvaluasiStepperTest|RubrikPenilaianTest"`
Expected: PASS semua.

- [ ] **Step 5: Commit**

```bash
git add resources/views/kepala/evaluasi/rubrik.blade.php tests/Feature/KepalaSekolah/EvaluasiStepperTest.php
git commit -m "feat: halaman rubrik jadi langkah 2 dengan bar aksi lanjut feedback"
```

---

### Task 6: Verifikasi menyeluruh + build

**Files:** tidak ada file baru.

- [ ] **Step 1: Guard suite visual**

Run: `php artisan test --filter "RadiusConsistencyTest|ColorTokenMigrationTest|DarkModeCoverageTest|LayoutDivBalanceTest|IndonesianUiTextTest|StatusBadgeConsistencyTest"`
Expected: PASS semua. Bila DivBalance/Radius gagal, perbaiki markup view yang baru diubah.

- [ ] **Step 2: Suite penuh**

Run: `php artisan config:clear; php artisan test`
Expected: 0 FAIL (≈345+ test).

- [ ] **Step 3: Build asset + vitest**

Run: `npm run build; npm test`
Expected: build sukses (kelas baru terkompilasi: `ring-4`, `ring-primary-100`, `bottom-20`, dst.), vitest PASS.

- [ ] **Step 4: QA visual Playwright (pola sesi ini)**

Buat user kepala + guru + supervisi via tinker (NIK `9900000000000002`, hapus setelahnya), `php artisan cache:clear`, `php artisan serve`, akses `http://localhost:8000` (BUKAN 127.0.0.1). Screenshot: show (langkah 1, light+dark), rubrik (stepper + bar aksi, tombol lanjut disabled→enabled setelah isi semua), feedback (langkah 3), state completed. Bersihkan data + kill server setelahnya.

- [ ] **Step 5: Commit penutup (bila ada perbaikan QA)**

```bash
git add -A
git commit -m "fix: polish QA visual alur evaluasi berpandu"
```
