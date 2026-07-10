# Platform Pembelajaran Mandiri untuk Guru

## Gambaran
Platform dirancang sebagai ruang pembelajaran mandiri untuk guru dengan modul ajar, video, soal, dan mekanisme supervisi oleh kepala sekolah serta validator untuk memverifikasi kualitas materi.

## Modul Ajar dan Konten Pembelajaran
- **Isi**: Modul, video pembelajaran, dan soal disediakan sebagai paket materi.
- **Sumber**: Konten utama berasal dari kementerian dan dapat diunggah oleh admin sesuai materi yang ditetapkan.
- **Tujuan**: Soal digunakan untuk menampilkan pemahaman guru setelah mempelajari modul.
- **Kesimpulan**: Sistem harus menampilkan modul + soal secara terstruktur agar guru dapat belajar mandiri dan diuji secara langsung.

## Alur Penilaian dan Supervisi
- **Alur**: Admin mengunggah modul dan soal → guru membaca dan mengerjakan → kepala sekolah menilai berdasarkan jawaban dan video praktik guru.
- **Fungsi kepala sekolah**: Melihat apakah guru membaca keseluruhan modul, menilai praktik lewat video, dan mengisi nilai akhir/predikat.
- **Refleksi guru**: Penilaian dirancang memberi umpan balik untuk perbaikan individu, bukan sekadar pengumpulan dokumen.

## Notifikasi, Integrasi, dan Tanda Tangan
- **Notifikasi**: Diusulkan notifikasi waktu pengisian agar guru diingatkan; opsi lanjutannya adalah integrasi email/API atau pemberitahuan internal sementara.
- **Biaya eksternal**: Integrasi ke layanan seperti WA/Telegram membutuhkan biaya token pihak ketiga.
- **Tanda tangan**: Tanda tangan tidak otomatis di web; disiapkan format PDF untuk diunduh dan ditandatangani secara manual.

## Peran Admin, Validasi, dan Hosting
- **Admin**: Hanya admin yang dapat mengubah/moderasi modul dan tampilan; guru hanya mengisi dan menggunakan konten praktik.
- **Validator**: Ada rencana tiga validator (ahli materi, ahli IT, ahli bahasa) untuk memvalidasi konten seperti materi, bahasa, dan fungsi web sebelum publikasi.
- **Hosting**: Sebelum hosting permanen dilakukan preview; hosting/domains berbiaya dan perlu perencanaan agar publikasi berjalan mulus.

## Fitur Sosial dan Dokumentasi
- **Video publik**: Video pembelajaran guru dapat dilihat guru lain dan diberi komentar untuk bahan belajar bersama, sementara detail penilaian hanya terlihat oleh guru terkait.
- **Dokumentasi**: Foto sekolah/guru diperlukan untuk penampilan halaman; kualitas foto harus baik agar hasil maksimal.

## Pelatihan, Timeline, dan Tindak Lanjut
- **Pelatihan**: Akan ada pelatihan untuk admin dan kepala sekolah setelah validasi selesai agar dapat mengoperasikan sistem.
- **Pengembangan bertahap**: Sistem dapat dikembangkan sambil berjalan (preview → hosting → penyesuaian fitur tambahan).
- **Langkah selanjutnya**: Kumpulkan masukan dari validator, perbaiki modul, siapkan preview hosting, dan jadwalkan pelatihan.
