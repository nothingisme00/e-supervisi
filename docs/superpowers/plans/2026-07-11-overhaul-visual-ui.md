# Overhaul Visual/UX E-Supervisi — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Merapikan desain seluruh aplikasi (landing/login, dashboard 3 peran, supervisi & evaluasi, modul & notifikasi) menjadi profesional, konsisten, dan berhirarki benar — tanpa mengubah perilaku PHP.

**Architecture:** Foundation-first: bangun kosakata komponen (x-icon, x-page-header, x-button, x-form.*, x-card) + satu sumber token di `resources/css/app.css`, lalu adopsi per area. Login/change-password dimigrasi dari Tailwind CDN ke build Vite. Invarian global (status, radius) ditegakkan lewat guard test repo-wide (pola `ColorTokenMigrationTest` yang sudah ada). Semua perubahan Blade/CSS/JS-view saja.

**Tech Stack:** Laravel 12 Blade, Tailwind v4 CSS-first (`@theme` di app.css, TANPA tailwind.config.js), Vite, Livewire 3 + Alpine (sudah ada), PHPUnit + vitest, Playwright MCP untuk screenshot.

## Global Constraints

- **Arah estetika: PROFESIONAL & TENANG.** Depth hanya `border` + `shadow-sm`; TANPA gradient dekoratif baru (yang ada dihapus/diratakan jadi flat); animasi hanya transisi fungsional halus (dropdown/hover); warna non-teal hanya untuk makna (status/peringatan/jenis notifikasi), tidak pernah hiasan. Kalau ragu antara "lebih menarik" vs "lebih konsisten" → pilih konsisten.
- **Palet:** nilai token `--color-primary-*` (teal) di `resources/css/app.css` TIDAK berubah. DILARANG kata/hex indigo, purple, violet di view & CSS (`ColorTokenMigrationTest`).
- **Radius:** kartu/panel/dropdown = `rounded-xl`; tombol/input/select = `rounded-lg`; badge/pill/avatar = `rounded-full`; blok hero maks `rounded-2xl`. `rounded-md`/`rounded-3xl` dilarang di view — dinormalisasi repo-wide + guard test di Task 23.
- **Kartu kanonik = komponen `x-card`** (render: `bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm`). Kode baru/tersentuh WAJIB pakai `x-card`, bukan menulis ulang string kelasnya; sisa string tulisan-tangan dimigrasi di Task 23.
- **Status:** `x-status-badge` = satu-satunya sumber warna+label status: draft=gray, submitted=blue, under_review=amber, revision=red, completed=emerald, aktif=emerald, nonaktif=gray. Label test-enforced: Disubmit/Ditinjau/Revisi/Selesai (JANGAN "Direview"/"Telah Ditinjau"). Pill status bespoke di luar komponen dilarang — guard test repo-wide dibuat di Task 4.
- **Ikon:** SVG inline saja (gaya Heroicons outline 24×24 stroke) via `x-icon`. Tanpa emoji. Material Symbols dipensiunkan per halaman; `<link>` dicabut hanya di Task 23.
- **Dark mode:** setiap view yang punya `bg-white` wajib punya varian `dark:` (`DarkModeCoverageTest`). Kontras teks ≥4.5:1. Touch target ≥44px.
- **JANGAN ubah:** copy yang di-assert test (mis. `Pengingat pengisian supervisi`, footer auth `E-Supervisi · Sistem Supervisi Pembelajaran`), id `notif-dropdown-btn`, atribut `data-notif-badge`, nama field form (mis. `skor[{id}]`), hook JS rubrik (`rubrikGoToStep`, `data-rubrik-step*`), `rubrik-pdf.blade.php`, `resources/js/bootstrap.js` (itu axios).
- **Tanpa perubahan perilaku PHP** (route/controller/composer/FormRequest/Notification). Pengecualian: (a) `resources/js/modul-reader.js` di Task 20 via TDD vitest (RED dulu); (b) test PHPUnit BARU sebagai guard (Task 4 & 23) — test selalu boleh, tulis RED dulu sesuai TDD-guard.
- **Div balance:** `LayoutDivBalanceTest` menghitung `<div>`/`</div>` di 3 dashboard — hitung sebelum commit.
- **Verifikasi per task:** `php artisan config:clear` WAJIB sebelum test (insiden DB terhapus), lalu test terarah. `npm run build` bila task mengubah kelas Tailwind yang akan dicek via browser. Full suite di akhir tiap fase (Task 5, 7, 12, 18, 23).
- Commit per task di branch `develop`, pesan bahasa Indonesia berprefiks feat/fix/refactor/style/chore.

## Checklist UI per task (disaring dari ui-ux-pro-max; berlaku untuk SETIAP view yang disentuh)

