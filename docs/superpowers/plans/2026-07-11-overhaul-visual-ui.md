# Overhaul Visual/UX E-Supervisi — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Merapikan desain seluruh aplikasi (landing/login, dashboard 3 peran, supervisi & evaluasi, modul & notifikasi) menjadi profesional, konsisten, dan berhirarki benar — tanpa mengubah perilaku PHP.

**Architecture:** Foundation-first: bangun kosakata komponen (x-icon, x-page-header, x-button, x-form.*, x-card) + satu sumber token di `resources/css/app.css`, lalu adopsi per area. Login/change-password dimigrasi dari Tailwind CDN ke build Vite. Semua perubahan Blade/CSS/JS-view saja.

**Tech Stack:** Laravel 12 Blade, Tailwind v4 CSS-first (`@theme` di app.css, TANPA tailwind.config.js), Vite, Livewire 3 + Alpine (sudah ada), PHPUnit + vitest, Playwright MCP untuk screenshot.

## Global Constraints

- **Arah estetika: PROFESIONAL & TENANG.** Depth hanya `border` + `shadow-sm`; TANPA gradient dekoratif baru (yang ada dihapus/diratakan jadi flat); animasi hanya transisi fungsional halus (dropdown/hover); warna non-teal hanya untuk makna (status/peringatan/jenis notifikasi), tidak pernah hiasan. Kalau ragu antara "lebih menarik" vs "lebih konsisten" → pilih konsisten.
- **Palet:** nilai token `--color-primary-*` (teal) di `resources/css/app.css` TIDAK berubah. DILARANG kata/hex indigo, purple, violet di view & CSS (`ColorTokenMigrationTest`).
- **Radius:** kartu/panel/dropdown = `rounded-xl`; tombol/input/select = `rounded-lg`; badge/pill/avatar = `rounded-full`; blok hero maks `rounded-2xl`. `rounded-3xl`/`rounded-md` diganti saat file tersentuh.
- **Kartu kanonik:** `bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm`.
- **Status:** `x-status-badge` = satu-satunya sumber warna+label status: draft=gray, submitted=blue, under_review=amber, revision=red, completed=emerald, aktif=emerald, nonaktif=gray. Label test-enforced: Disubmit/Ditinjau/Revisi/Selesai (JANGAN "Direview"/"Telah Ditinjau").
- **Ikon:** SVG inline saja (gaya Heroicons outline 24×24 stroke) via `x-icon`. Tanpa emoji. Material Symbols dipensiunkan per halaman; `<link>` dicabut hanya di Task 26.
- **Dark mode:** setiap view yang punya `bg-white` wajib punya varian `dark:` (`DarkModeCoverageTest`). Kontras teks ≥4.5:1. Touch target ≥44px.
- **JANGAN ubah:** copy yang di-assert test (mis. `Pengingat pengisian supervisi`, footer auth `E-Supervisi · Sistem Supervisi Pembelajaran`), id `notif-dropdown-btn`, atribut `data-notif-badge`, nama field form (mis. `skor[{id}]`), hook JS rubrik (`rubrikGoToStep`, `data-rubrik-step*`), `rubrik-pdf.blade.php`, `resources/js/bootstrap.js` (itu axios).
- **Tanpa perubahan perilaku PHP** (route/controller/composer/FormRequest/Notification). Pengecualian tunggal: `resources/js/modul-reader.js` di Task 23 via TDD vitest (RED dulu).
- **Div balance:** `LayoutDivBalanceTest` menghitung `<div>`/`</div>` di 3 dashboard — hitung sebelum commit.
- **Verifikasi per task:** `npm run build` + `php artisan config:clear` lalu test terarah (WAJIB config:clear — insiden DB terhapus). Test suite penuh per akhir fase.
- Commit per task di branch `develop`, pesan bahasa Indonesia berprefiks feat/fix/refactor/style.

---

## Fase 0 — Fondasi

### Task 1: Inter self-hosted + dokumentasi konvensi di app.css

**Files:**
- Create: `public/fonts/inter/InterVariable.woff2` (+ `InterVariable-Italic.woff2` bila tersedia)
- Modify: `resources/css/app.css`

