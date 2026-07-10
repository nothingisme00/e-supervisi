<?php

namespace Tests\Unit;

use App\Models\EvaluasiRubrik;
use App\Models\PredikatRubrik;
use App\Models\RubrikItem;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiRubrikCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_hitung_predikat_returns_sangat_baik_for_value_at_or_above_91(): void
    {
        $this->assertSame('SB', EvaluasiRubrik::hitungPredikat(91));
    }

    /**
     * Ambang predikat disimpan di database (tabel predikat_rubrik), bukan konstanta
     * di kode, supaya admin bisa mengubahnya nanti tanpa deploy ulang.
     */
    public function test_hitung_predikat_respects_custom_threshold_from_database(): void
    {
        PredikatRubrik::query()->delete();
        PredikatRubrik::create(['kode' => 'X', 'label' => 'Istimewa', 'batas_minimal' => 50, 'urutan' => 1]);

        $this->assertSame('X', EvaluasiRubrik::hitungPredikat(55));
    }

    public function test_hitung_predikat_default_seed_boundaries(): void
    {
        $this->assertSame('SB', EvaluasiRubrik::hitungPredikat(95));
        $this->assertSame('B', EvaluasiRubrik::hitungPredikat(81));
        $this->assertSame('C', EvaluasiRubrik::hitungPredikat(75));
        $this->assertSame('K', EvaluasiRubrik::hitungPredikat(50));
    }

    /**
     * skor_maksimal SELALU dihitung dari jumlah item aktif saat itu (bukan angka
     * tetap 94) supaya evaluasi lama tetap valid meski admin ubah jumlah item nanti.
     */
    public function test_hitung_dan_simpan_menghitung_skor_dan_predikat_dari_item_aktif(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);
        $item2 = RubrikItem::create(['kode' => 'T.2', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 2', 'urutan' => 2, 'is_active' => true]);

        $kepala = User::factory()->kepalaSekolah()->create();
        $supervisi = Supervisi::factory()->underReview()->create();

        $evaluasi = EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [
            $item1->id => 2,
            $item2->id => 1,
        ], 'Sudah cukup baik');

        $this->assertSame(3, $evaluasi->skor_total);
        $this->assertSame(4, $evaluasi->skor_maksimal);
        $this->assertEquals(75.00, $evaluasi->nilai_akhir);
        $this->assertSame('C', $evaluasi->predikat);
        $this->assertSame('Sudah cukup baik', $evaluasi->masukan_umum);
    }

    public function test_hitung_dan_simpan_menyimpan_skor_per_item(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);
        $item2 = RubrikItem::create(['kode' => 'T.2', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 2', 'urutan' => 2, 'is_active' => true]);

        $kepala = User::factory()->kepalaSekolah()->create();
        $supervisi = Supervisi::factory()->underReview()->create();

        $evaluasi = EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [
            $item1->id => 2,
            $item2->id => 1,
        ], null);

        $this->assertSame(2, $evaluasi->scores()->count());
        $this->assertDatabaseHas('evaluasi_rubrik_scores', ['evaluasi_rubrik_id' => $evaluasi->id, 'rubrik_item_id' => $item1->id, 'skor' => 2]);
        $this->assertDatabaseHas('evaluasi_rubrik_scores', ['evaluasi_rubrik_id' => $evaluasi->id, 'rubrik_item_id' => $item2->id, 'skor' => 1]);
    }
}