1. Elemen klik punya `cursor-pointer` + state hover/focus terlihat (`focus-visible:ring-2 ring-primary-500`) — jangan pernah menghapus focus ring.
2. Transisi hover/dropdown 150–300ms, `transform`/`opacity` saja (tanpa animasi width/height); keluar lebih cepat daripada masuk; tanpa animasi dekoratif.
3. Ikon: SATU keluarga (x-icon, stroke 1.5, outline), ukuran dari skala tetap (`w-4/5/6`), sejajar baseline teks; ikon-saja wajib `aria-label`.
4. Hirarki heading sekuensial (h1 → h2 → h3, tanpa loncat); satu h1 per halaman (via x-page-header); hirarki visual lewat ukuran+spacing+kontras, bukan warna saja.
5. Teks: body min 16px di mobile (`text-base`), sekunder min `text-xs`; angka dalam tabel/statistik pakai `tabular-nums`; kontras 4.5:1 dicek di KEDUA tema.
6. Form: label terlihat (bukan placeholder-only), error di bawah field terkait, tipe input semantik (email/tel/number), tinggi kontrol ≥44px.
7. State disabled: `disabled:opacity-50` + non-interaktif; state loading tombol submit bila sudah ada polanya di halaman itu (jangan tambah perilaku baru).
8. Empty state selalu pakai `<x-empty-state>` — pesan + arah aksi, bukan halaman kosong.
9. Warna tak pernah jadi satu-satunya penanda makna (selalu + ikon/label) — badge status sudah menangani ini via label.
10. Validasi anti-"ornate": tanpa ornamen, glow, bayangan tebal, atau dekorasi yang tidak menyampaikan informasi.

---

## Fase 0 — Fondasi

### Task 1: Inter self-hosted + dokumentasi konvensi di app.css

**Files:**
- Create: `public/fonts/inter/InterVariable.woff2` (+ `InterVariable-Italic.woff2` bila tersedia)
- Modify: `resources/css/app.css`

**Steps:**
- [ ] Unduh InterVariable.woff2 dari https://rsms.me/inter/font-files/InterVariable.woff2 (dan Italic dari https://rsms.me/inter/font-files/InterVariable-Italic.woff2) ke `public/fonts/inter/`. Bila unduhan gagal total, fallback: tambah `<link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap">` di `layouts/modern.blade.php` dan laporkan deviasi.
- [ ] Di `app.css` (sebelum `@theme`): blok `@font-face` untuk `Inter` (font-family "Inter", src url('/fonts/inter/InterVariable.woff2') format('woff2'), `font-weight: 100 900`, `font-display: swap`, `font-style: normal`; blok kedua italic bila file ada).
- [ ] Ubah `--font-sans` menjadi: `"Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif`.
- [ ] Tambah blok komentar dokumentasi di atas `@theme`: skala radius (kartu=xl, tombol/input=lg, pill=full, hero maks 2xl), kanon warna status (lihat Global Constraints), konvensi merah `red-*` = aksen video praktik (bukan token).
- [ ] `npm run build` sukses; cek Inter ter-render (computed font-family di satu halaman authed).
- [ ] Commit: `style: font Inter self-hosted + dokumentasi konvensi desain di app.css`

### Task 2: Cabut dependency mati (Bootstrap/Sass/Popper)

**Files:**
- Modify: `package.json`, `resources/js/bootstrap.js` (HANYA hapus baris mati `import 'bootstrap';` — wajib agar build tidak gagal resolve; setup axios di bawahnya utuh)
- Delete: seluruh folder `resources/sass/`

**Steps:**
- [ ] Verifikasi dulu: `git grep -il "popper"` (kecuali package-lock) dan `git grep -l "sass"` di resources/vite.config — pastikan tak ada konsumen nyata.
- [ ] Hapus dari devDependencies: `bootstrap`, `sass`, `@popperjs/core`. JANGAN hapus `concurrently`, `axios`, `laravel-vite-plugin`, `tailwindcss`, `@tailwindcss/vite`, `pdfjs-dist`, vitest deps. JANGAN sentuh `resources/js/bootstrap.js` (itu axios bootstrap Laravel).
- [ ] Hapus folder `resources/sass/`.
- [ ] `npm install` (perbarui lockfile), `npm run build`, `npm test` — semua sukses.
- [ ] Commit: `chore: cabut dependency mati bootstrap+sass+popper (app murni Tailwind v4)`

### Task 3: Perpustakaan komponen dasar (x-icon, x-page-header, x-button, x-form.*, x-card) + yield page-title

**Files:**
- Create: `resources/views/components/icon.blade.php`, `resources/views/components/page-header.blade.php`, `resources/views/components/button.blade.php`, `resources/views/components/form/field.blade.php`, `resources/views/components/form/input.blade.php`, `resources/views/components/form/select.blade.php`, `resources/views/components/form/textarea.blade.php`, `resources/views/components/card.blade.php`
- Modify: `resources/views/layouts/modern.blade.php` (baris `<title>` saja), `resources/views/components/empty-state.blade.php` (delegasi ikon), `resources/css/app.css` (`@layer components`)