**Steps:**
- [ ] Unduh InterVariable.woff2 dari https://rsms.me/inter/font-files/InterVariable.woff2 (dan Italic dari https://rsms.me/inter/font-files/InterVariable-Italic.woff2) ke `public/fonts/inter/`. Bila unduhan gagal total, fallback: tambah `<link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap">` di kedua layout dan laporkan deviasi.
- [ ] Di `app.css` (sebelum `@theme`): blok `@font-face` untuk `Inter` (font-family "Inter", src url('/fonts/inter/InterVariable.woff2') format('woff2'), `font-weight: 100 900`, `font-display: swap`, `font-style: normal`; blok kedua italic bila file ada).
- [ ] Ubah `--font-sans` menjadi: `"Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif`.
- [ ] Tambah blok komentar dokumentasi di atas `@theme`: skala radius (kartu=xl, tombol/input=lg, pill=full, hero maks 2xl), kanon warna status (lihat Global Constraints), konvensi merah `red-*` = aksen video praktik (bukan token).
- [ ] `npm run build` sukses; screenshot cepat satu halaman authed untuk konfirmasi Inter ter-render (DevTools computed font-family).
- [ ] Commit: `style: font Inter self-hosted + dokumentasi konvensi desain di app.css`

### Task 2: Cabut dependency mati (Bootstrap/Sass/Popper)

**Files:**
- Modify: `package.json`
- Delete: `resources/sass/app.scss`, `resources/sass/_variables.scss` (seluruh folder `resources/sass/`)

**Steps:**
- [ ] Verifikasi dulu: `git grep -il "popper"` (kecuali package-lock) dan `git grep -l "sass"` di resources/vite.config — pastikan tak ada konsumen nyata.
- [ ] Hapus dari devDependencies: `bootstrap`, `sass`, `@popperjs/core`. JANGAN hapus `concurrently`, `axios`, `laravel-vite-plugin`, `tailwindcss`, `@tailwindcss/vite`, `pdfjs-dist`, vitest deps. JANGAN sentuh `resources/js/bootstrap.js` (itu axios bootstrap Laravel).
- [ ] Hapus folder `resources/sass/`.
- [ ] `npm install` (perbarui lockfile), `npm run build`, `npm test` — semua sukses.
- [ ] Commit: `chore: cabut dependency mati bootstrap+sass+popper (app murni Tailwind v4)`

### Task 3: Komponen x-icon + x-page-header + yield page-title

**Files:**
- Create: `resources/views/components/icon.blade.php`, `resources/views/components/page-header.blade.php`
- Modify: `resources/views/layouts/modern.blade.php` (baris `<title>` saja), `resources/views/notifikasi/index.blade.php` (pilot)

**Steps:**
- [ ] `icon.blade.php`: props `name` (wajib) + `class` merge (default `w-5 h-5`). Registry `@switch($name)` SVG inline 24×24 `fill="none" stroke="currentColor" stroke-width="1.5"` gaya Heroicons outline, minimal: `bell`, `arrow-left`, `chevron-down`, `book-open`, `chat-bubble`, `exclamation-triangle`, `star`, `clipboard-check`, `clock`, `eye`, `document`, `check-circle`, `x-mark`, `plus`, `search`, `users`, `pencil`, `trash`, `home`. Default case: ikon `bell`. Docblock contoh pemakaian di atas file.
- [ ] `page-header.blade.php`: props `title` (wajib), `subtitle` (opsional), `backUrl` (opsional → link `x-icon arrow-left` + teks "Kembali"), slot `actions` (opsional, rata kanan). h1 = `text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100`; subtitle `text-sm text-gray-500 dark:text-gray-400 mt-1`. Wrapper `mb-6 flex flex-wrap items-start justify-between gap-3`.
- [ ] Di `layouts/modern.blade.php` ganti baris `<title>` menjadi: `<title>@hasSection('page-title')@yield('page-title') · @endif{{ config('app.name', 'E-Supervisi') }}</title>`.
- [ ] Pilot: `notifikasi/index.blade.php` pakai `<x-page-header title="Notifikasi">` dengan tombol "Tandai semua terbaca" pindah ke slot `actions` (markup form POST tetap sama).
- [ ] `php artisan config:clear && php artisan test --filter=Notifikasi` hijau; tab browser menampilkan "Notifikasi · E-Supervisi".
- [ ] Commit: `feat: komponen x-icon + x-page-header, judul tab dari page-title (pilot notifikasi)`

### Task 4: Komponen x-button, x-form.input/select/textarea, x-card

**Files:**
- Create: `resources/views/components/button.blade.php`, `resources/views/components/form/input.blade.php`, `resources/views/components/form/select.blade.php`, `resources/views/components/form/textarea.blade.php`, `resources/views/components/card.blade.php`

