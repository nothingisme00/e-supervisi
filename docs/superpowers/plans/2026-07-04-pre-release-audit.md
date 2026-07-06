# Pre-Release Audit & Fix Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Audit aplikasi e-supervisi (keamanan, alur kerja, bug, UX), laporkan temuan per tingkat keparahan, lalu perbaiki per kelompok setelah persetujuan pengguna.

**Architecture:** Laravel 11+ monolith, Blade + Livewire, tiga role (admin/guru/kepala_sekolah) dengan gate `isAdmin`/`isGuru`/`isKepalaSekolah`. Alur inti: Guru membuat supervisi → mengisi proses → upload dokumen → submit → Kepala Sekolah/Admin review → feedback/revisi → selesai. Audit membaca kode pada working tree saat ini (branch `develop`, termasuk perubahan belum di-commit).

**Tech Stack:** PHP 8.x, Laravel, PHPUnit 11 (TDD Guard reporter aktif), Livewire, SQLite in-memory untuk test.

## Global Constraints

- Semua temuan dicatat di `docs/superpowers/audit/2026-07-04-findings.md` dengan format seragam (lihat Task 1).
- Tingkat keparahan: **Kritis** (bisa diakses/dirusak lintas user atau data bocor), **Tinggi** (alur rusak atau data salah), **Sedang** (bug fungsional terbatas), **Rendah** (UX/kosmetik).
- Fase audit (Task 1–6) TIDAK mengubah kode aplikasi sama sekali — hanya membaca dan mencatat.
- Fase perbaikan (Task 7) hanya berjalan setelah pengguna menyetujui kelompok temuan; setiap perbaikan perilaku disertai test (TDD: test gagal dulu, lalu implementasi).
- Tidak ada `git commit` tanpa persetujuan pengguna, kecuali commit dokumen audit/plan.
- Pesan UI berbahasa Indonesia.
- Jangan upgrade dependensi besar; hanya patch keamanan yang disetujui pengguna.

---

### Task 1: Baseline — jalankan test suite & siapkan dokumen temuan

**Files:**
- Create: `docs/superpowers/audit/2026-07-04-findings.md`

**Interfaces:**
- Produces: dokumen temuan dengan format entri yang dipakai semua task berikutnya.

- [ ] **Step 1: Jalankan test suite penuh**

Run: `php artisan test 2>&1 | tail -40`
Expected: ringkasan PASS/FAIL. Catat SEMUA test yang gagal (nama test + pesan) — jangan perbaiki apa pun.

- [ ] **Step 2: Buat dokumen temuan dengan format entri baku**

```markdown
# Temuan Audit Pra-Rilis — 2026-07-04

Format entri:
## [SEVERITY] Judul singkat
- **Lokasi:** path/file.php:baris
- **Lapisan:** Keamanan | Alur Kerja | Bug | UX
- **Dampak:** apa yang bisa terjadi
- **Usulan perbaikan:** satu kalimat
- **Status:** ditemukan | disetujui | diperbaiki | dilewati

## Baseline Test Suite
(hasil php artisan test di sini — daftar test gagal bila ada)
```

- [ ] **Step 3: Isi bagian Baseline Test Suite dengan hasil Step 1, lalu commit**

```bash
git add docs/superpowers/audit/2026-07-04-findings.md
git commit -m "docs: baseline audit pra-rilis"
```

---

### Task 2: Audit Keamanan — Lapis 1

**Files:**
- Modify: `docs/superpowers/audit/2026-07-04-findings.md` (tambah temuan)
- Read: `routes/web.php`, semua file di `app/Http/Controllers/`, `app/Http/Middleware/`, `app/Models/`, `app/Livewire/Admin/UserManagement.php`, `config/session.php`, `.env.example`

**Interfaces:**
- Consumes: format entri temuan dari Task 1.
- Produces: entri temuan berlapisan "Keamanan".

- [ ] **Step 1: Audit otorisasi kepemilikan (IDOR) di controller Guru**

Baca `app/Http/Controllers/Guru/SupervisiController.php`, `app/Http/Controllers/Guru/ProsesController.php`, `app/Http/Controllers/Guru/HomeController.php`. Untuk SETIAP method yang menerima `{id}`, jawab: apakah ada pemeriksaan `where('user_id', auth()->id())` / `findOrFail` yang dibatasi kepemilikan / policy? Catat setiap method tanpa pemeriksaan sebagai temuan **Kritis** (contoh judul: "Guru dapat membuka supervisi guru lain via /guru/supervisi/{id}/proses").

- [ ] **Step 2: Audit otorisasi di controller KepalaSekolah dan Admin**