**Steps:**
- [ ] `icon.blade.php`: props `name` (wajib) + `class` merge (default `w-5 h-5`). Registry `@switch($name)` SVG inline 24×24 `fill="none" stroke="currentColor" stroke-width="1.5"` gaya Heroicons outline: `bell`, `arrow-left`, `chevron-down`, `book-open`, `chat-bubble`, `exclamation-triangle`, `star`, `clipboard-check`, `clock`, `eye`, `document`, `check-circle`, `x-mark`, `plus`, `search`, `users`, `pencil`, `trash`, `home`, `inbox`, `check` — HARUS mencakup semua nilai `icon` yang dipakai `empty-state` (inbox/document/users/search/clock/check). Default case: ikon `bell`. Docblock contoh pemakaian.
- [ ] **Refactor `empty-state.blade.php`**: buang switch SVG internal (baris ±12-36) → `<x-icon :name="$icon" class="...">`; satu sumber ikon, dan nilai `icon` apa pun tak pernah render kosong (default registry).
- [ ] `page-header.blade.php`: props `title` (wajib), `subtitle` (opsional), `backUrl` (opsional → link `x-icon arrow-left` + teks "Kembali"), slot `actions` (opsional, rata kanan). h1 = `text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100`; subtitle `text-sm text-gray-500 dark:text-gray-400 mt-1`. Wrapper `mb-6 flex flex-wrap items-start justify-between gap-3`.
- [ ] Di `layouts/modern.blade.php` ganti baris `<title>` menjadi: `<title>@hasSection('page-title')@yield('page-title') · @endif{{ config('app.name', 'E-Supervisi') }}</title>` (semua view sudah set `page-title` — langsung berfungsi tanpa pilot).
- [ ] `button.blade.php`: props `variant` (primary|secondary|danger|ghost, default primary), `size` (sm|md, default md), `href` (opsional → render `<a>`, selain itu `<button>` type merge default `button`). Base: `inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 disabled:opacity-50 disabled:pointer-events-none`. md = `px-4 py-2.5 text-sm min-h-[44px]`; sm = `px-3 py-1.5 text-sm`. primary=`bg-primary-600 hover:bg-primary-700 text-white`; secondary=`border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700`; danger=`bg-red-600 hover:bg-red-700 text-white`; ghost=`text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700`.
- [ ] Styling field SEKALI di `app.css`: `@layer components { .form-control { @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500; } }`.
- [ ] `form/field.blade.php` = wrapper TUNGGAL label/error/hint (props `label`, `name`, `error` fallback `$errors->first($name)`, `hint`; label `block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5`; error `text-sm text-red-600 dark:text-red-400 mt-1`; `{{ $slot }}` = kontrolnya). `form/input|select|textarea.blade.php` = tipis: bungkus `<x-form.field>` + elemen kontrol ber-`class="form-control"` + atribut merge. Logika label/error TIDAK diduplikasi tiga kali.
- [ ] `card.blade.php`: prop `flush` (bool, default false → tambah `overflow-hidden`). Kelas kartu kanonik; `{{ $slot }}`.
- [ ] Verifikasi: render semua komponen via `php artisan tinker` + `Blade::render()` tanpa error; `npm run build`; `php artisan config:clear && php artisan test --filter="DarkModeCoverageTest|ColorTokenMigrationTest"` hijau; buka satu halaman → tab browser menampilkan "«judul» · E-Supervisi".
- [ ] Commit: `feat: perpustakaan komponen dasar (icon, page-header, button, form, card) + judul tab`

### Task 4: Status satu sumber + guard test repo-wide

**Files:**
- Create: `tests/Feature/StatusPillSingleSourceTest.php`
- Modify: `resources/views/components/status-badge.blade.php`, `resources/views/admin/supervisi/index.blade.php` (±25-54 pill status saja), `resources/views/admin/carousel/index.blade.php` (±53 pill aktif/nonaktif bespoke green/gray)

**Steps:**
- [ ] Perluas map `status-badge.blade.php`: `aktif` → emerald + label "Aktif"; `nonaktif` → gray + label "Nonaktif". Label lama persis dipertahankan (Disubmit/Ditinjau/Revisi/Selesai/Draft).
- [ ] **Guard test dulu (RED)**: `StatusPillSingleSourceTest` — pola `ColorTokenMigrationTest` (`File::allFiles(resource_path('views'))`): tidak ada view di luar `components/status-badge.blade.php` yang mengandung kombinasi kelas pill status bespoke (regex kombinasi `bg-(green|amber|blue|emerald|rose)-100` berdampingan dengan teks status Aktif/Nonaktif/Disubmit/Ditinjau/dsb dalam elemen yang sama). Jalankan → RED (pelanggar: admin/carousel, admin/dashboard, kepala/evaluasi/index, admin/modul/index).
- [ ] Ganti pill hardcoded dengan `<x-status-badge>` HANYA di `admin/supervisi/index.blade.php` + `admin/carousel/index.blade.php` (file yang tak punya task rewrite sendiri). Pelanggar lain diperbaiki oleh task pemilik file-nya (Task 11 admin/dashboard, Task 16 kepala/evaluasi/index, Task 21 admin/modul) — guard test menjadi hijau penuh saat file-file itu selesai; sampai saat itu tandai daftar pelanggar tersisa di test sebagai whitelist SEMENTARA berkomentar `TODO Task N` yang dikosongkan bertahap.
- [ ] `php artisan config:clear && php artisan test --filter="StatusPillSingleSourceTest|StatusBadgeConsistencyTest|IndonesianUiTextTest"` hijau.
- [ ] Commit: `fix: status pill satu sumber via x-status-badge + guard test repo-wide`

### Task 5: Sweep gradient palsu repo-wide + CTA empty-state + chevron