**Steps:**
- [ ] `button.blade.php`: props `variant` (primary|secondary|danger|ghost, default primary), `size` (sm|md, default md), `href` (opsional → render `<a>`, selain itu `<button>` dengan `type` merge default `button`). Base: `inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900 disabled:opacity-50 disabled:pointer-events-none`. md = `px-4 py-2.5 text-sm min-h-[44px]`; sm = `px-3 py-1.5 text-sm`. primary=`bg-primary-600 hover:bg-primary-700 text-white`; secondary=`border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700`; danger=`bg-red-600 hover:bg-red-700 text-white`; ghost=`text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700`.
- [ ] `form/input.blade.php`, `form/select.blade.php`, `form/textarea.blade.php`: props `label`, `name`, `error` (opsional; fallback `$errors->first($name)`), `hint` (opsional). Kelas field: `w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500`. Label `block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5`. Error `text-sm text-red-600 dark:text-red-400 mt-1`. Atribut lain di-merge ke field.
- [ ] `card.blade.php`: prop `flush` (bool, default false → tambah `overflow-hidden`). Kelas kartu kanonik dari Global Constraints; `{{ $slot }}`.
- [ ] Verifikasi render: sisipkan sementara di satu halaman atau via `php artisan tinker` + `Blade::render()`; pastikan tidak ada error dan dark mode lengkap.
- [ ] `npm run build` + `php artisan config:clear && php artisan test --filter="DarkModeCoverageTest|ColorTokenMigrationTest"` hijau.
- [ ] Commit: `feat: komponen dasar x-button, x-form.(input|select|textarea), x-card`

### Task 5: Warna status satu sumber

**Files:**
- Modify: `resources/views/components/status-badge.blade.php`, `resources/views/admin/dashboard.blade.php` (~163-167), `resources/views/kepala/evaluasi/index.blade.php` (~133-141), `resources/views/admin/supervisi/index.blade.php` (~25-54 pill status saja, bukan tab filter), `resources/views/admin/modul/index.blade.php` (~101)

**Steps:**
- [ ] Perluas map `status-badge.blade.php`: `aktif` → emerald + label "Aktif"; `nonaktif` → gray + label "Nonaktif". Pertahankan label lama persis (Disubmit/Ditinjau/Revisi/Selesai/Draft).
- [ ] Ganti setiap pill status hardcoded di 4 file target dengan `<x-status-badge :status="...">`. Copy tab/filter TIDAK berubah — hanya elemen badge.
- [ ] `php artisan config:clear && php artisan test --filter="StatusBadgeConsistencyTest|IndonesianUiTextTest|LayoutDivBalanceTest"` hijau.
- [ ] `git grep -n "bg-amber-100" resources/views/admin/dashboard.blade.php` (dan pola serupa) memastikan tak ada map status bespoke tersisa di 4 file.
- [ ] Commit: `fix: warna+label status satu sumber via x-status-badge (aktif/nonaktif ditambahkan)`

### Task 6: Bersih-bersih mekanis (gradient palsu, CTA off-brand, chevron)

**Files:**
- Modify: `resources/views/guru/home.blade.php` (baris ±383, 441, 671, 751), `resources/views/livewire/admin/user-management.blade.php` (±18), `resources/views/layouts/modern.blade.php` (header profile-dropdown ±405), `resources/views/components/empty-state.blade.php` (±43), `resources/views/components/custom-dropdown.blade.php`

**Steps:**
- [ ] Semua `bg-gradient-to-* from-primary-600 to-primary-600` → `bg-primary-600` (render identik; markup jujur). Cari juga varian lain `from-X to-X` identik: `git grep -nE "from-([a-z]+-[0-9]+) (via-[^ ]+ )?to-\1"`.
- [ ] `empty-state.blade.php`: CTA `bg-blue-600 hover:bg-blue-700` → `bg-primary-600 hover:bg-primary-700`.
- [ ] `custom-dropdown.blade.php`: glyph Material Symbols `expand_more` → `<x-icon name="chevron-down" class="w-4 h-4">`.
- [ ] `git grep "from-primary-600 to-primary-600"` → kosong. `php artisan config:clear && php artisan test` (suite penuh, akhir fase 0) + `npm test` + `npm run build` hijau.
- [ ] Commit: `fix: hapus gradient palsu, CTA empty-state ke primary, chevron SVG inline`

---

## Fase 1 — Landing/Login (view-only; `CustomLoginController` TIDAK disentuh)

### Task 7: layouts/auth.blade.php + partial theme-init bersama

**Files:**
- Create: `resources/views/layouts/auth.blade.php`, `resources/views/partials/theme-init.blade.php`
- Modify: `resources/views/layouts/modern.blade.php` (ganti script anti-flash baris ±11-18 dengan `@include('partials.theme-init')`)

