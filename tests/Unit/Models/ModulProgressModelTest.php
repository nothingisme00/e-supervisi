<?php

namespace Tests\Unit\Models;

use App\Models\Modul;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulProgressModelTest extends TestCase
{
    use RefreshDatabase;

    private function makeProgress(int $halaman, int $jumlahHalaman): ModulProgress
    {
        $modul = Modul::factory()->create(['jumlah_halaman' => $jumlahHalaman]);
        $guru = User::factory()->guru()->create();

        return ModulProgress::create([
            'user_id' => $guru->id,
            'modul_id' => $modul->id,
            'halaman_terjauh' => $halaman,
        ]);
    }

    public function test_persen_dihitung_dari_halaman_terjauh(): void
    {
        $this->assertSame(30, $this->makeProgress(3, 10)->persen());
    }

    public function test_persen_dibatasi_100_saat_pdf_diganti_lebih_pendek(): void
    {
        // halaman_terjauh 8 tapi PDF baru hanya 4 halaman
        $this->assertSame(100, $this->makeProgress(8, 4)->persen());
    }

    public function test_pasangan_user_modul_unik(): void
    {
        $progress = $this->makeProgress(1, 10);

        $this->expectException(\Illuminate\Database\QueryException::class);
        ModulProgress::create([
            'user_id' => $progress->user_id,
            'modul_id' => $progress->modul_id,
            'halaman_terjauh' => 2,
        ]);
    }
}