**Files:**
- Modify (footprint grep NYATA, 13 file): `resources/views/guru/home.blade.php`, `resources/views/livewire/admin/user-management.blade.php`, `resources/views/layouts/modern.blade.php`, `resources/views/admin/dashboard.blade.php`, `resources/views/admin/supervisi/detail.blade.php`, `resources/views/guru/supervisi/create.blade.php`, `resources/views/guru/supervisi/detail.blade.php`, `resources/views/guru/supervisi/view.blade.php`, `resources/views/kepala/dashboard.blade.php`, `resources/views/kepala/evaluasi/show.blade.php`, `resources/views/components/empty-state.blade.php`, `resources/views/components/custom-dropdown.blade.php`
- Catatan: task per-halaman selanjutnya BOLEH me-restyle area yang sama — sweep ini hanya menghilangkan markup bohong, cepat per file.

**Steps:**
- [ ] `git grep -nE "from-([a-z]+-[0-9]+)( via-[^ ]+)? to-\1"` → ganti semua `bg-gradient-to-* from-X to-X` (start=end identik) dengan `bg-X` flat. Render identik; markup jujur.
- [ ] `empty-state.blade.php`: CTA anchor bespoke → `<x-button href="{{ ... }}" variant="primary">` (+ `x-icon` bila ada ikon) — komponen dari Task 3, jangan tambal warna manual.
- [ ] `custom-dropdown.blade.php`: glyph Material Symbols `expand_more` → `<x-icon name="chevron-down" class="w-4 h-4">`.
- [ ] `git grep -E "from-([a-z]+-[0-9]+) to-\1"` → kosong. **Full suite fase 0**: `npm run build` + `npm test` + `php artisan config:clear && php artisan test` hijau.
- [ ] Commit: `fix: hapus semua gradient palsu (13 file), CTA empty-state via x-button, chevron SVG`

---

## Fase 1 — Landing/Login (view-only; `CustomLoginController` TIDAK disentuh)

### Task 6: Migrasi login ke build Vite (+ layouts/auth + theme-init)

**Files:**
- Create: `resources/views/layouts/auth.blade.php`, `resources/views/partials/theme-init.blade.php`
- Modify: `resources/views/auth/login.blade.php` (613 baris — rework head + token; struktur body dipertahankan)

**Steps:**
- [ ] `theme-init.blade.php`: script inline anti-flash — `localStorage.theme` menang; bila kosong ikut `window.matchMedia('(prefers-color-scheme: dark)')`; set/hapus kelas `dark` di `document.documentElement` sebelum paint. (Adopsi di `layouts/modern.blade.php` dilakukan Task 8 — file itu wilayah task shell.)
- [ ] `layouts/auth.blade.php`: dokumen HTML lengkap — `<html lang="id">`, include theme-init, `<title>` pola sama Task 3, `@vite(['resources/css/app.css','resources/js/app.js'])`, TANPA link Material Symbols/Google Fonts/CDN, `@yield('content')`, include `resources/views/auth/partials/footer.blade.php`, `@stack('styles')` + `@stack('scripts')`. Layout ini langsung teruji oleh konsumen pertamanya (login) dalam task yang sama.
- [ ] `login.blade.php` — PERTAHANKAN: split-screen (carousel kiri / form kanan), JS carousel + dots + interval 5 detik, data `$carouselSlides` + slide fallback hardcoded, label toggle tema Indonesia, semua `name` atribut form + CSRF + route.
- [ ] GANTI: `@extends('layouts.auth')`; hapus `<script src="https://cdn.tailwindcss.com">` + config token inline + link Google Fonts (Inter sudah dari app.css); map token bespoke → token app (`background-light`→`gray-50`, `background-dark`→`gray-900`, `card-*`→`white`/`gray-800`, `#0F766E`/`teal-600`→`primary-600` dst); glyph Material Symbols (`badge`, `lock`, `visibility`, `light_mode`) → `x-icon` (tambah nama baru ke registry bila perlu: `eye-slash`, `sun`, `moon`, `lock`, `id-card`).
- [ ] FIX HIRARKI: `<h1>` = "E-Supervisi" branding `text-2xl lg:text-3xl font-bold`; headline carousel didemosikan ke `text-2xl lg:text-3xl` (dari 5xl); hapus efek `bg-clip-text` gradient slide fallback → teks putih biasa.
- [ ] CSS khusus carousel pindah ke `@push('styles')` di halaman (bukan app.css).
- [ ] Verifikasi: `git grep -n "cdn.tailwindcss\|fonts.googleapis" resources/views/auth/login.blade.php` → kosong; `php artisan config:clear && php artisan test --filter="AuthPageFooterTest|IndonesianUiTextTest"` hijau; Playwright: login guru berhasil; screenshot 375/1440 × light/dark.
- [ ] Commit: `refactor: migrasi login dari Tailwind CDN ke Vite (layout auth + theme-init) + fix hirarki`

### Task 7: Migrasi change-password + checkpoint fase 1

**Files:**
- Modify: `resources/views/auth/change-password.blade.php`

