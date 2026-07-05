# Temuan Audit Visual UI — 2026-07-05

Audit ini berbeda dari `2026-07-04-findings.md` (keamanan/alur/bug): fokusnya kesan visual "AI slop" — template Tailwind generik, gradient indigo-purple berlebihan — dan inkonsistensi footer yang memicu permintaan audit ini. Dilakukan lewat pembacaan kode (grep menyeluruh) + verifikasi visual nyata (app dijalankan lokal, 10 halaman kunci di-screenshot lintas 3 role).

Format entri:
```
## [SEVERITY] Judul singkat
- **Kategori:** Footer-Layout | Warna-Gradient | Tipografi | Komponen | Copy
- **Lokasi:** path/file.blade.php:baris
- **Effort:** S | M | L
- **Dampak:** apa yang terlihat/dirasakan user
- **Usulan perbaikan:** satu kalimat
- **Status:** ditemukan | disetujui | diperbaiki | dilewati
```
Keparahan didefinisikan ulang untuk konteks visual:
- **Kritis** — merusak kepercayaan/fungsi (mis. halaman terasa bukan bagian dari app yang sama).
- **Tinggi** — dampak di halaman berlalu-lintas tinggi, atau duplikasi kode berisiko drift.
- **Sedang** — kosmetik terlihat tapi tidak membingungkan.
- **Rendah** — nitpick atau cleanup non-user-facing.

---

## Ringkasan (terurut keparahan)

Total: **1 Tinggi, 4 Sedang, 3 Rendah** (7 temuan baru). Dead code layout Breeze sudah dibereskan terpisah (lihat commit `ac6bd8f`, sebelum audit ini ditulis).

### Tinggi (1)
- **V1** — Header section berwarna solid acak (biru/ungu/hijau) berulang identik di 6 file lintas 3 role, murni dekoratif tanpa makna semantik — ini pola paling mencolok yang bikin UI terasa generik/"AI slop".

### Sedang (4)
- **V2** — 146 pemakaian `bg-gradient-to` di 20 file; token warna semantik di `app.css` nyaris tidak dipakai.
- **V3** — Role kepala sekolah tampil `Kepala_sekolah` (raw enum, underscore) di header, bukan "Kepala Sekolah".
- **V4** — Modal panduan onboarding (guide tour) masih berbahasa Inggris ("Back"/"Continue") — lolos dari audit R11 sebelumnya karena tidak ter-cover test yang ada.
- **V5** — `lazy-image` fallback gradient default hardcode `indigo→purple→indigo`, dipakai di banyak tempat sebagai placeholder visual.

### Rendah (2)
- **V6** — Duplikasi definisi sticky-footer (flex column + margin-top:auto) 3x di `modern.blade.php` untuk hal yang identik.
- **V7** — Halaman auth (login, change-password) berdiri sendiri (standalone, tanpa `@extends`) sehingga tidak mewarisi footer — konsisten dengan cara mereka dibangun, tapi menonjol dibanding halaman lain yang selalu berfooter.

---

## [Tinggi] Header section berwarna solid berulang tanpa makna semantik
- **Kategori:** Warna-Gradient
- **Lokasi:** `resources/views/guru/supervisi/detail.blade.php`, `guru/supervisi/view.blade.php`, `guru/supervisi/proses.blade.php`, `guru/supervisi/create.blade.php`, `admin/supervisi/detail.blade.php`, `kepala/evaluasi/show.blade.php` — semuanya punya blok "Dokumen Evaluasi Diri" (biru solid), "Link Pembelajaran" (ungu solid), "Refleksi Pembelajaran" (hijau solid), dan di beberapa juga strip atas gradient pink→ungu→biru di kartu utama.
- **Effort:** M
- **Dampak:** Screenshot `04-guru-supervisi-detail.png` dan `07-kepala-evaluasi-show.png` menunjukkan pola identik: 3 section berwarna acak tanpa pola yang bisa dijelaskan (bukan status, bukan prioritas) — ini yang paling terasa seperti "template generator" karena warnanya tidak menyampaikan informasi apa pun, cuma dekorasi berulang.
- **Usulan perbaikan:** Ganti jadi header netral (satu warna/border-left tipis dengan ikon) atau, jika warna dipertahankan untuk pembeda kategori, gunakan token semantik konsisten (bukan biru/ungu/hijau acak) dan terapkan sekali di partial yang dipakai ulang, bukan disalin 6x.
- **Status:** diperbaiki — komponen `x-card-header` (netral, aksen kiri `primary`) menggantikan 15 blok header berwarna di guru/supervisi/{view,detail} & kepala/evaluasi/show; strip gradient pink→ungu→biru jadi solid `primary`; strip `h-1` acak di admin/supervisi/detail diseragamkan; refleksi 5-warna acak jadi satu gaya `primary`

