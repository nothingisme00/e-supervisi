<?php

namespace Tests\Feature\Admin;

use App\Models\RubrikItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RubrikItemManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::factory()->admin()->create(['must_change_password' => false]);
    }

    public function test_admin_can_view_rubrik_items_index(): void
    {
        $response = $this->actingAs($this->createAdmin())->get(route('admin.rubrik-items.index'));

        $response->assertStatus(200);
    }

    public function test_guru_cannot_access_rubrik_items_management(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('admin.rubrik-items.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_rubrik_item(): void
    {
        $response = $this->actingAs($this->createAdmin())->post(route('admin.rubrik-items.store'), [
            'kode' => 'D.1.a',
            'section' => 'A',
            'section_label' => 'Kegiatan Pendahuluan',
            'kelompok_nomor' => 4,
            'kelompok_label' => 'Aspek Tambahan',
            'sub_label' => 'Aspek baru dari admin.',
            'urutan' => 99,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('rubrik_items', ['kode' => 'D.1.a', 'is_active' => true]);
    }

    public function test_admin_can_toggle_rubrik_item_active_status(): void
    {
        $item = RubrikItem::first();

        $response = $this->actingAs($this->createAdmin())->patch(route('admin.rubrik-items.toggle', $item->id));

        $response->assertRedirect();
        $item->refresh();
        $this->assertFalse($item->is_active);
    }

    public function test_inactive_item_excluded_from_kepala_rubrik_form(): void
    {
        $item = RubrikItem::first();
        $item->update(['is_active' => false]);

        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $guru = User::factory()->guru()->create(['tingkat' => 'SD']);
        $supervisi = \App\Models\Supervisi::factory()->underReview()->create(['user_id' => $guru->id, 'reviewed_by' => $kepala->id]);

        $response = $this->actingAs($kepala)->get(route('kepala.evaluasi.rubrik', $supervisi->id));

        $response->assertStatus(200);
        $response->assertDontSee($item->sub_label);
    }

    public function test_admin_can_update_predikat_threshold(): void
    {
        $predikatB = \App\Models\PredikatRubrik::where('kode', 'B')->first();

        $response = $this->actingAs($this->createAdmin())->put(route('admin.rubrik-items.predikat.update', $predikatB->id), [
            'batas_minimal' => 85,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('predikat_rubrik', ['id' => $predikatB->id, 'batas_minimal' => 85]);
    }

    public function test_changing_threshold_does_not_affect_old_evaluasi_predikat(): void
    {
        $item = RubrikItem::first();
        $kepala = User::factory()->kepalaSekolah()->create();
        $supervisi = \App\Models\Supervisi::factory()->underReview()->create();
        $evaluasi = \App\Models\EvaluasiRubrik::hitungDanSimpan($supervisi, $kepala->id, [$item->id => 2], null);
        $predikatLama = $evaluasi->predikat;

        $predikatB = \App\Models\PredikatRubrik::where('kode', 'B')->first();
        $this->actingAs($this->createAdmin())->put(route('admin.rubrik-items.predikat.update', $predikatB->id), [
            'batas_minimal' => 99,
        ]);

        $evaluasi->refresh();
        $this->assertSame($predikatLama, $evaluasi->predikat);
    }
}
