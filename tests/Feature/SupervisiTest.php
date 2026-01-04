<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Supervisi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guru dapat membuat supervisi
     */
    public function test_guru_can_create_supervisi(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        
        $response = $this->actingAs($guru)
            ->post('/guru/supervisi/store', [
                'tanggal_supervisi' => now()->addDays(7)->format('Y-m-d'),
                'catatan' => 'Test supervisi'
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('supervisi', [
            'user_id' => $guru->id,
            'status' => Supervisi::STATUS_DRAFT
        ]);
    }

    /**
     * Test admin tidak dapat membuat supervisi
     */
    public function test_admin_cannot_create_supervisi(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)
            ->get('/guru/supervisi/create');
        
        $response->assertForbidden();
    }

    /**
     * Test kepala sekolah tidak dapat membuat supervisi
     */
    public function test_kepala_sekolah_cannot_create_supervisi(): void
    {
        $kepalaSekolah = User::factory()->create(['role' => 'kepala_sekolah']);
        
        $response = $this->actingAs($kepalaSekolah)
            ->get('/guru/supervisi/create');
        
        $response->assertForbidden();
    }

    /**
     * Test guru dapat melihat supervisi miliknya
     */
    public function test_guru_can_view_own_supervisi(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $supervisi = Supervisi::create([
            'user_id' => $guru->id,
            'status' => Supervisi::STATUS_DRAFT,
            'tanggal_supervisi' => now()->addDays(7),
            'catatan' => 'Test supervisi'
        ]);
        
        $response = $this->actingAs($guru)
            ->get('/guru/supervisi/' . $supervisi->id . '/detail');
        
        $response->assertOk();
    }

    /**
     * Test validasi tanggal supervisi harus di masa depan
     */
    public function test_tanggal_supervisi_must_be_future_date(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        
        $response = $this->actingAs($guru)
            ->post('/guru/supervisi/store', [
                'tanggal_supervisi' => now()->subDays(1)->format('Y-m-d'),
                'catatan' => 'Test supervisi'
            ]);
        
        $response->assertSessionHasErrors('tanggal_supervisi');
    }
}