**Steps:**
- [ ] `theme-init.blade.php`: script inline anti-flash — `localStorage.theme` menang; bila kosong ikut `window.matchMedia('(prefers-color-scheme: dark)')`; set/hapus kelas `dark` di `document.documentElement` sebelum paint.
- [ ] `layouts/auth.blade.php`: dokumen HTML lengkap — `<html lang="id">`, theme-init, `<title>` pola sama Task 3, `@vite(['resources/css/app.css','resources/js/app.js'])`, TANPA link Material Symbols/Google Fonts/CDN, `@yield('content')`, include `resources/views/auth/partials/footer.blade.php`, stack `@stack('scripts')` + `@stack('styles')`.
- [ ] `layouts/modern.blade.php`: ganti script anti-flash inline dengan include partial (perilaku berubah: default kini ikut prefers-color-scheme — dikehendaki).
- [ ] `php artisan config:clear && php artisan test --filter="AuthPageFooterTest|LayoutStickyFooterTest"` hijau (login belum pindah layout — test footer masih lewat jalur lama).
- [ ] Commit: `feat: layout auth berbasis Vite + theme-init anti-flash bersama`

### Task 8: Migrasi login.blade.php ke build Vite

**Files:**
- Modify: `resources/views/auth/login.blade.php` (613 baris — rework head + token; struktur body dipertahankan)

**Steps:**
- [ ] PERTAHANKAN: konsep split-screen (carousel kiri / panel form kanan), JS carousel + dots + interval 5 detik, data `$carouselSlides` + slide fallback hardcoded, label toggle tema Indonesia, include footer partial, semua `name` atribut form + CSRF + route.
- [ ] GANTI: `@extends('layouts.auth')`; hapus `<script src="https://cdn.tailwindcss.com">` + config token inline + link Google Fonts (Inter sudah dari app.css); map token bespoke → token app (`background-light`→`gray-50`, `background-dark`→`gray-900`, `card-*`→`white`/`gray-800`, `#0F766E`/`teal-600`→`primary-600` dst); glyph Material Symbols (`badge`, `lock`, `visibility`, `light_mode`) → `x-icon` (tambah nama ikon baru ke registry bila perlu: `eye-slash`, `sun`, `moon`, `lock`, `id-card`).
- [ ] FIX HIRARKI: `<h1>` = "E-Supervisi" branding dipromosikan ke `text-2xl lg:text-3xl font-bold`; headline carousel didemosikan ke `text-2xl lg:text-3xl` (dari 5xl) dan bukan elemen terbesar halaman; hapus efek `bg-clip-text` gradient di slide fallback → teks putih biasa (prinsip profesional).
- [ ] CSS khusus carousel pindah ke `@push('styles')` blok `<style>` di halaman (bukan app.css — hanya dipakai di sini).
- [ ] Verifikasi: `git grep -n "cdn.tailwindcss\|fonts.googleapis" resources/views/auth/login.blade.php` → kosong; `php artisan config:clear && php artisan test --filter="AuthPageFooterTest|IndonesianUiTextTest"` hijau; Playwright screenshot 375/1440 × light/dark — form login berfungsi (login guru berhasil).
- [ ] Commit: `refactor: migrasi halaman login dari Tailwind CDN ke build Vite + fix hirarki heading`

### Task 9: Migrasi change-password.blade.php

**Files:**
- Modify: `resources/views/auth/change-password.blade.php`

**Steps:**
- [ ] Perlakuan sama Task 8: extend `layouts.auth`, hapus CDN/config/fonts, map token, Material Symbols → x-icon, adopsi `x-form.input` + `x-button` untuk field & tombol. JS strength-meter password dipertahankan (sesuaikan selector bila markup berubah).
- [ ] `php artisan config:clear && php artisan test --filter="AuthPageFooterTest"` hijau; Playwright: alur ganti password (user `must_change_password`) tetap jalan; screenshot light/dark.
- [ ] Commit: `refactor: migrasi halaman ganti-password ke build Vite + komponen form`

---

## Fase 2 — Shell app + dashboard

### Task 10: Polish shell layouts/modern.blade.php (surgical)

**Files:**
- Modify: `resources/views/layouts/modern.blade.php`