Baca `app/Http/Controllers/KepalaSekolah/EvaluasiController.php`, `app/Http/Controllers/Admin/SupervisiController.php`, `app/Http/Controllers/Admin/UserController.php`, `app/Http/Controllers/Admin/CarouselController.php`. Periksa: (a) apakah kepala sekolah dibatasi hanya melihat supervisi yang sudah disubmit; (b) apakah `downloadDocument` memverifikasi dokumen milik supervisi yang boleh diakses; (c) apakah UserController mencegah admin menonaktifkan/menghapus dirinya sendiri. Catat temuan.

- [ ] **Step 3: Audit upload & download file**

Di `Guru/SupervisiController.php` method `uploadDocument` dan `deleteDocument`, periksa: validasi `mimes`/`max` di server, apakah nama file disanitasi (bukan nama asli user dipakai langsung ke path), apakah file disimpan di `storage/app` (bukan `public/`) dan diakses via route ber-auth. Di kedua `downloadDocument` (Admin & KepalaSekolah), periksa path traversal: apakah path diambil dari DB (aman) atau dari input request (bahaya). Catat temuan.

- [ ] **Step 4: Audit mass assignment & XSS**

Run: `grep -rn "guarded\|fillable" app/Models/`
Periksa `User.php`: apakah `role`, `is_active`, `must_change_password` ada di `$fillable` DAN controller memakai `$request->all()`/`only()` tanpa filter — kombinasi itu = temuan Kritis (eskalasi role).

Run: `grep -rn "{!!" resources/views/ | grep -v "vite\|@json"`
Setiap `{!! !!}` yang merender data input user = temuan **Tinggi** (XSS). Catat lokasinya.

- [ ] **Step 5: Audit middleware, sesi, rate limit, dan headers**

Baca `app/Http/Middleware/SessionTimeout.php`, `MustChangePassword.php`, `PreventBackHistory.php`, `RedirectIfAuthenticated.php`, `config/session.php`. Periksa: timeout wajar, `session()->regenerate()` saat login di `CustomLoginController.php`, logout invalidate session, cookie `secure`/`httponly`. Periksa `routes/web.php`: route `logout` TIDAK di dalam throttle group; route sensitif lain (upload, feedback) apakah perlu throttle. Catat temuan.

- [ ] **Step 6: Audit dependensi**

Run: `composer audit --format=plain 2>&1 | head -60`
Catat hanya advisori pada paket yang benar-benar dipakai runtime produksi (abaikan dev-only kecuali parah). Kelompokkan sebagai temuan dengan severity sesuai CVSS-nya.

- [ ] **Step 7: Commit temuan lapis keamanan**

```bash
git add docs/superpowers/audit/2026-07-04-findings.md
git commit -m "docs: temuan audit lapis keamanan"
```

---

### Task 3: Audit Alur Kerja — Lapis 2

**Files:**
- Modify: `docs/superpowers/audit/2026-07-04-findings.md`
- Read: `app/Models/Supervisi.php`, `database/migrations/2025_10_31_154321_create_supervisi_table.php`, `database/migrations/2025_11_01_083526_update_supervisi_status_enum.php`, controller Guru/Admin/KepalaSekolah yang mengubah status

**Interfaces:**
- Consumes: format entri temuan dari Task 1.
- Produces: peta state machine + entri temuan berlapisan "Alur Kerja".

- [ ] **Step 1: Petakan daftar status resmi**

Run: `grep -rn "status" database/migrations/2025_11_01_083526_update_supervisi_status_enum.php app/Models/Supervisi.php | head -30`
Tulis daftar nilai enum status di dokumen temuan (bagian baru "Peta Status"). Bandingkan dengan nilai yang di-set di controller: `grep -rn "status.*=>\|->status" app/Http/Controllers/ | grep -iv "getStatus"` — setiap nilai yang dipakai controller tapi tidak ada di enum (atau sebaliknya) = temuan **Tinggi**.

- [ ] **Step 2: Uji transisi ilegal per endpoint**

Untuk setiap endpoint pengubah status, jawab dari kode (bukan asumsi): apakah ada guard `if ($supervisi->status !== X) abort/redirect`?
- `Guru/ProsesController::submit` — bisakah submit dua kali? bisakah submit supervisi berstatus selesai?
- `Guru/SupervisiController::destroy` — bisakah guru menghapus supervisi yang sudah disubmit/direview?
- `Guru/SupervisiController::uploadDocument`/`deleteDocument` — bisakah mengubah dokumen setelah submit?
- `KepalaSekolah/EvaluasiController::startReview/giveFeedback/requestRevision/complete` — bisakah complete tanpa melalui review? bisakah review supervisi yang masih draft?
- `Admin/SupervisiController::storeFeedback/requestRevision` — apakah bentrok dengan aksi kepala sekolah (saling menimpa status)?
Setiap transisi tanpa guard = temuan **Tinggi**.

