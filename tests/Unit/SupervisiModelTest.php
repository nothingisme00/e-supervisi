<?php

namespace Tests\Unit;

use App\Models\Supervisi;
use App\Models\User;
use App\Models\DokumenEvaluasi;
use App\Models\ProsesPembelajaran;
use App\Models\Feedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisiModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test supervisi memiliki relasi dengan user
     */
    public function test_supervisi_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $supervisi = Supervisi::create([
            'user_id' => $user->id,
            'status' => Supervisi::STATUS_DRAFT,
            'tanggal_supervisi' => now()->addDays(7),
        ]);

        $this->assertInstanceOf(User::class, $supervisi->user);
        $this->assertEquals($user->id, $supervisi->user->id);
    }

    /**
     * Test supervisi memiliki relasi dengan dokumen evaluasi
     */
    public function test_supervisi_has_many_dokumen_evaluasi(): void
    {
        $supervisi = Supervisi::create([
            'user_id' => User::factory()->create()->id,
            'status' => Supervisi::STATUS_DRAFT,
            'tanggal_supervisi' => now()->addDays(7),
        ]);

        DokumenEvaluasi::create([
            'supervisi_id' => $supervisi->id,
            'jenis_dokumen' => 'rpp',
            'nama_file' => 'test.pdf',
            'path_file' => 'documents/test.pdf'
        ]);

        $this->assertCount(1, $supervisi->dokumenEvaluasi);
    }

    /**
     * Test konstanta status tersedia
     */
    public function test_status_constants_are_defined(): void
    {
        $this->assertEquals('draft', Supervisi::STATUS_DRAFT);
        $this->assertEquals('submitted', Supervisi::STATUS_SUBMITTED);
        $this->assertEquals('in_progress', Supervisi::STATUS_IN_PROGRESS);
        $this->assertEquals('reviewed', Supervisi::STATUS_REVIEWED);
        $this->assertEquals('completed', Supervisi::STATUS_COMPLETED);
        $this->assertEquals('revision_requested', Supervisi::STATUS_REVISION_REQUESTED);
    }

    /**
     * Test method getStatuses mengembalikan semua status
     */
    public function test_get_statuses_returns_all_statuses(): void
    {
        $statuses = Supervisi::getStatuses();

        $this->assertIsArray($statuses);
        $this->assertContains(Supervisi::STATUS_DRAFT, $statuses);
        $this->assertContains(Supervisi::STATUS_SUBMITTED, $statuses);
        $this->assertContains(Supervisi::STATUS_IN_PROGRESS, $statuses);
        $this->assertContains(Supervisi::STATUS_REVIEWED, $statuses);
        $this->assertContains(Supervisi::STATUS_COMPLETED, $statuses);
        $this->assertContains(Supervisi::STATUS_REVISION_REQUESTED, $statuses);
    }

    /**
     * Test cascade delete menghapus relasi terkait
     */
    public function test_cascade_delete_removes_related_records(): void
    {
        $supervisi = Supervisi::create([
            'user_id' => User::factory()->create()->id,
            'status' => Supervisi::STATUS_DRAFT,
            'tanggal_supervisi' => now()->addDays(7),
        ]);

        // Buat data terkait
        DokumenEvaluasi::create([
            'supervisi_id' => $supervisi->id,
            'jenis_dokumen' => 'rpp',
            'nama_file' => 'test.pdf',
            'path_file' => 'documents/test.pdf'
        ]);

        ProsesPembelajaran::create([
            'supervisi_id' => $supervisi->id,
            'kegiatan_pendahuluan' => 'Test'
        ]);

        // Hapus supervisi
        $supervisi->delete();

        // Pastikan data terkait terhapus
        $this->assertDatabaseMissing('dokumen_evaluasi', [
            'supervisi_id' => $supervisi->id
        ]);
        $this->assertDatabaseMissing('proses_pembelajaran', [
            'supervisi_id' => $supervisi->id
        ]);
    }
}
