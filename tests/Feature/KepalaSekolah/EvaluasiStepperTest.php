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
        // Label textarea form feedback (Card 5 lama) — kini hanya boleh ada di halaman feedback.
        $response->assertDontSee('Komentar dan Saran');
    }

    public function test_halaman_rubrik_menampilkan_stepper_dan_tombol_lanjut_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.rubrik', $supervisi->id));

        $response->assertSee('data-stepper-step="2" data-status="aktif"', false);
        $response->assertSee('Simpan & Lanjut Feedback');
        $response->assertSee('id="btnLanjutFeedback"', false);
        $response->assertSee('Simpan Draf');
    }

    public function test_show_completed_menawarkan_lihat_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('completed');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));

        $response->assertSee('Lihat Feedback');
        $response->assertDontSee('Lanjut: Isi Rubrik');
    }

    public function test_halaman_stepper_tidak_lazy_load_relasi(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review');
        \App\Models\Feedback::create([
            'supervisi_id' => $supervisi->id,
            'user_id' => $kepala->id,
            'komentar' => 'Feedback untuk uji lazy loading stepper.',
        ]);

        // Stepper membaca evaluasiRubrik.scores dan feedback.user di ketiga halaman;
        // relasi itu wajib di-eager-load agar tak ada kueri lazy per-render.
        $this->actingAs($kepala);

        \Illuminate\Support\Facades\DB::enableQueryLog();
        $this->get(route('kepala.evaluasi.show', $supervisi->id))->assertOk();
        $queriesShow = count(\Illuminate\Support\Facades\DB::getQueryLog());
        \Illuminate\Support\Facades\DB::flushQueryLog();

        $this->get(route('kepala.evaluasi.rubrik', $supervisi->id))->assertOk();
        $queriesRubrik = count(\Illuminate\Support\Facades\DB::getQueryLog());
        \Illuminate\Support\Facades\DB::disableQueryLog();

        $this->assertLessThanOrEqual(25, $queriesShow, "Kueri halaman show: {$queriesShow}");
        $this->assertLessThanOrEqual(25, $queriesRubrik, "Kueri halaman rubrik: {$queriesRubrik}");
    }
}