**Steps:**
- [ ] Perlakuan sama Task 6: extend `layouts.auth`, hapus CDN/config/fonts, map token, Material Symbols → x-icon, adopsi `x-form.input` + `x-button`. JS strength-meter password dipertahankan (sesuaikan selector bila markup berubah).
- [ ] Playwright: alur ganti password (user `must_change_password`) jalan; screenshot light/dark.
- [ ] **Full suite fase 1**: `php artisan config:clear && php artisan test` + `npm run build` hijau.
- [ ] Commit: `refactor: migrasi halaman ganti-password ke build Vite + komponen form`

---

## Fase 2 — Shell app + dashboard

### Task 8: Polish shell modern.blade.php (surgical) + adopsi theme-init

**Files:**
- Modify: `resources/views/layouts/modern.blade.php`

**Steps:**
- [ ] Ganti script anti-flash inline (baris ±11-18) dengan `@include('partials.theme-init')` (partial dari Task 6; perilaku default kini ikut prefers-color-scheme — dikehendaki).
- [ ] Topbar: SVG lonceng di tombol `#notif-dropdown-btn` dan link sidebar Notifikasi → `<x-icon name="bell">` (id/atribut JANGAN berubah); panel dropdown notif + profil seragam `rounded-xl` + transisi buka/tutup halus (opacity+scale, JS toggle existing).
- [ ] Sidebar: tinggi item nav ≥44px, state aktif konsisten (`bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400`).
- [ ] Header profile-dropdown: rapikan tipografi/spacing (gradient palsu sudah dibereskan Task 5).
- [ ] Footer & bottom-nav mobile: audit touch target ≥44px; copy footer TIDAK berubah.
- [ ] Hitung div balance sebelum commit. `php artisan config:clear && php artisan test --filter="LayoutDivBalanceTest|LoncengTopbar|LayoutStickyFooterTest|DarkModeCoverageTest"` hijau; `npm run build`; screenshot topbar+sidebar light/dark 375/1440.
- [ ] Commit: `style: polish shell — theme-init bersama, ikon lonceng x-icon, transisi dropdown, touch target`

### Task 9: Guru home

**Files:**
- Create: `resources/views/guru/_supervisi-card.blade.php`
- Modify: `resources/views/guru/home.blade.php` (789 baris)

**Steps:**
- [ ] `<x-page-header title="Beranda" subtitle="...">` (halaman ini belum punya h1).
- [ ] Kartu timeline supervisi (±94-369) diekstrak ke partial `_supervisi-card.blade.php` (param: `$supervisi`, flag `$milikSendiri`): header avatar+nama+badge, chip info Dokumen/Proses/Video/Feedback, accordion komentar, footer aksi status-dependent — pakai `x-card`, `x-status-badge`, `x-button`, `x-icon`.
- [ ] Hero/greeting & strip kartu: FLAT `bg-primary-600` atau netral (gradient palsu sudah flat sejak Task 5 — di sini rapikan komposisinya). Kartu stat → `x-card`.
- [ ] **SEMUA Material Symbols di file ini** → `x-icon`: modal welcome DAN modal supervisi (glyph ±733) — jangan tinggalkan sisa untuk closeout.
- [ ] Tips accordion: restyle konsisten (chevron `x-icon`, radius xl).
- [ ] Div balance dicek. `php artisan config:clear && php artisan test --filter="IndonesianUiTextTest|StatusBadgeConsistencyTest|LayoutDivBalanceTest|DarkModeCoverageTest"` hijau; `npm run build`; screenshot 375/1440 × light/dark.
- [ ] Commit: `refactor: guru home — page header, partial _supervisi-card, hero flat, ikon seragam`

### Task 10: Kepala sekolah dashboard

**Files:**
- Modify: `resources/views/kepala/dashboard.blade.php` (252 baris)

**Steps:**
- [ ] `<x-page-header>` menggantikan header ikon+h3.
- [ ] 3 kartu kanban (Perlu Review/Sedang Ditinjau/Telah Selesai) → `x-card`; strip atas → flat warna makna status (`h-1 bg-amber-500` dst); mini-kartu guru dirapikan (spacing konsisten, tombol → `x-button` sm).
- [ ] Div balance dicek; test filter sama Task 9 + `npm run build` + screenshot.
- [ ] Commit: `style: dashboard kepala sekolah — page header + kartu kanban konsisten`

