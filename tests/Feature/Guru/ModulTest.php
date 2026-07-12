<?php

namespace Tests\Feature\Guru;

use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModulTest extends TestCase
{
    use RefreshDatabase;

    private function createGuru(): User
    {
        return User::factory()->guru()->create(['must_change_password' => false]);
    }

    public function test_guru_can_view_modul_list_with_progress(): void
    {
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['judul' => 'Modul Literasi', 'jumlah_halaman' => 10]);
        ModulProgress::create(['user_id' => $guru->id, 'modul_id' => $modul->id, 'halaman_terjauh' => 6]);

        $response = $this->actingAs($guru)->get(route('guru.modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Modul Literasi');
        $response->assertSee('60%');
    }

    public function test_inactive_modul_hidden_from_list(): void
    {
        Modul::factory()->create(['judul' => 'Modul Nonaktif', 'is_active' => false]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index'));

        $response->assertDontSee('Modul Nonaktif');
    }

    public function test_list_can_be_filtered_by_kategori(): void
    {
        $kategoriA = ModulKategori::factory()->create(['nama' => 'Pedagogik']);
        $kategoriB = ModulKategori::factory()->create(['nama' => 'Numerasi']);
        Modul::factory()->create(['judul' => 'Modul Pedagogik Satu', 'modul_kategori_id' => $kategoriA->id]);
        Modul::factory()->create(['judul' => 'Modul Numerasi Satu', 'modul_kategori_id' => $kategoriB->id]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index', ['kategori' => $kategoriA->id]));

        $response->assertSee('Modul Pedagogik Satu');
        $response->assertDontSee('Modul Numerasi Satu');
    }

    public function test_empty_state_shown_when_no_modul(): void
    {
        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.index'));

        $response->assertStatus(200);
        $response->assertSee('Belum ada modul');
    }

    public function test_admin_cannot_access_guru_modul_list(): void
    {
        $admin = User::factory()->admin()->create(['must_change_password' => false]);

        $response = $this->actingAs($admin)->get(route('guru.modul.index'));

        $response->assertStatus(403);
    }

    public function test_opening_baca_page_creates_progress_row(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create();

        $response = $this->actingAs($guru)->get(route('guru.modul.show', $modul->id));

        $response->assertStatus(200);
        $this->assertDatabaseHas('modul_progress', [
            'user_id' => $guru->id,
            'modul_id' => $modul->id,
            'halaman_terjauh' => 1,
        ]);
    }

    public function test_baca_page_contains_reader_contract_attributes(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 7]);
        \Illuminate\Support\Facades\Storage::disk('local')->put($modul->file_path, 'dummy');

        $response = $this->actingAs($guru)->get(route('guru.modul.show', $modul->id));

        $response->assertSee('id="modul-reader"', false);
        $response->assertSee('data-jumlah-halaman="7"', false);
        $response->assertSee(route('guru.modul.file', $modul->id));
    }

    public function test_baca_page_contains_progress_saved_affordance(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create();
        \Illuminate\Support\Facades\Storage::disk('local')->put($modul->file_path, 'dummy');

        $response = $this->actingAs($guru)->get(route('guru.modul.show', $modul->id));

        // Affordance "Progres tersimpan" (id dipakai modul-reader.js) tersembunyi default.
        $response->assertSee('id="progress-saved"', false);
        $response->assertSee('Progres tersimpan');
    }

    public function test_file_endpoint_streams_pdf(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create();
        \Illuminate\Support\Facades\Storage::disk('local')->put($modul->file_path, '%PDF-1.4 dummy');

        $response = $this->actingAs($guru)->get(route('guru.modul.file', $modul->id));

        $response->assertStatus(200);
    }

    public function test_progress_only_increases(): void
    {
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);

        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 5]);
        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 3]);

        $this->assertDatabaseHas('modul_progress', [
            'user_id' => $guru->id,
            'modul_id' => $modul->id,
            'halaman_terjauh' => 5,
        ]);
    }

    public function test_missing_pdf_file_shows_friendly_message(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(); // file tidak pernah ditulis ke storage

        $response = $this->actingAs($guru)->get(route('guru.modul.show', $modul->id));

        $response->assertStatus(200);
        $response->assertSee('File modul tidak ditemukan');
    }

    public function test_inactive_modul_returns_404_on_baca(): void
    {
        $modul = Modul::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.show', $modul->id));

        $response->assertStatus(404);
    }

    public function test_inactive_modul_returns_404_on_file(): void
    {
        $modul = Modul::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->createGuru())->get(route('guru.modul.file', $modul->id));

        $response->assertStatus(404);
    }

    public function test_inactive_modul_returns_404_on_progress(): void
    {
        $modul = Modul::factory()->create(['is_active' => false]);

        $response = $this->actingAs($this->createGuru())->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 1]);

        $response->assertStatus(404);
    }

    public function test_progress_rejects_page_out_of_range(): void
    {
        $guru = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);

        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 0])->assertStatus(422);
        $this->actingAs($guru)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 11])->assertStatus(422);
    }

    public function test_progress_is_per_user(): void
    {
        $guruA = $this->createGuru();
        $guruB = $this->createGuru();
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);

        $this->actingAs($guruA)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 8]);
        $this->actingAs($guruB)->postJson(route('guru.modul.progress', $modul->id), ['halaman' => 2]);

        $this->assertDatabaseHas('modul_progress', ['user_id' => $guruA->id, 'halaman_terjauh' => 8]);
        $this->assertDatabaseHas('modul_progress', ['user_id' => $guruB->id, 'halaman_terjauh' => 2]);
    }

    public function test_file_endpoint_404_when_file_missing(): void
    {
        \Illuminate\Support\Facades\Storage::fake('local');
        $guru = $this->createGuru();
        $modul = Modul::factory()->create();

        $response = $this->actingAs($guru)->get(route('guru.modul.file', $modul->id));

        $response->assertStatus(404);
    }
}
