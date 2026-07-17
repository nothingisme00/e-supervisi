<?php

namespace Tests\Unit;

use App\Models\Supervisi;
use App\Models\User;
use Tests\TestCase;

class SupervisiFilenameTest extends TestCase
{
    private function buatSupervisi(?string $mataPelajaran, ?string $tanggal): Supervisi
    {
        $user = new User();
        $user->name = 'Budi Santoso';
        $user->mata_pelajaran = $mataPelajaran;

        $supervisi = new Supervisi();
        $supervisi->tanggal_supervisi = $tanggal;
        $supervisi->setRelation('user', $user);

        return $supervisi;
    }

    public function test_nama_file_memuat_judul_guru_mapel_dan_tanggal(): void
    {
        $supervisi = $this->buatSupervisi('Matematika', '2026-07-15');

        $this->assertSame(
            'Rubrik Penilaian Supervisi - Budi Santoso - Matematika - 15 Juli 2026.pdf',
            $supervisi->rubrikPdfFilename(),
        );
    }

    public function test_nama_file_melewati_mapel_saat_kosong(): void
    {
        $supervisi = $this->buatSupervisi(null, '2026-07-15');

        $this->assertSame(
            'Rubrik Penilaian Supervisi - Budi Santoso - 15 Juli 2026.pdf',
            $supervisi->rubrikPdfFilename(),
        );
    }
}