## [Sedang] Skala besar pemakaian gradient/warna hardcode vs token semantik yang sudah ada
- **Kategori:** Warna-Gradient
- **Lokasi:** 146 pemakaian `bg-gradient-to` di 20 file (`modern.blade.php` terbanyak, lalu `guru/home.blade.php`, `livewire/admin/user-management.blade.php`, `admin/dashboard.blade.php`, dst.); token semantik tersedia di `resources/css/app.css:16-21` (`--color-primary`, `--color-secondary`, `--color-success`, `--color-danger`, `--color-warning`) tapi nyaris tidak direferensikan di view manapun.
- **Effort:** L
- **Dampak:** Setiap halaman terlihat sedikit berbeda karena gradient indigo/purple/violet dipasang manual per file, bukan dari satu sumber kebenaran — konsistensi visual bergantung pada disiplin copy-paste, bukan sistem.
- **Usulan perbaikan:** Perluas token di `@theme` (`app.css`) dan migrasi bertahap per grup file (layout → komponen reusable → per halaman), tanpa mengubah struktur Tailwind v4 CSS-first yang sudah ada.
- **Status:** diperbaiki — palet "Teal Pendidikan" (pilihan user): skala `--color-primary-50..950` (= teal) di `@theme`; seluruh kelas `indigo-*`/`purple-*`/`violet-*` dimigrasi ke `primary-*` (halaman auth CDN pakai `teal-*` + config inline diperbarui); hex indigo/purple sisa (loading bar, pull-to-refresh, dropdown aktif, input-focus) diganti teal; regresi dijaga `ColorTokenMigrationTest` (scan file view + app.css); `docs/` dikecualikan dari auto-scan Tailwind

## [Sedang] Label role "Kepala_sekolah" tampil mentah (raw enum) di header
- **Kategori:** Copy
- **Lokasi:** `resources/views/layouts/modern.blade.php` (header user info) — konfirmasi visual di `06-kepala-evaluasi-index.png` dan `07-kepala-evaluasi-show.png`: teks "Kepala_sekolah" di bawah nama user, bukan "Kepala Sekolah".
- **Effort:** S
- **Dampak:** Satu-satunya tempat di seluruh app yang menampilkan nilai enum mentah dengan underscore — kontras dengan sisa UI yang konsisten pakai label Indonesia rapi (termasuk hasil kerja R9 sebelumnya).
- **Usulan perbaikan:** Format label role (`str_replace('_', ' ', ucwords(...))` atau accessor khusus) sebelum ditampilkan, konsisten dengan cara role sudah diformat di form login (`ucfirst(str_replace('_', ' ', ...))`, lihat `auth/login.blade.php:554`).
- **Status:** diperbaiki — `ucwords(str_replace('_', ' ', ...))` di header `modern.blade.php`, ter-cover test baru di `IndonesianUiTextTest`

## [Sedang] Modal panduan onboarding masih berbahasa Inggris ("Back"/"Continue")
- **Kategori:** Copy
- **Lokasi:** `resources/views/layouts/modern.blade.php:2893` (`<span>Back</span>`), `:2897` (`<span id="nextStepText">Continue</span>`), `:3105` (`nextText.textContent = 'Continue';`)
- **Effort:** S
- **Dampak:** Konfirmasi visual di `08-admin-dashboard.png` dan `09-admin-users.png` — modal tur muncul otomatis di kunjungan pertama tiap role, tombol navigasinya bahasa Inggris di tengah UI yang seluruhnya Indonesia. Lolos dari audit R11 sebelumnya karena test `IndonesianUiTextTest` tidak memicu render modal ini (butuh trigger JS `just_logged_in` session, tidak tercakup assertion HTTP biasa).
- **Usulan perbaikan:** Ganti ke "Kembali"/"Lanjut" (atau "Selesai" di step terakhir, sesuai `nextStepIcon` yang sudah berubah jadi check di step akhir).
- **Status:** diperbaiki — "Kembali"/"Lanjut"/"Selesai" (markup + JS `updateStepDisplay`), ter-cover test baru di `IndonesianUiTextTest`. Lanjutan: toggle tema di login ("Light Mode"/"Dark Mode") juga diganti "Mode Terang"/"Mode Gelap".

## [Sedang] `lazy-image` fallback gradient default masih hardcode indigo→purple→indigo
- **Kategori:** Komponen
- **Lokasi:** `resources/views/components/lazy-image.blade.php:5` — `'fallbackGradient' => 'from-indigo-600 via-purple-600 to-indigo-800'`
- **Effort:** S
- **Dampak:** Komponen reusable ini menjadi salah satu sumber gradient generik yang otomatis terwarisi ke semua pemanggilnya tanpa perlu override eksplisit.
- **Usulan perbaikan:** Ganti default ke token semantik baru begitu fondasi warna (temuan V2) diperluas — satu titik ubah, otomatis konsisten di semua pemanggil.
- **Status:** diperbaiki — default jadi `from-primary-600 via-primary-700 to-primary-900` (monokrom teal dari token)

