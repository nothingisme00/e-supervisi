<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiStepperTest extends TestCase
{
    use RefreshDatabase;

    private function kepalaDanSupervisi(string $status = 'submitted'): array
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id, 'status' => $status]);

        return [$kepala, $supervisi];
    }

    public function test_halaman_show_menampilkan_stepper_langkah_1_aktif(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('data-stepper-step="1" data-status="aktif"', false);
        $response->assertSee('Tinjau Materi');
        $response->assertSee('Isi Rubrik');
    }

    public function test_stepper_menandai_langkah_selesai_sesuai_data(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('data-stepper-step="1" data-status="selesai"', false);
        $response->assertSee('data-stepper-step="2" data-status="mendatang"', false);
        $response->assertSee('data-stepper-step="4" data-status="mendatang"', false);
    }

    public function test_stepper_langkah_2_selesai_saat_rubrik_lengkap(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');
        \App\Models\RubrikItem::query()->delete();
        $item = \App\Models\RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);
        \App\Models\EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [$item->id => 2], null);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('data-stepper-step="2" data-status="selesai"', false);
    }

    public function test_show_menjadi_langkah_tinjau_materi_dengan_bar_aksi(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('Langkah 1');
        $response->assertSee('Lanjut: Isi Rubrik');
        $response->assertDontSee('Berikan Feedback');
    }
}
