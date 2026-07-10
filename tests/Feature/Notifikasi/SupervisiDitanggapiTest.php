<?php

namespace Tests\Feature\Notifikasi;

use App\Models\RubrikItem;
use App\Models\EvaluasiRubrik;
use App\Models\Supervisi;
use App\Models\User;
use App\Notifications\SupervisiDitanggapi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SupervisiDitanggapiTest extends TestCase
{
    use RefreshDatabase;

    private function guruDanKepala(): array
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);

        return [$guru, $kepala];
    }

    public function test_kepala_feedback_mengirim_jenis_feedback_ke_guru(): void
    {
        Notification::fake();
        [$guru, $kepala] = $this->guruDanKepala();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Ini komentar minimal sepuluh karakter.',
        ])->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, function ($n) {
            return $n->jenis === 'feedback';
        });
    }

    public function test_kepala_request_revision_mengirim_jenis_revisi(): void
    {
        Notification::fake();
        [$guru, $kepala] = $this->guruDanKepala();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $this->actingAs($kepala)->post(route('kepala.evaluasi.revision', $supervisi->id), [
            'revision_notes' => 'Mohon perbaiki bagian ini ya.',
        ])->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, fn ($n) => $n->jenis === 'revisi');
    }

    public function test_kepala_complete_mengirim_jenis_selesai(): void
    {
        Notification::fake();
        [$guru, $kepala] = $this->guruDanKepala();

        RubrikItem::query()->delete();
        $item = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id, 'status' => 'under_review', 'reviewed_by' => $kepala->id]);
        EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [$item->id => 2], null);

        $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id))->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, fn ($n) => $n->jenis === 'selesai');
    }

    public function test_admin_feedback_mengirim_jenis_feedback(): void
    {
        Notification::fake();
        [$guru] = $this->guruDanKepala();
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $this->actingAs($admin)->post(route('admin.supervisi.feedback', $supervisi->id), [
            'komentar' => 'Komentar admin minimal sepuluh.',
        ])->assertRedirect();

        Notification::assertSentTo($guru, SupervisiDitanggapi::class, fn ($n) => $n->jenis === 'feedback');
    }
}
