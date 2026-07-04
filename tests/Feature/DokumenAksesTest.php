<?php

namespace Tests\Feature;

use App\Models\DokumenEvaluasi;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DokumenAksesTest extends TestCase
{
    use RefreshDatabase;

    private function makeDokumen(Supervisi $supervisi): DokumenEvaluasi
    {
        $path = 'supervisi/' . $supervisi->id . '/test.pdf';
        Storage::disk('local')->put($path, '%PDF-1.4 test');

        return DokumenEvaluasi::factory()->create([
            'supervisi_id' => $supervisi->id,
            'path_file' => $path,
            'nama_file' => 'test.pdf',
        ]);
    }

    public function test_guru_can_preview_own_document(): void
    {
        Storage::fake('local');
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);
        $dokumen = $this->makeDokumen($supervisi);

        $response = $this->actingAs($guru)->get(route('guru.supervisi.preview', $dokumen->id));
        $response->assertStatus(200);
    }

    public function test_guru_peer_preview_follows_draft_visibility(): void
    {
        Storage::fake('local');
        $pemilik = User::factory()->guru()->create();
        $penonton = User::factory()->guru()->create(['must_change_password' => false]);

        // Non-draft milik rekan: boleh (fitur lihat rekan)
        $submitted = Supervisi::factory()->submitted()->create(['user_id' => $pemilik->id]);
        $dokumenSubmitted = $this->makeDokumen($submitted);
        $this->actingAs($penonton)
            ->get(route('guru.supervisi.preview', $dokumenSubmitted->id))
            ->assertStatus(200);

        // Draft milik rekan: ditolak
        $draft = Supervisi::factory()->draft()->create(['user_id' => $pemilik->id]);
        $dokumenDraft = $this->makeDokumen($draft);
        $this->actingAs($penonton)
            ->get(route('guru.supervisi.preview', $dokumenDraft->id))
            ->assertStatus(403);
    }

    public function test_kepala_can_preview_document_from_own_tingkat(): void
    {
        Storage::fake('local');
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);
        $dokumen = $this->makeDokumen($supervisi);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.preview', $dokumen->id));
        $response->assertStatus(200);
    }

    public function test_kepala_and_admin_can_download_document_from_private_disk(): void
    {
        Storage::fake('local');
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->submitted()->create(['user_id' => $guru->id]);
        $dokumen = $this->makeDokumen($supervisi);

        $this->actingAs($kepala)
            ->get(route('kepala.evaluasi.download', $dokumen->id))
            ->assertStatus(200)
            ->assertDownload('test.pdf');

        $this->actingAs($admin)
            ->get(route('admin.supervisi.download', $dokumen->id))
            ->assertStatus(200)
            ->assertDownload('test.pdf');
    }

    public function test_uploaded_document_is_stored_on_private_disk(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $guru = User::factory()->guru()->create(['must_change_password' => false]);
        $supervisi = Supervisi::factory()->draft()->create(['user_id' => $guru->id]);

        $response = $this->actingAs($guru)->post(route('guru.supervisi.upload', $supervisi->id), [
            'jenis_dokumen' => 'modul_ajar',
            'file' => \Illuminate\Http\UploadedFile::fake()->create('modul.pdf', 100, 'application/pdf'),
        ]);

        $response->assertStatus(200);
        $dokumen = \App\Models\DokumenEvaluasi::where('supervisi_id', $supervisi->id)->firstOrFail();
        Storage::disk('local')->assertExists($dokumen->path_file);
        Storage::disk('public')->assertMissing($dokumen->path_file);
    }
}
