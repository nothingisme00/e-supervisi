<?php

namespace Tests\Unit\Models;

use App\Models\Feedback;
use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_belongs_to_supervisi(): void
    {
        $supervisi = Supervisi::factory()->submitted()->create();
        $feedback = Feedback::factory()->create(['supervisi_id' => $supervisi->id]);

        $this->assertTrue($feedback->supervisi->is($supervisi));
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->admin()->create();
        $feedback = Feedback::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($feedback->user->is($user));
    }

    public function test_has_parent_relationship(): void
    {
        $parent = Feedback::factory()->create();
        $reply = Feedback::factory()->create([
            'parent_id' => $parent->id,
            'supervisi_id' => $parent->supervisi_id,
        ]);

        $this->assertTrue($reply->parent->is($parent));
    }

    public function test_has_replies_relationship(): void
    {
        $parent = Feedback::factory()->create();
        $reply1 = Feedback::factory()->create([
            'parent_id' => $parent->id,
            'supervisi_id' => $parent->supervisi_id,
        ]);
        $reply2 = Feedback::factory()->create([
            'parent_id' => $parent->id,
            'supervisi_id' => $parent->supervisi_id,
        ]);

        $this->assertCount(2, $parent->replies);
    }

    public function test_replies_with_user_eager_loads_user(): void
    {
        $parent = Feedback::factory()->create();
        Feedback::factory()->create([
            'parent_id' => $parent->id,
            'supervisi_id' => $parent->supervisi_id,
        ]);

        $replies = $parent->repliesWithUser;
        $this->assertCount(1, $replies);
        $this->assertNotNull($replies->first()->user);
    }

    public function test_fillable_attributes(): void
    {
        $supervisi = Supervisi::factory()->submitted()->create();
        $user = User::factory()->create();

        $feedback = Feedback::create([
            'supervisi_id' => $supervisi->id,
            'user_id' => $user->id,
            'komentar' => 'Test komentar feedback',
            'is_revision_request' => true,
        ]);

        $this->assertEquals('Test komentar feedback', $feedback->komentar);
        $this->assertTrue((bool) $feedback->is_revision_request);
    }
}
