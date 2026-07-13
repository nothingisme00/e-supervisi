# Alur Evaluasi Berpandu (Guided Stepper) — Kepala Sekolah

Tanggal: 2026-07-12 · Status: disetujui user

## Tujuan

Menjadikan aksi **"Isi Rubrik Penilaian"** fokus paling menonjol dalam tinjauan
supervisi oleh kepala sekolah, lewat alur berpandu 4 langkah dengan stepper
horizontal. Mockup user (5 kriteria, skor 1–4, top bar minimal) diadaptasi ke
kenyataan sistem: **instrumen 47 aspek (section A/B/C, skor 0/1/2) dipertahankan**,
stepper lintas halaman server-rendered, layout app (sidebar + topbar) tetap.

## Pemetaan langkah → halaman

| Langkah | Halaman/Route | "Selesai" dihitung dari |
|---|---|---|
| 1 · Tinjau Materi | `kepala.evaluasi.show` (dirampingkan: dokumen, video, refleksi) | status ∈ {under_review, revision, completed} |
| 2 · Isi Rubrik | `kepala.evaluasi.rubrik` (wizard internal A/B/C/Ringkasan dipertahankan) | skor tersimpan = jumlah item aktif (47/47) |
| 3 · Feedback | **baru:** `kepala.evaluasi.feedback.show` (GET) — thread + form + ringkasan nilai rubrik + Tandai Selesai | ada feedback dari user role kepala_sekolah |
| 4 · Selesai | bukan halaman; end-state stepper | status = completed |

## Komponen

- **`x-evaluasi-stepper`** — props: `supervisi`, `aktif` (1–3). Empat node +
  garis penghubung. Selesai: lingkaran `primary-600` + ikon centang, garis teal.
  Aktif: lingkaran lebih besar, ring `primary-100` (glow ala `box-shadow 0 0 0 5px`),
  label tebal teal. Mendatang: abu. Node 1–3 adalah link antar halaman; node 4
  indikator saja. Dark mode penuh.
- **`x-evaluasi-guru-header`** — props: `supervisi`. Avatar inisial 2 huruf,
  nama, NIK, `x-status-badge`. Dipakai ketiga halaman menggantikan header besar
  `show` yang lama (tombol "Mulai Review" pindah ke bar aksi langkah 1).
- **Bar aksi sticky bawah** (markup per halaman, pola sama): kiri
  "Langkah N · Judul"; kanan tombol Kembali (outline) + tombol primer teal.
  `sticky bottom-0` dengan `shadow` atas, offset aman dari bottom-nav mobile.

## Perubahan halaman

- **show**: back button + header ringkas + stepper (aktif 1) + kartu Dokumen /
  Link Pembelajaran / Refleksi (konten existing). Kartu Rubrik-summary, thread
  feedback, form feedback, modal revisi, dan tombol selesai **dipindah/hapus**.
  Bar aksi: primer = "Mulai Review & Lanjut →" (POST startReview, saat submitted)
  atau "Lanjut: Isi Rubrik →" (link rubrik). Saat completed: primer = link
  "Lihat Feedback" (halaman feedback menampilkan state selesai).
- **rubrik**: stepper (aktif 2) + wizard internal existing. Bar aksi: kiri
  "Langkah 2 · Isi Rubrik Penilaian"; kanan Kembali (ke show), **Simpan Draf**
  (submit biasa, boleh parsial — perilaku existing), **Simpan & Lanjut
  Feedback →** (submit dengan `lanjut=1`, nonaktif via JS sampai 47/47 terisi;
  guard JS memakai progress counter yang sudah ada). Tombol simpan di sub-langkah
  Ringkasan diganti oleh bar aksi.
- **feedback (baru)**: stepper (aktif 3) + kartu ringkasan nilai rubrik
  (nilai akhir, skor, predikat, tombol Unduh PDF `blue-600`, Edit Rubrik bila
  belum completed) + thread `supervisi._feedback-thread` + form feedback +
  checkbox minta revisi + modal revisi (dipindah dari show). Bar aksi: kiri
  "Langkah 3 · Feedback"; kanan Kembali (ke rubrik) + "Tandai Selesai" (hanya
  `under_review`; konfirmasi modal existing). Saat completed: kartu state
  selesai (existing) menggantikan form.

## Controller & route

- `EvaluasiController@showFeedback($id)` — GET baru; guard identik `show`
  (tingkat + status whitelist); eager-load feedback.user, feedback.replies.user,
  evaluasiRubrik.scores.
- Route baru: `GET kepala/evaluasi/{id}/feedback` → `kepala.evaluasi.feedback.show`
  (nama berbeda dari POST `kepala.evaluasi.feedback` yang sudah ada).
- `storeRubrik` — redirect ke halaman feedback (pesan sukses sama); jika
  `lanjut` tidak dikirim (Simpan Draf) tetap redirect ke rubrik dengan pesan
  "Draf tersimpan".
- `giveFeedback` — redirect ke halaman feedback (bukan show).
- `complete` — tidak berubah (redirect index; guard rubrik lengkap sudah ada).

## Yang TIDAK berubah

Instrumen rubrik (47 item, skor 0/1/2, section A/B/C, kelompok), kalkulasi
`EvaluasiRubrik::hitungDanSimpan`, predikat, PDF export, locking reviewer,
notifikasi, halaman index evaluasi, akses guru.

## Testing

- Baru: `EvaluasiStepperTest` — status langkah per kondisi data (submitted =
  langkah 1 belum selesai; rubrik lengkap = langkah 2 selesai; feedback kepala =
  langkah 3 selesai; completed = semua + langkah 4), stepper hadir di 3 halaman.
- Baru: halaman feedback — akses OK utk kepala setingkat, 403 beda tingkat,
  memuat thread + form + ringkasan rubrik.
- Update: test yang meng-assert redirect `storeRubrik`/`giveFeedback` ke show.
- Guard suite visual (radius, color token, dark mode, div balance) tetap hijau.