**Steps:**
- [ ] Topbar: SVG lonceng di tombol `#notif-dropdown-btn` dan link sidebar Notifikasi → `<x-icon name="bell">` (id/atribut JANGAN berubah); panel dropdown notif + profil seragam `rounded-xl` + transisi buka/tutup halus (opacity+scale via kelas yang sudah ada polanya, JS toggle existing).
- [ ] Sidebar: tinggi item nav ≥44px (`min-h-[44px]` atau padding setara), state aktif konsisten (`bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400` — sudah polanya, rapikan yang menyimpang).
- [ ] Header profile-dropdown: rapikan tipografi/spacing (gradient palsu sudah dibereskan Task 6).
- [ ] Footer & bottom-nav mobile: audit touch target ≥44px; copy footer TIDAK berubah.
- [ ] Hitung div balance sebelum commit. `php artisan config:clear && php artisan test --filter="LayoutDivBalanceTest|LoncengTopbar|LayoutStickyFooterTest|DarkModeCoverageTest"` hijau; screenshot topbar+sidebar light/dark 375/1440.
- [ ] Commit: `style: polish shell — ikon lonceng via x-icon, transisi dropdown, touch target sidebar`

### Task 11: Guru home

**Files:**
- Create: `resources/views/guru/_supervisi-card.blade.php`
- Modify: `resources/views/guru/home.blade.php` (789 baris)

**Steps:**
- [ ] `<x-page-header title="Beranda" subtitle="...">` (halaman ini belum punya h1).
- [ ] Kartu timeline supervisi (baris ±94-369) diekstrak ke partial `_supervisi-card.blade.php` (param: `$supervisi`, flag `$milikSendiri`): header avatar+nama+badge, chip info Dokumen/Proses/Video/Feedback, accordion komentar, footer aksi status-dependent — pakai `x-card`, `x-status-badge`, `x-button`, `x-icon`. Radius & warna sesuai Global Constraints.
- [ ] Hero/greeting & strip kartu: FLAT `bg-primary-600` atau netral — tanpa gradient (prinsip payung). Kartu stat → `x-card`.
- [ ] Modal welcome: Material Symbols → `x-icon`.
- [ ] Tips accordion: restyle konsisten (chevron `x-icon`, radius xl).
- [ ] Div balance dicek. `php artisan config:clear && php artisan test --filter="IndonesianUiTextTest|StatusBadgeConsistencyTest|LayoutDivBalanceTest|DarkModeCoverageTest"` hijau; screenshot 375/1440 × light/dark.
- [ ] Commit: `refactor: guru home — page header, partial _supervisi-card, hero flat`

### Task 12: Kepala sekolah dashboard

**Files:**
- Modify: `resources/views/kepala/dashboard.blade.php` (252 baris)

**Steps:**
- [ ] `<x-page-header>` menggantikan header ikon+h3 (h3→h1 semantik lewat komponen).
- [ ] 3 kartu kanban (Perlu Review/Sedang Ditinjau/Telah Selesai) → `x-card`; strip gradient atas → flat warna makna status (amber/blue/emerald tipis, mis. `h-1 bg-amber-500`); mini-kartu guru di dalam kolom dirapikan (spacing konsisten, tombol → `x-button` sm).
- [ ] Div balance dicek; test filter sama Task 11 + screenshot.
- [ ] Commit: `style: dashboard kepala sekolah — page header + kartu kanban konsisten`

### Task 13: Admin dashboard

**Files:**
- Modify: `resources/views/admin/dashboard.blade.php` (238 baris)

**Steps:**
- [ ] Perlakuan sama Task 12: `x-page-header`, tile quick-action → `x-card` + `x-icon`, kanban konsisten, layout sekitar `x-status-badge` (dari Task 5) dirapikan.
- [ ] Copy tombol modal panduan TIDAK berubah (test).
- [ ] Div balance + test filter sama + screenshot.
- [ ] Commit: `style: dashboard admin — page header + tile & kanban konsisten`

### Task 14: Admin user management (Livewire)

**Files:**
- Modify: `resources/views/livewire/admin/user-management.blade.php` (542 baris), `resources/views/admin/users/create.blade.php`, `resources/views/admin/users/edit.blade.php`

**Steps:**
- [ ] SEMUA atribut `wire:*` dan struktur root tunggal Livewire dipertahankan persis. Dropdown Alpine existing dipertahankan (hanya restyle kelas).
- [ ] Adopsi `x-page-header`, `x-card`, `x-button`, `x-form.*` di ketiga file; Material Symbols di create/edit → `x-icon`; tabel/list user dirapikan (spacing, badge role konsisten).
- [ ] `php artisan config:clear && php artisan test` (full — akhir fase 2) + `npm test` + `npm run build`; Playwright: buat user baru + cari + edit via UI; screenshot dashboard 3 peran 375/768/1440 × light/dark.
- [ ] Commit: `style: manajemen user admin — adopsi komponen, wire: utuh`

