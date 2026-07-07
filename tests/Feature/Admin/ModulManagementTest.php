<?php

namespace Tests\Feature\Admin;

use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Support\MakesPdfFixture;
use Tests\TestCase;

class ModulManagementTest extends TestCase
{
    use RefreshDatabase;
    use MakesPdfFixture;

    private function createAdmin(): User
    {
        return User::factory()->admin()->create(['must_change_password' => false]);
    }

    private function fakePdfUpload(int $pages = 3): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('modul.pdf', $this->pdfContent($pages));
    }

    public function test_admin_can_view_modul_index(): void
    {
        $response = $this->actingAs($this->createAdmin())->get(route('admin.modul.index'));

        $response->assertStatus(200);
    }

    public function test_guru_cannot_access_modul_management(): void
    {
        $guru = User::factory()->guru()->create(['must_change_password' => false]);

        $response = $this->actingAs($guru)->get(route('admin.modul.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_modul_with_page_count(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Kurikulum Merdeka',
            'deskripsi' => 'Deskripsi singkat.',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(3),
        ]);

        $response->assertRedirect(route('admin.modul.index'));
        $this->assertDatabaseHas('moduls', [
            'judul' => 'Modul Kurikulum Merdeka',
            'jumlah_halaman' => 3,
            'is_active' => true,
        ]);
        $this->assertCount(1, Storage::disk('local')->allFiles('modul'));
    }

    public function test_corrupt_pdf_is_rejected_without_leftover_file(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Rusak',
            'modul_kategori_id' => $kategori->id,
            'file' => UploadedFile::fake()->createWithContent('rusak.pdf', 'bukan konten pdf'),
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('moduls', 0);
        $this->assertEmpty(Storage::disk('local')->allFiles('modul'));
    }

    public function test_admin_can_create_modul_with_video_links(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Bervideo',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(2),
            'videos' => [
                ['judul' => 'Pengantar', 'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
            ],
        ]);

        $this->assertDatabaseHas('modul_videos', ['judul' => 'Pengantar']);
    }

    public function test_invalid_youtube_url_is_rejected(): void
    {
        Storage::fake('local');
        $kategori = ModulKategori::factory()->create();

        $response = $this->actingAs($this->createAdmin())->post(route('admin.modul.store'), [
            'judul' => 'Modul Video Salah',
            'modul_kategori_id' => $kategori->id,
            'file' => $this->fakePdfUpload(2),
            'videos' => [
                ['judul' => 'Salah', 'youtube_url' => 'https://vimeo.com/12345'],
            ],
        ]);

        $response->assertSessionHasErrors('videos.0.youtube_url');
    }

    public function test_replacing_pdf_keeps_progress_and_updates_page_count(): void
    {
        Storage::fake('local');
        $modul = Modul::factory()->create(['jumlah_halaman' => 10]);
        $guru = User::factory()->guru()->create();
        ModulProgress::create(['user_id' => $guru->id, 'modul_id' => $modul->id, 'halaman_terjauh' => 5]);

        $this->actingAs($this->createAdmin())->put(route('admin.modul.update', $modul->id), [
            'judul' => $modul->judul,
            'modul_kategori_id' => $modul->modul_kategori_id,
            'file' => $this->fakePdfUpload(4),
        ]);

        $modul->refresh();
        $this->assertSame(4, $modul->jumlah_halaman);
        $this->assertDatabaseHas('modul_progress', ['modul_id' => $modul->id, 'halaman_terjauh' => 5]);
    }

    public function test_admin_can_toggle_modul(): void
    {
        $modul = Modul::factory()->create(['is_active' => true]);

        $this->actingAs($this->createAdmin())->patch(route('admin.modul.toggle', $modul->id));

        $this->assertFalse($modul->refresh()->is_active);
    }

    public function test_admin_can_create_and_toggle_kategori(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post(route('admin.modul.kategori.store'), ['nama' => 'Literasi Digital']);
        $this->assertDatabaseHas('modul_kategoris', ['nama' => 'Literasi Digital', 'is_active' => true]);

        $kategori = ModulKategori::where('nama', 'Literasi Digital')->first();
        $this->actingAs($admin)->patch(route('admin.modul.kategori.toggle', $kategori->id));
        $this->assertFalse($kategori->refresh()->is_active);
    }
}
