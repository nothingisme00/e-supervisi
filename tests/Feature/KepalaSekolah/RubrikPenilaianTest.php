<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\RubrikItem;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RubrikPenilaianTest extends TestCase
{
    use RefreshDatabase;

    private function createKepala(): User
    {
        return User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
    }

    public function test_kepala_can_view_rubrik_form(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.rubrik', $supervisi->id));

        $response->assertStatus(200);
    }

    public function test_kepala_cannot_view_rubrik_form_for_other_tingkat(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SMP']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.rubrik', $supervisi->id));

        $response->assertStatus(403);
    }

    public function test_kepala_can_submit_complete_rubrik(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);
        $item2 = RubrikItem::create(['kode' => 'T.2', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 2', 'urutan' => 2, 'is_active' => true]);

        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), [
            'skor' => [$item1->id => 2, $item2->id => 1],
            'masukan_umum' => 'Sudah baik secara umum.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('evaluasi_rubrik', [
            'supervisi_id' => $supervisi->id,
            'skor_total' => 3,
            'skor_maksimal' => 4,
            'predikat' => 'C',
        ]);
    }

    public function test_submit_rubrik_rejects_invalid_score_value(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);

        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), [
            'skor' => [$item1->id => 3],
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('evaluasi_rubrik', ['supervisi_id' => $supervisi->id]);
    }

    public function test_kepala_cannot_submit_rubrik_when_locked_by_other(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);

        $kepala = $this->createKepala();
        $otherReviewer = User::factory()->admin()->create();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $otherReviewer->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), [
            'skor' => [$item1->id => 2],
        ]);

        $response->assertStatus(403);
    }

    public function test_kepala_editing_existing_rubrik_updates_not_duplicates(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);

        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), ['skor' => [$item1->id => 1]]);
        $this->actingAs($kepala)->post(route('kepala.evaluasi.rubrik.store', $supervisi->id), ['skor' => [$item1->id => 2]]);

        $this->assertSame(1, \App\Models\EvaluasiRubrik::where('supervisi_id', $supervisi->id)->count());
        $this->assertDatabaseHas('evaluasi_rubrik', ['supervisi_id' => $supervisi->id, 'skor_total' => 2]);
    }

    public function test_complete_fails_if_rubrik_not_filled(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $supervisi->refresh();
        $this->assertEquals('under_review', $supervisi->status);
    }

    public function test_kepala_can_download_rubrik_pdf(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);

        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        \App\Models\EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [$item1->id => 2], null);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.rubrik.pdf', $supervisi->id));

        $response->assertStatus(200);
        $this->assertSame('application/pdf', $response->headers->get('content-type'));
    }

    public function test_rubrik_pdf_contains_reviewer_name_in_signature(): void
    {
        RubrikItem::query()->delete();
        $item1 = RubrikItem::create(['kode' => 'T.1', 'section' => 'A', 'section_label' => 'Tes', 'kelompok_nomor' => 1, 'kelompok_label' => 'Tes', 'sub_label' => 'Tes 1', 'urutan' => 1, 'is_active' => true]);

        $kepala = $this->createKepala();
        $kepala->name = 'Kepala Sekolah Unik Testcase';
        $kepala->save();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        $evaluasi = \App\Models\EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [$item1->id => 2], null);

        $this->assertSame($kepala->id, $evaluasi->reviewer->id);
    }
}