---

## Fase 3 — Supervisi & evaluasi

### Task 15: Partial _feedback-thread + penyatuan detail guru/kepala

**Files:**
- Create: `resources/views/supervisi/_feedback-thread.blade.php`
- Modify: `resources/views/guru/supervisi/detail.blade.php` (dedupe ±235-448), `resources/views/kepala/evaluasi/show.blade.php` (dedupe ±271-448)

**Steps:**
- [ ] Partial props: `$feedbacks`, `$supervisi`, `$readonly` (bool, default false). Isi: kartu komentar ber-aksen kiri (merah=permintaan revisi, primary=milik sendiri, amber=lainnya — samakan; sebelumnya biru utk milik sendiri → pindah ke primary demi konsistensi), badge peran, tombol Balas + form reply (disembunyikan bila `$readonly`), balasan nested `ml-6 border-l-2`. Komponen: `x-icon`, `x-button`.
- [ ] Kedua halaman meng-include partial; markup duplikat dihapus; tombol aksi spesifik-peran tetap di halaman masing-masing.
- [ ] Form reply tetap POST ke route existing dengan nama field sama.
- [ ] `php artisan config:clear && php artisan test --filter="Feedback|Supervisi"` hijau; Playwright: kepsek kirim feedback → guru balas → kedua sisi tampil identik; screenshot.
- [ ] Commit: `refactor: thread feedback jadi partial bersama guru+kepala (dedupe ±210 baris)`

### Task 16: Rebuild admin supervisi detail

**Files:**
- Modify: `resources/views/admin/supervisi/detail.blade.php` (326 baris)

**Steps:**
- [ ] Hapus background gradient full-page `from-slate-50 via-blue-50 to-primary-50` dan seluruh palet `slate-*` → sistem `gray-*`/`primary-*` standar.
- [ ] Kartu strip-warna ad-hoc → `x-card` + `<x-card-header>` (komponen existing).
- [ ] Bagian feedback → include `supervisi/_feedback-thread` dengan `:readonly="true"` (JANGAN tambah form reply admin — perubahan perilaku).
- [ ] `php artisan config:clear && php artisan test --filter="Admin|DarkModeCoverageTest"` hijau; screenshot — paritas visual dengan detail guru/kepala.
- [ ] Commit: `refactor: detail supervisi admin disatukan ke sistem visual standar (readonly thread)`

### Task 17: Redesign rubrik penilaian (47 aspek)

**Files:**
- Modify: `resources/views/kepala/evaluasi/rubrik.blade.php` (128 baris)

**Steps:**
- [ ] PERTAHANKAN: nama field `skor[{id}]`, fungsi/atribut JS step (`rubrikGoToStep`, `data-rubrik-step*`), sticky progress bar, alur submit.
- [ ] Kelompok aspek → kartu ber-`<x-card-header>` (bukan border kiri tipis); antar baris aspek beri ritme (`divide-y` + padding vertikal cukup).
- [ ] Radio pill 3-pilihan → segmented control lebih besar: label ≥44px tinggi, state checked jelas (`peer-checked:bg-primary-600 peer-checked:text-white` pola), angka + label pendek, keyboard-accessible (input radio asli tetap, `sr-only` + focus-visible ring di label).
- [ ] Nav step: tampilkan hitungan terisi per bagian (hitung client-side dari radio checked — JS kecil di view, bukan PHP).
- [ ] Ikon `arrow_back` Material Symbols → `x-icon arrow-left`.
- [ ] `php artisan config:clear && php artisan test --filter="Rubrik"` hijau; Playwright: isi beberapa skor → submit → nilai tersimpan; screenshot 375/1440 light/dark.
- [ ] Commit: `style: rubrik penilaian — kelompok ber-kartu, segmented control ≥44px, hitungan per langkah`

### Task 18: Legibilitas kepala evaluasi index

**Files:**
- Modify: `resources/views/kepala/evaluasi/index.blade.php` (211 baris)

**Steps:**
- [ ] Semua `text-[8px]`/`text-[10px]` → minimal `text-xs`; pill status → `x-status-badge` (sudah dimulai Task 5 — tuntaskan sisanya).
- [ ] Dropdown filter vanilla-JS (`dropdown-menu-custom`) → pola Alpine `x-data`/`x-show` seperti di user-management (view-only, parameter GET sama).
- [ ] `x-page-header` (h1 existing dipindah ke komponen); baris kartu daftar dirapikan (aksen kiri status dipertahankan, spacing naik).
- [ ] `php artisan config:clear && php artisan test --filter="Evaluasi|StatusBadgeConsistencyTest"` hijau (assertDontSee('Direview') tetap lolos); Playwright: filter status bekerja; screenshot.
- [ ] Commit: `fix: legibilitas daftar evaluasi kepsek — teks min text-xs, badge & filter konsisten`