- [ ] **Step 3: Periksa alur revisi ujung-ke-ujung**

Telusuri: setelah `requestRevision`, status jadi apa? Apakah guru bisa mengedit lagi pada status itu (cek guard di ProsesController::save dan uploadDocument)? Setelah guru submit ulang, apakah status kembali ke antrian review? Bila ada jalan buntu (status yang tidak bisa keluar), catat sebagai temuan **Tinggi**.

- [ ] **Step 4: Commit temuan lapis alur kerja**

```bash
git add docs/superpowers/audit/2026-07-04-findings.md
git commit -m "docs: temuan audit lapis alur kerja"
```

---

### Task 4: Audit Bug Umum — Lapis 3

**Files:**
- Modify: `docs/superpowers/audit/2026-07-04-findings.md`
- Read: semua file `M` pada `git status` (controller, middleware, migrasi, seeder, model `CarouselSlide.php`), `app/Helpers/CacheHelper.php`, `app/Services/ImageService.php`

**Interfaces:**
- Consumes: baseline hasil test dari Task 1.
- Produces: entri temuan berlapisan "Bug".

- [ ] **Step 1: Review diff perubahan yang belum di-commit**

Run: `git diff app/ database/ | head -400` (ulangi dengan offset bila terpotong)
Untuk setiap perubahan, jawab: apakah migrasi yang diubah sudah pernah dijalankan di lingkungan lain (mengubah file migrasi lama = bug deploy)? Apakah perubahan controller konsisten dengan view yang memakainya? Catat anomali sebagai temuan (severity sesuai dampak).

- [ ] **Step 2: Cek konsistensi migrasi vs model vs pemakaian**

Untuk `Supervisi`, `DokumenEvaluasi`, `Feedback`, `ProsesPembelajaran`, `CarouselSlide`: bandingkan kolom di migrasi dengan `$fillable` model dan pemakaian di controller/view (`grep -rn "nama_kolom"` untuk kolom yang mencurigakan). Kolom dipakai tapi tidak ada di migrasi = temuan **Tinggi**.

- [ ] **Step 3: Analisis test yang gagal dari baseline**

Untuk setiap test gagal yang dicatat Task 1: tentukan akar masalah (test usang vs bug nyata di kode). Bug nyata = temuan dengan severity sesuai dampak; test usang = temuan **Rendah** ("test perlu diperbarui").

- [ ] **Step 4: Commit temuan lapis bug**

```bash
git add docs/superpowers/audit/2026-07-04-findings.md
git commit -m "docs: temuan audit lapis bug"
```

---

### Task 5: Audit UX — Lapis 4

**Files:**
- Modify: `docs/superpowers/audit/2026-07-04-findings.md`
- Read: `resources/views/guru/**`, `resources/views/kepala/**`, `resources/views/admin/**`, `resources/views/auth/login.blade.php`, `resources/views/layouts/modern.blade.php`

**Interfaces:**
- Consumes: peta alur kerja dari Task 3 (urutan layar mengikuti alur supervisi).
- Produces: entri temuan berlapisan "UX".

- [ ] **Step 1: Telusuri alur Guru dari sisi layar**

Baca view berurutan: `guru/home` → `guru/supervisi/create` → `proses` → upload → submit → `detail`. Periksa per layar: (a) apakah error validasi ditampilkan (`@error` / `$errors`); (b) apakah ada notifikasi sukses setelah aksi (`session('success')`); (c) apakah tombol aksi utama jelas; (d) empty state saat belum ada data. Catat kekurangan sebagai temuan **Rendah** (atau **Sedang** bila menyesatkan pengguna).

- [ ] **Step 2: Telusuri alur Kepala Sekolah dan Admin**

Sama seperti Step 1 untuk `kepala/evaluasi/index+show` dan `admin/dashboard`, `admin/users/*`, `admin/supervisi/*`, `admin/carousel/*`. Tambahan: apakah status supervisi ditampilkan dengan label berwarna yang konsisten di ketiga role?

- [ ] **Step 3: Periksa konsistensi pesan & bahasa**

Run: `grep -rn "session()->flash\|with('success'\|with('error'" app/Http/Controllers/ | head -40`
Periksa: semua pesan berbahasa Indonesia, konsisten nadanya, dan dipasangkan dengan tampilan flash di layout. Catat yang bahasa Inggris/hilang.

