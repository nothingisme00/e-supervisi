<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Supervisi;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisiReviewTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->admin()->create(['must_change_password' => false]);
    }

    public function test_admin_can_view_supervisi_list(): void
    {
        $admin = $this->createAdmin();
        Supervisi::factory()->submitted()->count(3)->create();
        $response = $this->actingAs($admin)->get(route('admin.supervisi.index'));
        $response->assertStatus(200);
        $response->assertViewHas('supervisiList');
    }

    public function test_supervisi_list_filters_by_status(): void
    {
        $admin = $this->createAdmin();
        Supervisi::factory()->completed()->count(1)->create();
        $response = $this->actingAs($admin)->get(route('admin.supervisi.index', ['status' => 'completed']));
        $response->assertStatus(200);
        $response->assertViewHas('title', 'Telah Ditinjau');
    }

    public function test_admin_can_view_supervisi_detail(): void
    {
        $admin = $this->createAdmin();
        $supervisi = Supervisi::factory()->submitted()->create();
        $response = $this->actingAs($admin)->get(route('admin.supervisi.show', $supervisi->id));
        $response->assertStatus(200);
    }

    public function test_viewing_submitted_auto_marks_under_review(): void
    {
        $admin = $this->createAdmin();
        $supervisi = Supervisi::factory()->submitted()->create();
        $this->actingAs($admin)->get(route('admin.supervisi.show', $supervisi->id));
        $supervisi->refresh();
        $this->assertEquals(Supervisi::STATUS_UNDER_REVIEW, $supervisi->status);
        $this->assertEquals($admin->id, $supervisi->reviewed_by);
    }

    public function test_admin_can_give_feedback(): void
    {
        $admin = $this->createAdmin();
        $supervisi = Supervisi::factory()->underReview()->create();
        $response = $this->actingAs($admin)->post(route('admin.supervisi.feedback', $supervisi->id), [
            'komentar' => 'Feedback yang cukup panjang untuk validasi minimum.',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', ['supervisi_id' => $supervisi->id, 'user_id' => $admin->id]);
    }

    public function test_admin_can_mark_completed(): void
    {
        $admin = $this->createAdmin();
        $supervisi = Supervisi::factory()->underReview()->create();
        $this->actingAs($admin)->post(route('admin.supervisi.feedback', $supervisi->id), [
            'komentar' => 'Supervisi sudah bagus, selesai ditinjau lengkap.',
            'mark_completed' => '1',
        ]);
        $supervisi->refresh();
        $this->assertEquals(Supervisi::STATUS_COMPLETED, $supervisi->status);
    }

    public function test_feedback_requires_min_length(): void
    {
        $admin = $this->createAdmin();
        $supervisi = Supervisi::factory()->underReview()->create();
        $response = $this->actingAs($admin)->post(route('admin.supervisi.feedback', $supervisi->id), [
            'komentar' => 'short',
        ]);
        $response->assertSessionHasErrors('komentar');
    }

    public function test_admin_can_request_revision(): void
    {
        $admin = $this->createAdmin();
        $supervisi = Supervisi::factory()->underReview()->create();
        $response = $this->actingAs($admin)->post(route('admin.supervisi.revision', $supervisi->id), [
            'revision_notes' => 'Perbaiki bagian refleksi dan tambahkan dokumen yang kurang.',
        ]);
        $response->assertRedirect();
        $supervisi->refresh();
        $this->assertEquals(Supervisi::STATUS_REVISION, $supervisi->status);
        $this->assertDatabaseHas('feedback', ['supervisi_id' => $supervisi->id, 'is_revision_request' => true]);
    }

    public function test_admin_store_feedback_fails_if_draft_or_completed(): void
    {
        $admin = $this->createAdmin();

        foreach (['draft', 'completed'] as $status) {
            $supervisi = Supervisi::factory()->create(['status' => $status]);
            $response = $this->actingAs($admin)->post(route('admin.supervisi.feedback', $supervisi->id), [
                'komentar' => 'Feedback yang cukup panjang untuk validasi minimum.',
            ]);
            $response->assertStatus(403);
            $this->assertDatabaseMissing('feedback', ['supervisi_id' => $supervisi->id]);
        }
    }

    public function test_admin_mark_completed_fails_if_revision(): void
    {
        $admin = $this->createAdmin();
        $supervisi = Supervisi::factory()->create(['status' => 'revision']);

        $response = $this->actingAs($admin)->post(route('admin.supervisi.feedback', $supervisi->id), [
            'komentar' => 'Supervisi sudah bagus, selesai ditinjau lengkap.',
            'mark_completed' => '1',
        ]);
        $response->assertStatus(403);
        $supervisi->refresh();
        $this->assertEquals('revision', $supervisi->status);
    }

    public function test_admin_request_revision_fails_if_draft_or_completed(): void
    {
        $admin = $this->createAdmin();

        foreach (['draft', 'completed'] as $status) {
            $supervisi = Supervisi::factory()->create(['status' => $status]);
            $response = $this->actingAs($admin)->post(route('admin.supervisi.revision', $supervisi->id), [
                'revision_notes' => 'Perbaiki bagian refleksi dan tambahkan dokumen yang kurang.',
            ]);
            $response->assertStatus(403);
            $supervisi->refresh();
            $this->assertEquals($status, $supervisi->status);
        }
    }

    public function test_guru_cannot_access_admin_supervisi(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $response = $this->actingAs($guru)->get(route('admin.supervisi.index'));
        $response->assertStatus(403);
    }
}