### Task 19: Dedup guru dokumen/evaluasi

**Files:**
- Modify: `resources/views/guru/supervisi/evaluasi.blade.php` (783 baris)

**Steps:**
- [ ] Ganti dual markup (kartu mobile 63-175 + tabel desktop 180-289) dengan SATU pola: daftar baris kartu responsif untuk semua ukuran (preferensi) — satu loop, satu sumber markup.
- [ ] Aksi per baris: 1 tombol primer (upload/ganti) + aksi sekunder ikon ber-label (`x-button` ghost sm + `x-icon`), semua ≥44px.
- [ ] Semua form upload/hapus tetap ke route existing dengan field sama.
- [ ] `php artisan config:clear && php artisan test --filter="Dokumen|Guru"` hijau; Playwright: upload → preview → hapus dokumen di 375px & 1440px; screenshot.
- [ ] Commit: `refactor: daftar dokumen guru satu pola responsif (hapus duplikasi tabel/kartu)`

### Task 20: My-supervisi + form create/proses/view

**Files:**
- Modify: `resources/views/guru/my-supervisi.blade.php` (463 baris), `resources/views/guru/supervisi/create.blade.php`, `resources/views/guru/supervisi/proses.blade.php`, `resources/views/guru/supervisi/view.blade.php`

**Steps:**
- [ ] `my-supervisi.blade.php`: adopsi partial `guru/_supervisi-card` dari Task 11 (satu bahasa visual dengan home); progress bar + stat tile dipertahankan sebagai pelengkap di atas daftar.
- [ ] `create/proses`: adopsi `x-form.*` + `x-button`; 5 blok refleksi textarea di proses diberi struktur berulang rapi (kartu ber-nomor, char counter tetap).
- [ ] `view.blade.php`: samakan dengan `detail.blade.php` (komponen sama; tanpa aksi edit).
- [ ] Semua `x-page-header`. Field & route tidak berubah.
- [ ] `php artisan config:clear && php artisan test` (full — akhir fase 3) + `npm test` + `npm run build`; Playwright E2E: guru buat supervisi → isi proses → submit → kepsek review + rubrik → guru lihat hasil; screenshot semua permukaan fase 3.
- [ ] Commit: `style: my-supervisi & form guru — satu bahasa visual dengan beranda`

---

## Fase 4 — Modul + notifikasi + closeout

### Task 21: Ikon notifikasi per jenis

**Files:**
- Create: `resources/views/notifikasi/_icon.blade.php`
- Modify: `resources/views/notifikasi/_item.blade.php`

**Steps:**
- [ ] `_icon.blade.php`: baca `$ikon` → chip `w-9 h-9 rounded-full flex items-center justify-center` + `<x-icon>`: `modul`→book-open/`bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400`; `feedback`→chat-bubble/blue; `revisi`→exclamation-triangle/red; `nilai`→star/emerald; `review`→clipboard-check/amber; `pengingat`→clock/amber; default→bell/gray. (Payload lama mungkin tanpa kunci → selalu `?? 'default'`.)
- [ ] `_item.blade.php`: pakai `_icon` dengan `$n->data['ikon'] ?? 'default'`; judul unread `font-semibold`, read `font-medium text-gray-600 dark:text-gray-400`; tint + dot unread dipertahankan. Copy `Pengingat pengisian supervisi` TIDAK berubah.
- [ ] `php artisan config:clear && php artisan test --filter=Notifikasi` hijau; verifikasi manual: dropdown & halaman menampilkan ikon berbeda per jenis (seed cepat via tinker lalu hapus).
- [ ] Commit: `feat: ikon notifikasi per jenis dari payload data.ikon (gap spec dilunasi)`

### Task 22: Halaman notifikasi — pengelompokan tanggal

**Files:**
- Modify: `resources/views/notifikasi/index.blade.php`

**Steps:**
- [ ] Kelompokkan item per tanggal di Blade dari `created_at` item paginator: "Hari Ini" / "Kemarin" / "Sebelumnya" (header kecil `text-xs font-semibold uppercase text-gray-500` antar grup). Paginasi tetap `links()` bawaan.
- [ ] Empty state → `<x-empty-state>` (ikon bell).
- [ ] `php artisan config:clear && php artisan test --filter=Notifikasi` hijau; screenshot.
- [ ] Commit: `style: halaman notifikasi — grup tanggal + empty state standar`

