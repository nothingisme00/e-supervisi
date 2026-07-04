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

    public function test_needs_revision_is_cast_to_boolean(): void
    {
        $supervisi = Supervisi::factory()->create(['needs_revision' => 1]);
        $this->assertIsBool($supervisi->needs_revision);
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
}
