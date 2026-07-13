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
}
