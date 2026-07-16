<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiFeedbackPageTest extends TestCase
{
    use RefreshDatabase;

    private function kepalaDanSupervisi(string $status = 'under_review', string $tingkatKepala = 'SD'): array
    {
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => $tingkatKepala]);
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id, 'status' => $status]);

        return [$kepala, $supervisi];
    }

    public function test_halaman_feedback_tampil_dengan_stepper_langkah_3(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertOk();
        $response->assertSee('data-stepper-step="3" data-status="aktif"', false);
        $response->assertSee('Berikan Feedback');
        $response->assertSee('Langkah 3');
    }

    public function test_thread_menandai_feedback_yang_sudah_direvisi(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();
        \App\Models\Feedback::create([
            'supervisi_id' => $supervisi->id,
            'user_id' => $kepala->id,
            'komentar' => 'Feedback dari siklus sebelum revisi guru.',
            'sudah_direvisi' => true,
        ]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertSee('Sudah Direvisi');
    }

    public function test_halaman_feedback_403_untuk_kepala_beda_tingkat(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('under_review', 'SMP');

        $this->actingAs($kepala)
            ->get(route('kepala.evaluasi.feedback.show', $supervisi->id))
            ->assertForbidden();
    }

    public function test_stepper_langkah_3_selesai_setelah_kepala_memberi_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();
        \App\Models\Feedback::create([
            'supervisi_id' => $supervisi->id,
            'user_id' => $kepala->id,
            'komentar' => 'Feedback dari kepala sekolah untuk stepper.',
        ]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertSee('data-stepper-step="3" data-status="selesai"', false);
    }

    public function test_halaman_feedback_menampilkan_ajakan_mulai_review_saat_submitted(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('submitted');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertOk();
        $response->assertSee('Review Belum Dimulai');
        $response->assertSee('Ke Langkah 1');
        $response->assertDontSee('Komentar dan Saran');
    }

    public function test_simpan_rubrik_dengan_lanjut_redirect_ke_halaman_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();
        \App\Models\RubrikItem::query()->delete();
        $item = \App\Models\RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), [
            'skor' => [$item->id => 2],
            'lanjut' => '1',
        ]);

        $response->assertRedirect(route('kepala.evaluasi.feedback.show', $supervisi->id));
    }

    public function test_kirim_feedback_redirect_ke_halaman_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Feedback minimal sepuluh karakter.',
        ]);

        $response->assertRedirect(route('kepala.evaluasi.feedback.show', $supervisi->id));
    }

    public function test_minta_revisi_redirect_ke_halaman_feedback(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.revision', $supervisi->id), [
            'revision_notes' => 'Mohon perbaiki bagian pendahuluan modul ajar.',
        ]);

        $response->assertRedirect(route('kepala.evaluasi.feedback.show', $supervisi->id));
    }

    public function test_halaman_feedback_completed_menampilkan_status_selesai_tanpa_tombol_tandai(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('completed');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertOk();
        $response->assertSee('Supervisi Telah Selesai Ditinjau');
        $response->assertDontSee('Tandai Selesai');
    }

    public function test_halaman_feedback_revision_menampilkan_form_dan_tanpa_tombol_tandai(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi('revision');

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.feedback.show', $supervisi->id));

        $response->assertOk();
        $response->assertSee('data-stepper-step="3" data-status="aktif"', false);
        $response->assertSee('Komentar dan Saran');
        $response->assertDontSee('Tandai Selesai');
    }

    public function test_simpan_rubrik_tanpa_lanjut_kembali_ke_rubrik_sebagai_draf(): void
    {
        [$kepala, $supervisi] = $this->kepalaDanSupervisi();
        \App\Models\RubrikItem::query()->delete();
        $item = \App\Models\RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), [
            'skor' => [$item->id => 1],
        ]);

        $response->assertRedirect(route('kepala.evaluasi.rubrik', $supervisi->id));
        $response->assertSessionHas('success', 'Draf rubrik tersimpan');
    }
}
