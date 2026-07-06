<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\User;
use App\Models\Supervisi;
use App\Models\Feedback;
use App\Models\EvaluasiRubrik;
use App\Models\RubrikItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiTest extends TestCase
{
    use RefreshDatabase;

    private function createKepala(): User
    {
        return User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
    }

    /**
     * complete() sekarang mensyaratkan rubrik penilaian lengkap terlebih dahulu.
     */
    private function fillRubrik(Supervisi $supervisi, User $kepala): void
    {
        $itemIds = RubrikItem::active()->pluck('id');
        EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, $itemIds->mapWithKeys(fn ($id) => [$id => 2])->all(), null);
    }

    public function test_kepala_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->createKepala())->get(route('kepala.dashboard'));
        $response->assertStatus(200);
    }

    public function test_kepala_can_view_evaluasi_list(): void
    {
        Supervisi::factory()->submitted()->count(2)->create();
        $response = $this->actingAs($this->createKepala())->get(route('kepala.evaluasi.index'));
        $response->assertStatus(200);
        $response->assertViewHas('supervisiList');
    }

    public function test_evaluasi_list_can_filter_by_status(): void
    {
        Supervisi::factory()->completed()->create();
        $response = $this->actingAs($this->createKepala())->get(route('kepala.evaluasi.index', ['status' => 'completed']));
        $response->assertStatus(200);
    }

    public function test_evaluasi_list_can_search(): void
    {
        $guru = User::factory()->guru()->create(['name' => 'Guru Unik Spesial']);
        Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);
        $response = $this->actingAs($this->createKepala())->get(route('kepala.evaluasi.index', ['search' => 'Unik']));
        $response->assertStatus(200);
    }

    public function test_kepala_can_view_supervisi_detail(): void
    {
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);
        $response = $this->actingAs($this->createKepala())->get(route('kepala.evaluasi.show', $supervisi->id));
        $response->assertStatus(200);
    }

    public function test_kepala_can_start_review(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.startReview', $supervisi->id));
        $response->assertRedirect();
        $supervisi->refresh();
        $this->assertEquals('under_review', $supervisi->status);
    }

    public function test_start_review_fails_if_not_submitted(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.startReview', $supervisi->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_kepala_can_give_feedback(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Feedback yang sangat baik dari kepala sekolah.',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', ['supervisi_id' => $supervisi->id]);
    }

    public function test_kepala_can_request_revision_via_feedback(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Perlu diperbaiki bagian refleksi secara keseluruhan.',
            'is_revision_request' => '1',
        ]);
        $response->assertRedirect();
        $supervisi->refresh();
        $this->assertEquals('revision', $supervisi->status);
    }

    public function test_kepala_can_request_revision(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.revision', $supervisi->id), [
            'revision_notes' => 'Mohon perbaiki bagian dokumen yang kurang lengkap.',
        ]);
        $response->assertRedirect();
        $supervisi->refresh();
        $this->assertEquals('revision', $supervisi->status);
    }

    public function test_kepala_can_complete_supervisi(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        $this->fillRubrik($supervisi, $kepala);
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id));
        $response->assertRedirect(route('kepala.evaluasi.index'));
        $supervisi->refresh();
        $this->assertEquals('completed', $supervisi->status);
    }

    public function test_guru_cannot_access_kepala_routes(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $response = $this->actingAs($guru)->get(route('kepala.dashboard'));
        $response->assertStatus(403);
    }

    public function test_admin_cannot_access_kepala_routes(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $response = $this->actingAs($admin)->get(route('kepala.dashboard'));
        $response->assertStatus(403);
    }

    // ============================
    // K1: tingkat/status filter on {id} methods
    // ============================

    public function test_kepala_cannot_view_supervisi_from_other_tingkat(): void
    {
        $kepala = $this->createKepala(); // tingkat SD
        $guru = User::factory()->guru()->create(['tingkat' => 'SMP']);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));
        $response->assertStatus(403);
    }

    public function test_kepala_cannot_view_draft_supervisi(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id));
        $response->assertStatus(403);
    }

    public function test_kepala_cannot_start_review_on_other_tingkat(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SMP']);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.startReview', $supervisi->id));
        $response->assertStatus(403);
        $supervisi->refresh();
        $this->assertEquals('submitted', $supervisi->status);
    }

    public function test_kepala_cannot_give_feedback_on_other_tingkat(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SMP']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Feedback yang sangat baik dari kepala sekolah.',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('feedback', ['supervisi_id' => $supervisi->id]);
    }

    public function test_kepala_cannot_complete_on_other_tingkat(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SMP']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id));
        $response->assertStatus(403);
        $supervisi->refresh();
        $this->assertEquals('under_review', $supervisi->status);
    }

    public function test_kepala_cannot_request_revision_on_other_tingkat(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SMP']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.revision', $supervisi->id), [
            'revision_notes' => 'Mohon perbaiki bagian dokumen yang kurang lengkap.',
        ]);
        $response->assertStatus(403);
        $supervisi->refresh();
        $this->assertEquals('under_review', $supervisi->status);
    }

    public function test_complete_fails_if_not_under_review(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id));
        $response->assertStatus(403);
        $supervisi->refresh();
        $this->assertEquals('submitted', $supervisi->status);
    }

    public function test_complete_sets_reviewed_by_and_reviewed_at(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);
        $this->fillRubrik($supervisi, $kepala);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id));
        $response->assertRedirect(route('kepala.evaluasi.index'));
        $supervisi->refresh();
        $this->assertEquals('completed', $supervisi->status);
        $this->assertEquals($kepala->id, $supervisi->reviewed_by);
        $this->assertNotNull($supervisi->reviewed_at);
    }

    public function test_give_feedback_fails_if_completed(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->completed()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Feedback yang sangat baik dari kepala sekolah.',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('feedback', ['supervisi_id' => $supervisi->id]);
    }

    public function test_give_feedback_fails_if_draft(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->create(['user_id' => $guru->id, 'status' => 'draft']);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Feedback yang sangat baik dari kepala sekolah.',
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('feedback', ['supervisi_id' => $supervisi->id]);
    }

    public function test_request_revision_fails_if_completed(): void
    {
        $kepala = $this->createKepala();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->completed()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.revision', $supervisi->id), [
            'revision_notes' => 'Mohon perbaiki bagian dokumen yang kurang lengkap.',
        ]);
        $response->assertStatus(403);
        $supervisi->refresh();
        $this->assertEquals('completed', $supervisi->status);
    }

    public function test_kepala_cannot_change_status_of_review_claimed_by_other(): void
    {
        $kepala = $this->createKepala();
        $otherReviewer = User::factory()->admin()->create();
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->underReview()->create([
            'user_id' => $guru->id,
            'reviewed_by' => $otherReviewer->id,
        ]);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.complete', $supervisi->id));
        $response->assertStatus(403);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.revision', $supervisi->id), [
            'revision_notes' => 'Mohon perbaiki bagian dokumen yang kurang lengkap.',
        ]);
        $response->assertStatus(403);

        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Perlu diperbaiki bagian refleksi secara keseluruhan.',
            'is_revision_request' => '1',
        ]);
        $response->assertStatus(403);

        $supervisi->refresh();
        $this->assertEquals('under_review', $supervisi->status);
    }
}