### Task 11: Admin dashboard

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php` (238 baris)

**Steps:**
- [ ] Perlakuan sama Task 10: `x-page-header`, tile quick-action → `x-card` + `x-icon`, kanban konsisten.
- [ ] **Pill status hardcoded (±163-167) → `<x-status-badge>`** (bagian rencana Task 4 yang dititipkan ke sini — hapus entri file ini dari whitelist sementara `StatusPillSingleSourceTest`).
- [ ] Copy tombol modal panduan TIDAK berubah (test).
- [ ] Div balance + `php artisan test --filter="...|StatusPillSingleSourceTest"` + `npm run build` + screenshot.
- [ ] Commit: `style: dashboard admin — page header + kanban + status badge satu sumber`

### Task 12: Admin user management (Livewire) + checkpoint fase 2

**Files:**
- Modify: `resources/views/livewire/admin/user-management.blade.php` (542 baris), `resources/views/admin/users/create.blade.php`, `resources/views/admin/users/edit.blade.php`

**Steps:**
- [ ] SEMUA atribut `wire:*` dan struktur root tunggal Livewire dipertahankan persis. Dropdown Alpine existing dipertahankan (hanya restyle kelas).
- [ ] Adopsi `x-page-header`, `x-card`, `x-button`, `x-form.*` di ketiga file; Material Symbols di create/edit → `x-icon`; tabel/list user dirapikan (badge role konsisten).
- [ ] **Full suite fase 2**: `php artisan config:clear && php artisan test` + `npm test` + `npm run build`; Playwright: buat user + cari + edit via UI; screenshot dashboard 3 peran 375/768/1440 × light/dark.
- [ ] Commit: `style: manajemen user admin — adopsi komponen, wire: utuh`

---

## Fase 3 — Supervisi & evaluasi

### Task 13: Partial _feedback-thread (dedupe 3 arah) + penyatuan detail guru/kepala

**Files:**
- Create: `resources/views/supervisi/_feedback-thread.blade.php`
- Modify: `resources/views/guru/supervisi/detail.blade.php` (dedupe ±235-448), `resources/views/kepala/evaluasi/show.blade.php` (dedupe ±271-448), `resources/views/guru/supervisi/view.blade.php` (salinan ketiga ±206-310 — ikut dedupe, mode readonly)

**Steps:**
- [ ] Partial props: `$feedbacks`, `$supervisi`, `$readonly` (bool, default false). Isi: kartu komentar ber-aksen kiri (merah=permintaan revisi, primary=milik sendiri, amber=lainnya), badge peran, tombol Balas + form reply (disembunyikan bila `$readonly`), balasan nested `ml-6 border-l-2`. Komponen: `x-icon`, `x-button`.
- [ ] TIGA halaman meng-include partial (`view.blade.php` dengan `:readonly="true"`); markup duplikat dihapus; tombol aksi spesifik-peran tetap di halaman masing-masing.
- [ ] Form reply tetap POST ke route existing dengan nama field sama.
- [ ] `php artisan config:clear && php artisan test --filter="Feedback|Supervisi"` hijau; Playwright: kepsek kirim feedback → guru balas → kedua sisi identik; screenshot.
- [ ] Commit: `refactor: thread feedback jadi partial bersama guru+kepala+view (dedupe 3 salinan)`

### Task 14: Rebuild admin supervisi detail

**Files:**
- Modify: `resources/views/admin/supervisi/detail.blade.php` (326 baris)

**Steps:**
- [ ] Hapus background gradient full-page `from-slate-50 via-blue-50 to-primary-50` dan seluruh palet `slate-*` → sistem `gray-*`/`primary-*` standar.
- [ ] Kartu strip-warna ad-hoc → `x-card` + `<x-card-header>` (komponen existing).
- [ ] Bagian feedback → include `supervisi/_feedback-thread` dengan `:readonly="true"` (JANGAN tambah form reply admin — perubahan perilaku).
- [ ] `php artisan config:clear && php artisan test --filter="Admin|DarkModeCoverageTest"` hijau; `npm run build`; screenshot — paritas visual dengan detail guru/kepala.
- [ ] Commit: `refactor: detail supervisi admin disatukan ke sistem visual standar (readonly thread)`

### Task 15: Redesign rubrik penilaian (47 aspek)

**Files:**
- Modify: `resources/views/kepala/evaluasi/rubrik.blade.php` (128 baris)

**Steps:**
- [ ] PERTAHANKAN: nama field `skor[{id}]`, fungsi/atribut JS step (`rubrikGoToStep`, `data-rubrik-step*`), sticky progress bar, alur submit.
- [ ] Kelompok aspek → kartu ber-`<x-card-header>` (bukan border kiri tipis); antar baris aspek `divide-y` + padding vertikal cukup.
- [ ] Radio pill 3-pilihan → segmented control: label ≥44px, state checked jelas (pola `peer-checked:bg-primary-600 peer-checked:text-white`), angka + label pendek, keyboard-accessible (radio asli `sr-only` + focus-visible ring di label).
- [ ] Nav step: hitungan terisi per bagian (client-side dari radio checked — JS kecil di view).
- [ ] Ikon `arrow_back` Material Symbols → `x-icon arrow-left`.
- [ ] `php artisan config:clear && php artisan test --filter="Rubrik"` hijau; Playwright: isi skor → submit → tersimpan; `npm run build`; screenshot 375/1440 light/dark.
- [ ] Commit: `style: rubrik penilaian — kelompok ber-kartu, segmented control ≥44px, hitungan per langkah`

### Task 16: Legibilitas kepala evaluasi index

**Files:**
- Modify: `resources/views/kepala/evaluasi/index.blade.php` (211 baris)

**Steps:**
- [ ] Semua `text-[8px]`/`text-[10px]` → minimal `text-xs`.
- [ ] **Pill status (±133-141) → `<x-status-badge>`** (titipan Task 4 — hapus file ini dari whitelist `StatusPillSingleSourceTest`).
- [ ] Dropdown filter statis vanilla-JS (±39-82) → **komponen existing `<x-custom-dropdown>`** (`resources/views/components/custom-dropdown.blade.php` — sudah Alpine, click-outside, hidden input; kelas CSS-nya sama persis dengan markup statis yang diganti). JANGAN hand-roll Alpine baru. Parameter GET sama.
- [ ] `x-page-header` (h1 existing dipindah ke komponen); baris kartu daftar dirapikan (aksen kiri status dipertahankan, spacing naik).
- [ ] `php artisan config:clear && php artisan test --filter="Evaluasi|StatusBadgeConsistencyTest|StatusPillSingleSourceTest"` hijau (assertDontSee('Direview') lolos); Playwright: filter status bekerja; `npm run build`; screenshot.
- [ ] Commit: `fix: legibilitas daftar evaluasi kepsek — teks min text-xs, badge & filter via komponen`

### Task 17: Dedup guru dokumen/evaluasi

**Files:**
- Modify: `resources/views/guru/supervisi/evaluasi.blade.php` (783 baris)

**Steps:**
- [ ] Ganti dual markup (kartu mobile 63-175 + tabel desktop 180-289) dengan SATU pola: daftar baris kartu responsif untuk semua ukuran — satu loop, satu sumber markup.
- [ ] Aksi per baris: 1 tombol primer (upload/ganti) + aksi sekunder ikon ber-label (`x-button` ghost sm + `x-icon`), semua ≥44px.
- [ ] Semua form upload/hapus tetap ke route existing dengan field sama.
- [ ] `php artisan config:clear && php artisan test --filter="Dokumen|Guru"` hijau; Playwright: upload → preview → hapus di 375px & 1440px; `npm run build`; screenshot.
- [ ] Commit: `refactor: daftar dokumen guru satu pola responsif (hapus duplikasi tabel/kartu)`

### Task 18: My-supervisi + form create/proses/view + checkpoint fase 3

**Files:**
- Modify: `resources/views/guru/my-supervisi.blade.php` (463 baris), `resources/views/guru/supervisi/create.blade.php`, `resources/views/guru/supervisi/proses.blade.php`, `resources/views/guru/supervisi/view.blade.php`

**Steps:**
- [ ] `my-supervisi.blade.php`: adopsi partial `guru/_supervisi-card` dari Task 9 (satu bahasa visual dengan home); progress bar + stat tile dipertahankan di atas daftar.
- [ ] `create/proses`: adopsi `x-form.*` + `x-button`; 5 blok refleksi textarea di proses diberi struktur berulang rapi (kartu ber-nomor, char counter tetap).
- [ ] `view.blade.php`: samakan komponen dengan `detail.blade.php` — thread feedback SUDAH via partial sejak Task 13, jangan sentuh lagi; sisanya `x-card`/`x-page-header`/ikon.
- [ ] Semua `x-page-header`. Field & route tidak berubah.
- [ ] **Full suite fase 3**: `php artisan config:clear && php artisan test` + `npm test` + `npm run build`; Playwright E2E: guru buat supervisi → isi proses → submit → kepsek review + rubrik → guru lihat hasil; screenshot permukaan fase 3.
- [ ] Commit: `style: my-supervisi & form guru — satu bahasa visual dengan beranda`

---

## Fase 4 — Modul + notifikasi + closeout

### Task 19: Notifikasi — ikon per jenis + grup tanggal + page header

**Files:**
- Create: `resources/views/notifikasi/_icon.blade.php`
- Modify: `resources/views/notifikasi/_item.blade.php`, `resources/views/notifikasi/index.blade.php`

**Steps:**
- [ ] `_icon.blade.php`: baca `$ikon` → chip `w-9 h-9 rounded-full flex items-center justify-center` + `<x-icon>`: `modul`→book-open/`bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400`; `feedback`→chat-bubble/blue; `revisi`→exclamation-triangle/red; `nilai`→star/emerald; `review`→clipboard-check/amber; `pengingat`→clock/amber; default→bell/gray. (Payload lama mungkin tanpa kunci → selalu `?? 'default'`.)
- [ ] `_item.blade.php`: pakai `_icon` dengan `$n->data['ikon'] ?? 'default'`; judul unread `font-semibold`, read `font-medium text-gray-600 dark:text-gray-400`; tint + dot unread dipertahankan. Copy `Pengingat pengisian supervisi` TIDAK berubah.
- [ ] `index.blade.php`: `<x-page-header title="Notifikasi">` dengan tombol "Tandai semua terbaca" di slot `actions` (form POST sama); kelompokkan item per tanggal dari `created_at` item paginator: "Hari Ini" / "Kemarin" / "Sebelumnya" (header `text-xs font-semibold uppercase text-gray-500`); paginasi `links()` bawaan; empty state → `<x-empty-state icon="bell">` (registry sudah punya `bell` sejak Task 3).
- [ ] `php artisan config:clear && php artisan test --filter=Notifikasi` hijau; verifikasi manual dropdown & halaman (seed cepat via tinker lalu hapus); `npm run build`; screenshot.
- [ ] Commit: `feat: notifikasi — ikon per jenis dari payload, grup tanggal, page header`

### Task 20: Modul guru — grid & affordance progres

**Files:**
- Modify: `resources/views/guru/modul/index.blade.php`, `resources/views/guru/modul/baca.blade.php`; kemungkinan `resources/js/modul-reader.js` + test vitest-nya

**Steps:**
- [ ] Index: container `max-w-5xl`→`max-w-6xl`; grid `sm:grid-cols-2 lg:grid-cols-3`; kartu tetap tekstual — hirarki tipografi dipertegas (judul `font-semibold text-base` > pill kategori > progres), TANPA cover dekoratif.
- [ ] Baca: blok "file tidak ditemukan" → `<x-empty-state>`; affordance "Progres tersimpan" kecil dekat toolbar (muncul sesaat setelah simpan). Bila butuh perubahan `modul-reader.js`: test vitest RED dulu, baru implementasi.
- [ ] `npm test` + `php artisan config:clear && php artisan test --filter="Modul"` hijau; Playwright: baca modul, progres tampil; `npm run build`; screenshot.
- [ ] Commit: `style: modul guru — grid 3 kolom + hirarki kartu + affordance progres tersimpan`

### Task 21: Admin modul management

**Files:**
- Modify: `resources/views/admin/modul/index.blade.php`

**Steps:**
- [ ] `<details>` native → disclosure Alpine (`x-data="{open:false}"`) dengan chevron `x-icon` berotasi, styling kartu konsisten; field form edit di dalamnya tidak berubah.
- [ ] **Badge aktif/nonaktif (±101) → `<x-status-badge>`** (titipan Task 4 — hapus file ini dari whitelist `StatusPillSingleSourceTest`); baris nonaktif tetap `opacity-*` sebagai penguat.
- [ ] Form tambah: adopsi `x-form.*` + `x-button`; slot video tetap 2 bila validasi membatasi (CEK FormRequest dulu — style saja, JANGAN ubah rules).
- [ ] `php artisan config:clear && php artisan test --filter="Modul|Admin|StatusPillSingleSourceTest"` hijau (whitelist sementara kini KOSONG — guard aktif penuh); Playwright: tambah + edit + toggle modul; `npm run build`; screenshot.
- [ ] Commit: `style: manajemen modul admin — disclosure Alpine + komponen form + guard status tuntas`

### Task 22: Halaman error + settings

**Files:**
- Modify: `resources/views/errors/404.blade.php`, `419.blade.php`, `500.blade.php`, `503.blade.php`, `network.blade.php`, `resources/views/settings/index.blade.php`

**Steps:**
- [ ] Template error seragam: kode `text-7xl font-extrabold text-primary-600 dark:text-primary-400` (buang `text-9xl font-black` + warna campur), `x-icon` ilustratif, pesan singkat, `x-button` CTA "Kembali ke Beranda". Verifikasi render kondisi guest DAN authed (error pages lewat guest branch layout saat logout).
- [ ] `settings/index.blade.php`: adopsi `x-page-header` + `x-card` + `x-form.*`.
- [ ] `php artisan config:clear && php artisan test --filter="DarkModeCoverageTest|ColorTokenMigrationTest"` hijau; kunjungi tiap halaman error; `npm run build`; screenshot 404 light/dark.
- [ ] Commit: `style: halaman error & settings seragam dengan sistem visual`

### Task 23: Closeout — normalisasi radius + migrasi sisa kartu + cabut Material Symbols

**Files:**
- Create: `tests/Feature/RadiusConsistencyTest.php`
- Modify: `resources/views/layouts/modern.blade.php` (cabut `<link>` Material Symbols), `resources/views/components/video-praktik-badge.blade.php`, `resources/views/kepala/modul-progress/index.blade.php`, `resources/views/admin/rubrik-items/index.blade.php`, `resources/views/admin/carousel/index.blade.php`, + file sisa temuan grep

**Steps:**
- [ ] **Guard test dulu (RED)**: `RadiusConsistencyTest` — pola `ColorTokenMigrationTest`: tidak ada `rounded-md`/`rounded-3xl` di `resources/views`. Jalankan → RED.
- [ ] Normalisasi radius repo-wide: `rounded-md`→`rounded-lg`, `rounded-3xl`→`rounded-2xl`; khusus `video-praktik-badge.blade.php` (badge) → `rounded-full` sesuai kanon. Test → GREEN.
- [ ] Migrasi sisa kartu kanonik tulisan-tangan → `x-card`: `admin/carousel/index.blade.php` (±33, 92), `kepala/modul-progress/index.blade.php` (±40), `admin/rubrik-items/index.blade.php` (±13, 30) — file yang tak tersentuh task mana pun.
- [ ] `git grep -l "material-symbols\|Material+Symbols" resources/` → migrasikan sisa konsumen ke `x-icon`, lalu cabut `<link>` dari `layouts/modern.blade.php`.
- [ ] Grep bersih: `cdn.tailwindcss` → kosong; `from-primary-600 to-primary-600` → kosong; `text-\[8px\]` → kosong.
- [ ] **Verifikasi penuh**: `npm run build` + `npm test` + `php artisan config:clear && php artisan test` (semua hijau, termasuk 2 guard test baru tanpa whitelist).
- [ ] Playwright matriks akhir — HANYA kombinasi yang belum pernah di-screenshot task sebelumnya: breakpoint 768px untuk halaman utama + halaman per peran yang relevan saja (rubrik = kepsek; manajemen = admin; modul baca = guru). Jangan mengulang matriks Task 12.
- [ ] Commit: `chore: closeout overhaul — normalisasi radius + guard test, migrasi sisa kartu, cabut Material Symbols`
