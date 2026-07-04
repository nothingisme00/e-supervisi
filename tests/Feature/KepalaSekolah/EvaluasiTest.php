<?php

namespace Tests\Feature\KepalaSekolah;

use App\Models\User;
use App\Models\Supervisi;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiTest extends TestCase
{
    use RefreshDatabase;

    private function createKepala(): User
    {
        return User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
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
        $supervisi = Supervisi::factory()->submitted()->create();
        $response = $this->actingAs($this->createKepala())->get(route('kepala.evaluasi.show', $supervisi->id));
        $response->assertStatus(200);
    }

    public function test_kepala_can_start_review(): void
    {
        $kepala = $this->createKepala();
        $supervisi = Supervisi::factory()->submitted()->create();
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.startReview', $supervisi->id));
        $response->assertRedirect();
        $supervisi->refresh();
        $this->assertEquals('under_review', $supervisi->status);
    }

    public function test_start_review_fails_if_not_submitted(): void
    {
        $kepala = $this->createKepala();
        $supervisi = Supervisi::factory()->underReview()->create();
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.startReview', $supervisi->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_kepala_can_give_feedback(): void
    {
        $kepala = $this->createKepala();
        $supervisi = Supervisi::factory()->underReview()->create();
        $response = $this->actingAs($kepala)->post(route('kepala.evaluasi.feedback', $supervisi->id), [
            'komentar' => 'Feedback yang sangat baik dari kepala sekolah.',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', ['supervisi_id' => $supervisi->id]);
    }

    public function test_kepala_can_request_revision_via_feedback(): void
    {
        $kepala = $this->createKepala();
        $supervisi = Supervisi::factory()->underReview()->create();
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
        $supervisi = Supervisi::factory()->underReview()->create();
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
        $supervisi = Supervisi::factory()->underReview()->create();
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
}
