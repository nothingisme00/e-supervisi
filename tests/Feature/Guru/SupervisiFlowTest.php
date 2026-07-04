<?php

namespace Tests\Feature\Guru;

use App\Models\User;
use App\Models\Supervisi;
use App\Models\DokumenEvaluasi;
use App\Models\ProsesPembelajaran;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisiFlowTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    public function test_guru_can_access_home(): void
    {
        $response = $this->actingAs($this->createGuru())->get(route('guru.home'));
        $response->assertStatus(200);
    }

    public function test_guru_can_view_my_supervisi(): void
    {
        $response = $this->actingAs($this->createGuru())->get(route('guru.my-supervisi'));
        $response->assertStatus(200);
    }

    public function test_guru_can_create_supervisi(): void
    {
        $guru = $this->createGuru();
        $response = $this->actingAs($guru)->get(route('guru.supervisi.create'));
        $response->assertStatus(200);
    }

    public function test_store_rejects_second_active_supervisi(): void
    {
        $guru = $this->createGuru();
        $existing = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->post(route('guru.supervisi.store'));

        $response->assertRedirect(route('guru.supervisi.continue', $existing->id));
        $this->assertEquals(1, Supervisi::where('user_id', $guru->id)->count());
    }

    public function test_guru_cannot_delete_revision_supervisi(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->revision()->create(['user_id' => $guru->id]);
        $feedback = \App\Models\Feedback::create([
            'supervisi_id' => $supervisi->id,
            'user_id' => User::factory()->kepalaSekolah()->create()->id,
            'komentar' => 'Mohon perbaiki bagian refleksi.',
            'is_revision_request' => true,
        ]);

        $response = $this->actingAs($guru)->delete(route('guru.supervisi.delete', $supervisi->id));

        $this->assertDatabaseHas('supervisi', ['id' => $supervisi->id]);
        $this->assertDatabaseHas('feedback', ['id' => $feedback->id]);
    }

    public function test_guru_with_active_supervisi_redirected_to_continue(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.create'));
        $response->assertRedirect(route('guru.supervisi.continue', $supervisi->id));
    }

    public function test_guru_can_store_new_supervisi(): void
    {
        $guru = $this->createGuru();
        $response = $this->actingAs($guru)->post(route('guru.supervisi.store'));
        $response->assertRedirect();
        $this->assertDatabaseHas('supervisi', ['user_id' => $guru->id, 'status' => 'draft']);
    }

    public function test_guru_can_view_evaluasi_page(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.evaluasi', $supervisi->id));
        $response->assertStatus(200);
    }

    public function test_guru_can_view_own_supervisi_detail(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));
        $response->assertStatus(200);
    }

    public function test_guru_cannot_view_other_guru_detail(): void
    {
        $guru = $this->createGuru();
        $otherGuru = $this->createGuru();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $otherGuru->id]);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id));
        $response->assertStatus(404);
    }

    public function test_guru_can_view_other_supervisi(): void
    {
        $guru = $this->createGuru();
        $otherGuru = $this->createGuru();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $otherGuru->id]);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.view', $supervisi->id));
        $response->assertStatus(200);
    }

    public function test_guru_can_add_comment(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->post(route('guru.supervisi.comment', $supervisi->id), [
            'komentar' => 'Ini adalah komentar test',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', ['supervisi_id' => $supervisi->id, 'user_id' => $guru->id]);
    }

    public function test_cannot_comment_on_draft_supervisi(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->post(route('guru.supervisi.comment', $supervisi->id), [
            'komentar' => 'Test comment',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_guru_can_delete_draft_supervisi(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->delete(route('guru.supervisi.delete', $supervisi->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('supervisi', ['id' => $supervisi->id]);
    }

    public function test_guru_cannot_delete_submitted_supervisi(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->delete(route('guru.supervisi.delete', $supervisi->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');
        // Supervisi should still exist
        $this->assertDatabaseHas('supervisi', ['id' => $supervisi->id]);
    }

    public function test_guru_can_check_documents(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.check-documents', $supervisi->id));
        $response->assertJson(['complete' => false, 'count' => 0, 'required' => 7]);
    }

    public function test_admin_cannot_access_guru_routes(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $response = $this->actingAs($admin)->get(route('guru.home'));
        $response->assertStatus(403);
    }
}
