<?php

namespace Tests\Unit\Models;

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

    public function test_has_status_constants(): void
    {
        $this->assertEquals('draft', Supervisi::STATUS_DRAFT);
        $this->assertEquals('submitted', Supervisi::STATUS_SUBMITTED);
        $this->assertEquals('under_review', Supervisi::STATUS_UNDER_REVIEW);
        $this->assertEquals('completed', Supervisi::STATUS_COMPLETED);
        $this->assertEquals('revision', Supervisi::STATUS_REVISION);
    }

    public function test_get_statuses_returns_all_statuses(): void
    {
        $statuses = Supervisi::getStatuses();
        $this->assertCount(5, $statuses);
        $this->assertContains('draft', $statuses);
        $this->assertContains('submitted', $statuses);
        $this->assertContains('under_review', $statuses);
        $this->assertContains('completed', $statuses);
        $this->assertContains('revision', $statuses);
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->guru()->create();
        $supervisi = Supervisi::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($supervisi->user->is($user));
    }

    public function test_belongs_to_reviewer(): void
    {
        $reviewer = User::factory()->admin()->create();
        $supervisi = Supervisi::factory()->create(['reviewed_by' => $reviewer->id]);

        $this->assertTrue($supervisi->reviewer->is($reviewer));
    }

    public function test_has_many_dokumen_evaluasi(): void
    {
        $supervisi = Supervisi::factory()->create();
        DokumenEvaluasi::factory()->count(3)->create(['supervisi_id' => $supervisi->id]);

        $this->assertCount(3, $supervisi->dokumenEvaluasi);
    }

    public function test_has_one_proses_pembelajaran(): void
    {
        $supervisi = Supervisi::factory()->create();
        $proses = ProsesPembelajaran::factory()->create(['supervisi_id' => $supervisi->id]);

        $this->assertTrue($supervisi->prosesPembelajaran->is($proses));
    }

    public function test_has_many_feedback(): void
    {
        $supervisi = Supervisi::factory()->submitted()->create();
        Feedback::factory()->count(2)->create(['supervisi_id' => $supervisi->id]);

        $this->assertCount(2, $supervisi->feedback);
    }

    public function test_scope_by_status(): void
    {
        Supervisi::factory()->draft()->count(2)->create();
        Supervisi::factory()->submitted()->count(3)->create();

        $this->assertCount(2, Supervisi::byStatus('draft')->get());
        $this->assertCount(3, Supervisi::byStatus('submitted')->get());
    }

    public function test_scope_pending(): void
    {
        Supervisi::factory()->submitted()->count(2)->create();
        Supervisi::factory()->draft()->count(1)->create();

        $this->assertCount(2, Supervisi::pending()->get());
    }

    public function test_scope_under_review(): void
    {
        Supervisi::factory()->underReview()->count(2)->create();
        Supervisi::factory()->submitted()->count(1)->create();

        $this->assertCount(2, Supervisi::underReview()->get());
    }

    public function test_scope_completed(): void
    {
        Supervisi::factory()->completed()->count(3)->create();
        Supervisi::factory()->draft()->count(1)->create();

        $this->assertCount(3, Supervisi::completed()->get());
    }

    public function test_scope_exclude_drafts(): void
    {
        Supervisi::factory()->draft()->count(2)->create();
        Supervisi::factory()->submitted()->count(1)->create();
        Supervisi::factory()->completed()->count(1)->create();

        $this->assertCount(2, Supervisi::excludeDrafts()->get());
    }

    public function test_tanggal_supervisi_is_cast_to_date(): void
    {
        $supervisi = Supervisi::factory()->submitted()->create();
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $supervisi->tanggal_supervisi);
    }

    public function test_reviewed_by_is_cast_to_integer(): void
    {
        // Driver PDO tanpa mysqlnd (umum di shared hosting) mengembalikan angka
        // sebagai string — tanpa cast, lockedByOther() salah menolak reviewer sendiri
        $supervisi = new Supervisi(['reviewed_by' => '2']);

        $this->assertSame(2, $supervisi->reviewed_by);
    }

    public function test_locked_by_other_is_false_for_same_reviewer_even_when_db_returns_string(): void
    {
        $kepala = User::factory()->kepalaSekolah()->create();
        $this->actingAs($kepala);

        $supervisi = Supervisi::factory()->underReview()->create(['reviewed_by' => $kepala->id]);
        // Simulasi driver PDO shared hosting yang mengembalikan angka sebagai string
        $supervisi->setRawAttributes(array_merge($supervisi->getAttributes(), [
            'reviewed_by' => (string) $kepala->id,
        ]));

        $this->assertFalse($supervisi->lockedByOther());
    }

    public function test_needs_revision_column_is_removed(): void
    {
        // R3: kolom mati — tidak pernah ditulis aplikasi, sumber data salah
        $this->assertFalse(\Illuminate\Support\Facades\Schema::hasColumn('supervisi', 'needs_revision'));
        $this->assertNotContains('needs_revision', (new Supervisi())->getFillable());
    }

    public function test_cascade_delete_related_records(): void
    {
        $supervisi = Supervisi::factory()->create();
        DokumenEvaluasi::factory()->count(2)->create(['supervisi_id' => $supervisi->id]);
        ProsesPembelajaran::factory()->create(['supervisi_id' => $supervisi->id]);
        Feedback::factory()->count(2)->create(['supervisi_id' => $supervisi->id]);

        $supervisiId = $supervisi->id;
        $supervisi->delete();

        $this->assertCount(0, DokumenEvaluasi::where('supervisi_id', $supervisiId)->get());
        $this->assertCount(0, ProsesPembelajaran::where('supervisi_id', $supervisiId)->get());
        $this->assertCount(0, Feedback::where('supervisi_id', $supervisiId)->get());
    }

    public function test_evaluasi_rubrik_relation_returns_hasone(): void
    {
        $supervisi = Supervisi::factory()->create();
        $kepala = User::factory()->kepalaSekolah()->create();
        $evaluasi = \App\Models\EvaluasiRubrik::create([
            'supervisi_id' => $supervisi->id,
            'reviewed_by' => $kepala->id,
            'skor_total' => 10,
            'skor_maksimal' => 20,
            'nilai_akhir' => 50,
            'predikat' => 'K',
        ]);

        $this->assertTrue($supervisi->evaluasiRubrik->is($evaluasi));
    }

    public function test_cascade_delete_removes_evaluasi_rubrik(): void
    {
        $supervisi = Supervisi::factory()->create();
        $kepala = User::factory()->kepalaSekolah()->create();
        \App\Models\EvaluasiRubrik::create([
            'supervisi_id' => $supervisi->id,
            'reviewed_by' => $kepala->id,
            'skor_total' => 10,
            'skor_maksimal' => 20,
            'nilai_akhir' => 50,
            'predikat' => 'K',
        ]);

        $supervisiId = $supervisi->id;
        $supervisi->delete();

        $this->assertCount(0, \App\Models\EvaluasiRubrik::where('supervisi_id', $supervisiId)->get());
    }
}
