<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubrik_items', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('section', 1);
            $table->string('section_label');
            $table->unsignedTinyInteger('kelompok_nomor');
            $table->string('kelompok_label');
            $table->string('sub_label');
            $table->unsignedSmallInteger('urutan');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        DB::table('rubrik_items')->insert(array_map(
            fn (array $row, int $i) => array_merge($row, ['urutan' => $i + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]),
            $this->items(),
            array_keys($this->items())
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('rubrik_items');
    }

    /**
     * 47 aspek "Instrumen Supervisi Pelaksanaan Pembelajaran" (Yayasan Az-Zahroh).
     * @return array<int, array{kode:string,section:string,section_label:string,kelompok_nomor:int,kelompok_label:string,sub_label:string}>
     */
    private function items(): array
    {
        $rows = [];

        $push = function (string $kode, string $section, string $sectionLabel, int $kelompokNomor, string $kelompokLabel, string $subLabel) use (&$rows) {
            $rows[] = [
                'kode' => $kode,
                'section' => $section,
                'section_label' => $sectionLabel,
                'kelompok_nomor' => $kelompokNomor,
                'kelompok_label' => $kelompokLabel,
                'sub_label' => $subLabel,
            ];
        };

        // A. Kegiatan Pendahuluan
        $push('A.1.a', 'A', 'Kegiatan Pendahuluan', 1, 'Orientasi', 'Guru menyiapkan fisik dan psikis peserta didik dengan menyapa dan memberi salam.');
        $push('A.1.b', 'A', 'Kegiatan Pendahuluan', 1, 'Orientasi', 'Guru menyampaikan rencana kegiatan baik individual, kerja kelompok, dan melakukan observasi.');
        $push('A.2.a', 'A', 'Kegiatan Pendahuluan', 2, 'Motivasi', 'Guru mengajukan pertanyaan yang menantang untuk memotivasi Peserta Didik.');
        $push('A.2.b', 'A', 'Kegiatan Pendahuluan', 2, 'Motivasi', 'Guru menyampaikan manfaat dan tujuan pembelajaran.');
        $push('A.3.a', 'A', 'Kegiatan Pendahuluan', 3, 'Apersepsi', 'Guru menyampaikan kompetensi yang akan dicapai peserta didik.');
        $push('A.3.b', 'A', 'Kegiatan Pendahuluan', 3, 'Apersepsi', 'Guru mengaitkan materi dengan materi pembelajaran sebelumnya.');
        $push('A.3.c', 'A', 'Kegiatan Pendahuluan', 3, 'Apersepsi', 'Guru mendemonstrasikan sesuatu yang terkait dengan materi pembelajaran.');

        // B. Kegiatan Inti
        $push('B.1.a', 'B', 'Kegiatan Inti', 1, 'Penguasaan materi pembelajaran', 'Guru menyampaikan materi sesuai dengan Tujuan Pembelajaran.');
        $push('B.1.b', 'B', 'Kegiatan Inti', 1, 'Penguasaan materi pembelajaran', 'Guru mengkaitkan materi dengan pengetahuan lain yang relevan, perkembangan iptek, dan kehidupan nyata (kontekstual).');
        $push('B.1.c', 'B', 'Kegiatan Inti', 1, 'Penguasaan materi pembelajaran', 'Guru menyajikan materi pembelajaran dengan jelas dan menyenangkan.');
        $push('B.1.d', 'B', 'Kegiatan Inti', 1, 'Penguasaan materi pembelajaran', 'Guru menyajikan materi secara sistematis (mudah ke sulit, dari konkrit ke abstrak).');

        $push('B.2.a', 'B', 'Kegiatan Inti', 2, 'Penerapan strategi pembelajaran yang mendidik', 'Guru melaksanakan pembelajaran sesuai dengan kompetensi yang akan dicapai.');
        $push('B.2.b', 'B', 'Kegiatan Inti', 2, 'Penerapan strategi pembelajaran yang mendidik', 'Guru melaksanakan pembelajaran yang menumbuhkan partisipasi aktif peserta didik dalam mengajukan pertanyaan.');
        $push('B.2.c', 'B', 'Kegiatan Inti', 2, 'Penerapan strategi pembelajaran yang mendidik', 'Guru melaksanakan pembelajaran yang menumbuhkan partisipasi aktif peserta didik dalam mengemukakan pendapat.');
        $push('B.2.d', 'B', 'Kegiatan Inti', 2, 'Penerapan strategi pembelajaran yang mendidik', 'Guru melaksanakan pembelajaran yang mengembangkan keterampilan peserta didik sesuai dengan materi ajar.');
        $push('B.2.e', 'B', 'Kegiatan Inti', 2, 'Penerapan strategi pembelajaran yang mendidik', 'Guru melaksanakan pembelajaran yang memungkinkan tumbuhnya kebiasaan dan sikap positif.');
        $push('B.2.f', 'B', 'Kegiatan Inti', 2, 'Penerapan strategi pembelajaran yang mendidik', 'Guru melaksanakan pembelajaran sesuai dengan alur pembelajaran pada modul ajar.');
        $push('B.2.g', 'B', 'Kegiatan Inti', 2, 'Penerapan strategi pembelajaran yang mendidik', 'Guru melaksanakan pembelajaran sesuai dengan alokasi waktu yang direncanakan.');

        $push('B.3.a', 'B', 'Kegiatan Inti', 3, 'Aktivitas Pembelajaran HOTS dan Kecakapan Abad 21 (4C)', 'Guru melaksanakan pembelajaran yang mengasah kemampuan Creativity peserta didik.');
        $push('B.3.b', 'B', 'Kegiatan Inti', 3, 'Aktivitas Pembelajaran HOTS dan Kecakapan Abad 21 (4C)', 'Guru melaksanakan pembelajaran yang mengasah kemampuan Critical Thinking peserta didik.');
        $push('B.3.c', 'B', 'Kegiatan Inti', 3, 'Aktivitas Pembelajaran HOTS dan Kecakapan Abad 21 (4C)', 'Guru melaksanakan pembelajaran yang mengasah kemampuan Communication peserta didik.');
        $push('B.3.d', 'B', 'Kegiatan Inti', 3, 'Aktivitas Pembelajaran HOTS dan Kecakapan Abad 21 (4C)', 'Guru melaksanakan pembelajaran yang mengasah kemampuan Collaboration peserta didik.');

        $push('B.4.a', 'B', 'Kegiatan Inti', 4, 'Kualitas pembelajaran: manajemen pengelolaan kelas', 'Terciptanya suasana kelas yang kondusif dan menyenangkan.');
        $push('B.4.b', 'B', 'Kegiatan Inti', 4, 'Kualitas pembelajaran: manajemen pengelolaan kelas', 'Terlaksananya penerapan prinsip disiplin positif (reinforcement atau pembentukan perilaku adaptif) dalam menegakkan aturan kelas yang telah disepakati bersama.');

        $push('B.5.a', 'B', 'Kegiatan Inti', 5, 'Kualitas pembelajaran: dukungan afektif', 'Terlaksananya kondisi dimana guru mengkomunikasikan pesan bahwa guru percaya akan kemampuan semua murid untuk belajar dan berprestasi secara akademik.');
        $push('B.5.b', 'B', 'Kegiatan Inti', 5, 'Kualitas pembelajaran: dukungan afektif', 'Terlaksananya kondisi dimana guru memberikan perhatian dan bantuan ekstra kepada murid sesuai dengan kebutuhan belajar tiap murid.');
        $push('B.5.c', 'B', 'Kegiatan Inti', 5, 'Kualitas pembelajaran: dukungan afektif', 'Terlaksananya penyampaian hasil evaluasi dan perilaku murid dengan cara yang mendorong murid untuk terus meningkatkan kemampuannya.');

        $push('B.6.a', 'B', 'Kegiatan Inti', 6, 'Kualitas pembelajaran: aktivasi kognitif', 'Terlaksananya praktik adaptasi pengajaran oleh guru sebagai respon atas umpan balik dari respon murid terhadap kebutuhan belajarnya.');
        $push('B.6.b', 'B', 'Kegiatan Inti', 6, 'Kualitas pembelajaran: aktivasi kognitif', 'Terlaksananya penjelasan oleh guru yang terstruktur tentang materi pelajaran, serta pemberian contoh tentang cara menerapkannya.');
        $push('B.6.c', 'B', 'Kegiatan Inti', 6, 'Kualitas pembelajaran: aktivasi kognitif', 'Terlaksananya praktik pengajaran yang mendorong kolaborasi dan komunikasi antar murid dalam konteks memaknai dan memahami materi ajar.');

        $push('B.7.a', 'B', 'Kegiatan Inti', 7, 'Pembelajaran Literasi dan Numerasi', 'Terlaksananya praktik pengajaran yang mendorong keterampilan literasi murid.');
        $push('B.7.b', 'B', 'Kegiatan Inti', 7, 'Pembelajaran Literasi dan Numerasi', 'Terlaksananya praktik pengajaran yang mendorong keterampilan numerasi murid.');

        $push('B.8.a', 'B', 'Kegiatan Inti', 8, 'Pemanfaatan sumber belajar/media pembelajaran', 'Guru menunjukkan keterampilan dalam penggunaan sumber belajar yang bervariasi.');
        $push('B.8.b', 'B', 'Kegiatan Inti', 8, 'Pemanfaatan sumber belajar/media pembelajaran', 'Guru menunjukkan keterampilan dalam penggunaan media pembelajaran.');
        $push('B.8.c', 'B', 'Kegiatan Inti', 8, 'Pemanfaatan sumber belajar/media pembelajaran', 'Guru melibatkan peserta didik dalam pemanfaatan sumber belajar.');
        $push('B.8.d', 'B', 'Kegiatan Inti', 8, 'Pemanfaatan sumber belajar/media pembelajaran', 'Guru melibatkan peserta didik dalam pemanfaatan media pembelajaran.');
        $push('B.8.e', 'B', 'Kegiatan Inti', 8, 'Pemanfaatan sumber belajar/media pembelajaran', 'Menghasilkan kesan yang menarik.');

        $push('B.9.a', 'B', 'Kegiatan Inti', 9, 'Penggunaan Bahasa yang benar dan tepat dalam pembelajaran', 'Menggunakan bahasa lisan secara jelas dan lancar.');
        $push('B.9.b', 'B', 'Kegiatan Inti', 9, 'Penggunaan Bahasa yang benar dan tepat dalam pembelajaran', 'Menggunakan bahasa tulis yang baik dan benar.');

        // C. Kegiatan Penutup
        $push('C.1.a', 'C', 'Kegiatan Penutup', 1, 'Proses rangkuman, refleksi, dan tindak lanjut', 'Guru memfasilitasi dan membimbing peserta didik merangkum materi pelajaran.');
        $push('C.1.b', 'C', 'Kegiatan Penutup', 1, 'Proses rangkuman, refleksi, dan tindak lanjut', 'Guru menunjukkan aktivitas belajar yang bertujuan meningkatkan pengetahuan mengajar.');
        $push('C.1.c', 'C', 'Kegiatan Penutup', 1, 'Proses rangkuman, refleksi, dan tindak lanjut', 'Guru menunjukkan aktivitas untuk mengevaluasi dan merefleksikan praktik pengajaran yang telah diterapkan, terutama dari sisi dampaknya terhadap belajar murid.');
        $push('C.1.d', 'C', 'Kegiatan Penutup', 1, 'Proses rangkuman, refleksi, dan tindak lanjut', 'Terlaksananya penerapan pendekatan baru dalam cara, bahan, dan/atau perencanaan, pelaksanaan, sampai evaluasi pembelajaran.');
        $push('C.1.e', 'C', 'Kegiatan Penutup', 1, 'Proses rangkuman, refleksi, dan tindak lanjut', 'Guru melaksanakan tindak lanjut dengan memberikan arahan kegiatan berikutnya dan tugas perbaikan dan pengayaan secara individu atau kelompok.');

        $push('C.2.a', 'C', 'Kegiatan Penutup', 2, 'Pelaksanaan Penilaian Hasil Belajar', 'Guru melaksanakan Penilaian Sikap melalui observasi.');
        $push('C.2.b', 'C', 'Kegiatan Penutup', 2, 'Pelaksanaan Penilaian Hasil Belajar', 'Guru melaksanakan Penilaian Pengetahuan melalui tes lisan, tulisan.');
        $push('C.2.c', 'C', 'Kegiatan Penutup', 2, 'Pelaksanaan Penilaian Hasil Belajar', 'Guru melaksanakan Penilaian Keterampilan; penilaian kinerja, projek, produk dan portofolio.');

        return $rows;
    }
};