## [Rendah] Duplikasi definisi sticky-footer 3x untuk hal yang identik
- **Kategori:** Footer-Layout
- **Lokasi:** `resources/views/layouts/modern.blade.php` — `<style>` block baris 26-34 (`#main-content { min-height:100vh; display:flex; flex-direction:column } #main-content > footer { margin-top:auto }`), inline `style` di baris 609 (`min-height:100vh;display:flex;flex-direction:column;`), inline `style` lagi di baris 615 (`margin-top:auto;`).
- **Effort:** S
- **Dampak:** Tidak ada dampak visual saat ini (ketiganya konsisten), tapi risiko drift kalau salah satu diedit tanpa yang lain — tiga sumber kebenaran untuk satu perilaku.
- **Usulan perbaikan:** Sisakan satu definisi (kelas Tailwind `flex flex-col`/`mt-auto` yang sudah ada di `class`, hapus `style` inline yang redundan dan blok `<style>` custom).
- **Status:** diperbaiki — blok `<style>` sticky-footer + 3 inline style dihapus, kelas Tailwind jadi satu-satunya definisi; ter-cover `LayoutStickyFooterTest`

## [Rendah] Halaman auth (login, change-password) tidak mewarisi footer app
- **Kategori:** Footer-Layout
- **Lokasi:** `resources/views/auth/login.blade.php`, `resources/views/auth/change-password.blade.php` — standalone full-HTML, tidak `@extends` layout manapun.
- **Effort:** M
- **Dampak:** Ini akar masalah asli yang memicu keluhan "footer beda-beda antar halaman" di awal. Screenshot `01-login.png` mengonfirmasi: halaman login punya desain sendiri (ilustrasi + gradient teal kiri, form kanan) tanpa footer co-brand, kontras dengan seluruh halaman lain (guru/kepala/admin) yang selalu berfooter lewat `modern.blade.php`.
- **Usulan perbaikan:** Tambah footer partial kecil (co-brand minimal, bukan `@extends('layouts.modern')` penuh — itu akan membawa sidebar/nav yang tidak relevan untuk halaman auth) dipakai di kedua file.
- **Status:** diperbaiki — partial `auth/partials/footer.blade.php` ("© {tahun} E-Supervisi · Sistem Supervisi Pembelajaran") dipakai di login (menggantikan copyright inline lama) & change-password; ter-cover `AuthPageFooterTest`

---

## Observasi Aman (OK) — jangan disentuh

- `resources/css/app.css`: utility custom scrollbar (light+dark), animasi (`fade-in`/`slide-up`/`slide-down`/`scale-in`/`pulse-slow`), `.hover-scale`, `.hover-lift`, `.stagger-1..4`, `.line-clamp-4` — semuanya solid, reusable, tidak perlu ditulis ulang.
- Layout dasar (`min-h-screen flex flex-col`, sidebar responsif, bottom-nav mobile) berfungsi baik secara struktural — masalahnya di warna/label, bukan struktur.
- Halaman `admin/users` dan `admin/carousel` (screenshot `09`, `10`): tabel, empty-state, badge status sudah rapi dan konsisten — tidak butuh perubahan visual besar, hanya ikut migrasi token warna nanti.
- Komponen `status-badge` (dari kerja R9 sebelumnya) sudah bekerja baik lintas role — terlihat konsisten di `03`, `06` (badge "Disubmit", "REVIEW").
- Font system stack tetap dipertahankan — bukan sumber masalah "AI slop" (lihat kerangka Bagian B di bawah).

---

## Kerangka Redesign Bertahap (arah, perlu approve terpisah sebelum eksekusi)

1. **Fondasi token warna** — perluas `@theme` di `app.css` (CSS custom property biasa, bukan sistem token/build-step baru) dengan palet yang punya karakter. Migrasi bertahap: layout (`modern.blade.php`) → komponen reusable (V5 dkk.) → per halaman (guru → kepala → admin).
2. **Footer & layout** — tambah footer partial kecil ke halaman auth (V7); konsolidasi duplikasi CSS sticky-footer (V6) jadi satu definisi.
3. **Re-skin komponen reusable** (`status-badge`, `empty-state`, `skeleton-loader`, `breadcrumb`, `custom-dropdown`, `lazy-image`, `loading-spinner`) memakai token baru — tidak ada komponen baru.
4. **Header section berulang (V1)** — konsolidasi 6 file yang punya blok "Dokumen Evaluasi Diri/Link Pembelajaran/Refleksi Pembelajaran" jadi satu partial/komponen, ganti warna acak jadi konsisten.
5. **Rollout per halaman/role** (guru → kepala → admin), kurangi gradient jadi solid/token di mana tidak menambah makna, rapikan copy sisa Inggris (V3, V4).
6. **Font** — tidak perlu font display baru; system font stack sudah cukup, sumber masalah "AI slop" adalah warna/gradient (V1, V2), bukan tipografi.

Palet warna konkret akan diusulkan sebagai 2-3 opsi nyata pada tahap eksekusi (setelah temuan di atas disetujui/diprioritaskan), sesuai keputusan user untuk tidak menentukan warna sekarang.
