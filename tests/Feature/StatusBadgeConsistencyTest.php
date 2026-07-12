<?php

namespace Tests\Feature;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusBadgeConsistencyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * R9: label status baku di semua layar —
     * draft=Draft, submitted=Disubmit, under_review=Ditinjau, revision=Revisi, completed=Selesai
     */
    public function test_status_labels_are_consistent_across_screens(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $supervisi = Supervisi::factory()->create([
            'user_id' => $guru->id,
            'status' => 'under_review',
            'tanggal_supervisi' => now(),
        ]);

        // Guru home: under_review harus "Ditinjau", bukan "Direview"
        $this->actingAs($guru)->get(route('guru.home'))
            ->assertSee('Ditinjau')
            ->assertDontSee('Direview');

        $supervisi->update(['status' => 'completed', 'reviewed_at' => now()]);

        // Detail guru: completed harus "Selesai", bukan "Telah Ditinjau"
        $this->actingAs($guru)->get(route('guru.supervisi.detail', $supervisi->id))
            ->assertSee('Selesai')
            ->assertDontSee('Telah Ditinjau');

        // Detail admin: sama
        $admin = User::factory()->admin()->create(['must_change_password' => false]);
        $this->actingAs($admin)->get(route('admin.supervisi.show', $supervisi->id))
            ->assertDontSee('Telah Ditinjau');

        // Detail kepala sekolah: sama
        $kepala = User::factory()->kepalaSekolah()->create(['must_change_password' => false, 'tingkat' => 'SD']);
        $this->actingAs($kepala)->get(route('kepala.evaluasi.show', $supervisi->id))
            ->assertDontSee('Telah Ditinjau');

        // Halaman index (tab/filter) juga tidak boleh pakai label lama
        $this->actingAs($admin)->get(route('admin.supervisi.index'))
            ->assertDontSee('Telah Ditinjau');
        $this->actingAs($kepala)->get(route('kepala.evaluasi.index'))
            ->assertDontSee('Telah Ditinjau')
            ->assertDontSee('Direview');
    }

    /**
     * R9 perluasan: <x-status-badge> juga menjadi sumber tunggal untuk status
     * aktif/nonaktif (dipakai carousel, modul, dsb), bukan hanya status supervisi.
     */
    public function test_status_badge_component_renders_aktif_nonaktif(): void
    {
        $aktif = $this->blade('<x-status-badge status="aktif" />');
        $aktif->assertSee('Aktif');
        $aktif->assertSee('bg-emerald-100', false);

        $nonaktif = $this->blade('<x-status-badge status="nonaktif" />');
        $nonaktif->assertSee('Nonaktif');
        $nonaktif->assertSee('bg-gray-100', false);
    }
}