### Task 23: Modul guru — grid & affordance progres

**Files:**
- Modify: `resources/views/guru/modul/index.blade.php`, `resources/views/guru/modul/baca.blade.php`; kemungkinan `resources/js/modul-reader.js` + test vitest-nya

**Steps:**
- [ ] Index: container `max-w-5xl`→`max-w-6xl`; grid `sm:grid-cols-2 lg:grid-cols-3`; kartu tetap tekstual — hirarki tipografi dipertegas (judul `font-semibold text-base` > pill kategori > progres), TANPA cover dekoratif.
- [ ] Baca: blok "file tidak ditemukan" → `<x-empty-state>`; affordance "Progres tersimpan" kecil dekat toolbar (muncul sesaat setelah simpan progres). Bila butuh perubahan `modul-reader.js`: tulis test vitest RED dulu (`modul-reader.test.js`), baru implementasi.
- [ ] `npm test` + `php artisan config:clear && php artisan test --filter="Modul"` hijau; Playwright: buka modul, navigasi halaman PDF, progres tampil; screenshot.
- [ ] Commit: `style: modul guru — grid 3 kolom + hirarki kartu + affordance progres tersimpan`

### Task 24: Admin modul management

**Files:**
- Modify: `resources/views/admin/modul/index.blade.php`

**Steps:**
- [ ] `<details>` native → disclosure Alpine (`x-data="{open:false}"`) dengan chevron `x-icon` berotasi, styling konsisten kartu; konten edit form di dalamnya tidak berubah field-nya.
- [ ] Badge aktif/nonaktif kini bermakna via Task 5 — pastikan dipakai; baris nonaktif tetap `opacity-*` sebagai penguat.
- [ ] Form tambah: adopsi `x-form.*` + `x-button`; slot video tetap 2 bila validasi membatasi (CEK FormRequest dulu — style saja, JANGAN ubah rules).
- [ ] `php artisan config:clear && php artisan test --filter="Modul|Admin"` hijau; Playwright: tambah modul + edit + toggle; screenshot.
- [ ] Commit: `style: manajemen modul admin — disclosure Alpine + komponen form`

### Task 25: Halaman error + settings

**Files:**
- Modify: `resources/views/errors/404.blade.php`, `419.blade.php`, `500.blade.php`, `503.blade.php`, `network.blade.php`, `resources/views/settings/index.blade.php`

**Steps:**
- [ ] Template error seragam: kode `text-7xl font-extrabold text-primary-600 dark:text-primary-400` (buang `text-9xl font-black` + warna campur), `x-icon` ilustratif, pesan singkat, `x-button` CTA "Kembali ke Beranda". Verifikasi render kondisi guest DAN authed (error pages lewat guest branch layout saat logout).
- [ ] `settings/index.blade.php`: adopsi `x-page-header` + `x-card` + `x-form.*`.
- [ ] `php artisan config:clear && php artisan test --filter="DarkModeCoverageTest|ColorTokenMigrationTest"` hijau; kunjungi tiap halaman error manual (route 404 asal, /login POST tanpa CSRF utk 419 opsional); screenshot 404 light/dark.
- [ ] Commit: `style: halaman error & settings seragam dengan sistem visual`

### Task 26: Closeout sweep

**Files:**
- Modify: `resources/views/layouts/modern.blade.php` (cabut `<link>` Material Symbols) + file sisa temuan grep

**Steps:**
- [ ] `git grep -l "material-symbols\|Material+Symbols" resources/` → migrasikan sisa konsumen ke `x-icon`, lalu cabut `<link>` dari layout (dan dari layouts/auth bila ada).
- [ ] Grep bersih: `cdn.tailwindcss` → kosong; `from-primary-600 to-primary-600` → kosong; `text-\[8px\]` → kosong; `rounded-3xl` di file tersentuh → kosong.
- [ ] Verifikasi penuh: `npm run build` + `npm test` + `php artisan config:clear && php artisan test` (semua hijau).
- [ ] Playwright matriks akhir: 3 peran × halaman utama (login, dashboard, supervisi detail, rubrik, modul, notifikasi) × {375, 768, 1440} × {light, dark} — arsipkan screenshot sebagai bukti overhaul.
- [ ] Commit: `chore: closeout overhaul visual — cabut Material Symbols, grep bersih`