- [ ] **Step 4: Commit temuan lapis UX**

```bash
git add docs/superpowers/audit/2026-07-04-findings.md
git commit -m "docs: temuan audit lapis UX"
```

---

### Task 6: Laporan Konsolidasi + Gerbang Persetujuan

**Files:**
- Modify: `docs/superpowers/audit/2026-07-04-findings.md` (rapikan, kelompokkan)

**Interfaces:**
- Consumes: semua entri temuan Task 2–5.
- Produces: laporan final terurut Kritis → Rendah; daftar kelompok perbaikan yang disetujui pengguna.

- [ ] **Step 1: Urutkan & deduplikasi temuan**

Susun ulang dokumen temuan: kelompok Kritis, Tinggi, Sedang, Rendah. Gabungkan temuan duplikat (mis. IDOR yang sama ditemukan di lapis keamanan dan alur kerja). Beri nomor urut (K1, K2, T1, ...).

- [ ] **Step 2: Sajikan laporan ke pengguna di percakapan**

Ringkas per kelompok: jumlah temuan, 1 kalimat per temuan (nomor + judul + lokasi). Tanyakan persetujuan per kelompok: "Perbaiki semua Kritis? semua Tinggi? ..." — tunggu jawaban pengguna. Tandai entri yang disetujui dengan Status: disetujui.

- [ ] **Step 3: Commit laporan final**

```bash
git add docs/superpowers/audit/2026-07-04-findings.md
git commit -m "docs: laporan audit final + persetujuan perbaikan"
```

---

### Task 7: Fase Perbaikan (per kelompok yang disetujui)

**Files:**
- Modify: ditentukan oleh temuan yang disetujui (lokasi ada di tiap entri temuan).
- Test: file test yang relevan di `tests/Feature/**` (mis. `tests/Feature/Guru/SupervisiFlowTest.php` untuk guard alur, `tests/Feature/RouteAccessTest.php` untuk otorisasi).

**Interfaces:**
- Consumes: entri temuan berstatus "disetujui" dari Task 6.
- Produces: kode diperbaiki + test hijau; entri temuan diperbarui ke Status: diperbaiki.

Karena isi perbaikan bergantung pada temuan, setiap temuan disetujui dikerjakan dengan siklus baku berikut (WAJIB, TDD Guard aktif):

- [ ] **Step 1: Tulis test yang mereproduksi masalah (harus GAGAL)**

Contoh pola untuk temuan IDOR (sesuaikan nama/route dengan temuan nyata):

```php
public function test_guru_tidak_bisa_membuka_supervisi_guru_lain(): void
{
    $guruA = User::factory()->create(['role' => 'guru']);
    $guruB = User::factory()->create(['role' => 'guru']);
    $supervisiB = Supervisi::factory()->create(['user_id' => $guruB->id]);

    $response = $this->actingAs($guruA)
        ->get(route('guru.supervisi.proses', $supervisiB->id));

    $response->assertStatus(403);
}
```

Run: `php artisan test --filter=nama_test`
Expected: FAIL (perilaku bug masih ada).

- [ ] **Step 2: Implementasi perbaikan minimal**

Ubah hanya file di lokasi temuan. Pola umum per jenis temuan:
- IDOR: `$supervisi = Supervisi::where('user_id', auth()->id())->findOrFail($id);`
- Guard status: `abort_unless(in_array($supervisi->status, ['draft', 'revisi']), 403);`
- XSS: ganti `{!! $x !!}` → `{{ $x }}` (atau sanitasi bila memang butuh HTML).
- Pesan UX: tambah `->with('success', 'Pesan bahasa Indonesia')` + blok flash di view.

- [ ] **Step 3: Jalankan test — harus PASS, lalu jalankan suite penuh**

Run: `php artisan test --filter=nama_test` → PASS
Run: `php artisan test` → tidak ada regresi baru dibanding baseline Task 1.

- [ ] **Step 4: Perbarui status temuan di dokumen audit**

Ubah `Status: disetujui` → `Status: diperbaiki` pada entri terkait.

- [ ] **Step 5: Commit per kelompok severity (setelah semua temuan kelompok itu selesai)**

```bash
git add -A
git commit -m "fix: perbaikan temuan audit kelompok <Kritis|Tinggi|Sedang|Rendah>"
```

- [ ] **Step 6: Verifikasi akhir setelah semua kelompok selesai**

Run: `php artisan test`
Expected: PASS penuh (atau sama/lebih baik dari baseline dengan penjelasan). Laporkan ringkasan akhir ke pengguna: temuan diperbaiki vs dilewati.
