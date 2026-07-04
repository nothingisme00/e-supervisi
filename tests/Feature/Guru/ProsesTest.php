<?php

namespace Tests\Feature\Guru;

use App\Models\User;
use App\Models\Supervisi;
use App\Models\DokumenEvaluasi;
use App\Models\ProsesPembelajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProsesTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    private function createSupervisiWithAllDocs(User $guru): Supervisi
    {
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);
        $docs = ['capaian_pembelajaran','alur_tujuan_pembelajaran','kalender','program_tahunan','program_semester','modul_ajar','bahan_ajar'];
        foreach ($docs as $doc) {
            DokumenEvaluasi::factory()->create(['supervisi_id' => $supervisi->id, 'jenis_dokumen' => $doc]);
        }
        return $supervisi;
    }

    public function test_guru_can_access_proses_with_all_docs(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->createSupervisiWithAllDocs($guru);
        $response = $this->actingAs($guru)->get(route('guru.supervisi.proses', $supervisi->id));
        $response->assertStatus(200);
    }

    public function test_guru_redirected_without_all_docs(): void
    {
        $guru = $this->createGuru();
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);
        DokumenEvaluasi::factory()->create(['supervisi_id' => $supervisi->id, 'jenis_dokumen' => 'capaian_pembelajaran']);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.proses', $supervisi->id));
        $response->assertRedirect(route('guru.supervisi.evaluasi', $supervisi->id));
        $response->assertSessionHas('error');
    }

    public function test_guru_can_save_proses_data(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->createSupervisiWithAllDocs($guru);

        $response = $this->actingAs($guru)->postJson(route('guru.supervisi.proses.save', $supervisi->id), [
            'link_video' => 'https://youtube.com/watch?v=test',
            'refleksi_1' => 'Tujuan pembelajaran A',
            'refleksi_2' => 'Strategi B',
            'refleksi_3' => 'Tantangan C',
            'refleksi_4' => 'Respon D',
            'refleksi_5' => 'Rencana E',
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('proses_pembelajaran', ['supervisi_id' => $supervisi->id, 'link_video' => 'https://youtube.com/watch?v=test']);
    }

    public function test_guru_can_submit_supervisi(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->createSupervisiWithAllDocs($guru);
        ProsesPembelajaran::factory()->create(['supervisi_id' => $supervisi->id]);

        $response = $this->actingAs($guru)->postJson(route('guru.supervisi.submit', $supervisi->id));

        $response->assertJson(['success' => true]);
        $supervisi->refresh();
        $this->assertEquals(Supervisi::STATUS_SUBMITTED, $supervisi->status);
        $this->assertNotNull($supervisi->tanggal_supervisi);
    }

    public function test_submit_fails_without_complete_proses(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->createSupervisiWithAllDocs($guru);

        $response = $this->actingAs($guru)->postJson(route('guru.supervisi.submit', $supervisi->id));
        $response->assertStatus(400);
        $response->assertJson(['success' => false]);
    }

    public function test_submit_fails_with_incomplete_proses(): void
    {
        $guru = $this->createGuru();
        $supervisi = $this->createSupervisiWithAllDocs($guru);
        ProsesPembelajaran::factory()->incomplete()->create(['supervisi_id' => $supervisi->id]);

        $response = $this->actingAs($guru)->postJson(route('guru.supervisi.submit', $supervisi->id));
        $response->assertStatus(400);
    }

    public function test_guru_cannot_access_other_guru_proses(): void
    {
        $guru = $this->createGuru();
        $otherGuru = $this->createGuru();
        $supervisi = $this->createSupervisiWithAllDocs($otherGuru);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.proses', $supervisi->id));
        $response->assertStatus(404);
    }
}
