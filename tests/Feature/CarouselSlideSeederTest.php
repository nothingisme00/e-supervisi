<?php

namespace Tests\Feature;

use App\Models\CarouselSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CarouselSlideSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_is_idempotent_and_attaches_existing_sample_images(): void
    {
        Storage::fake('public');

        $this->seed(\Database\Seeders\CarouselSlideSeeder::class);
        $this->seed(\Database\Seeders\CarouselSlideSeeder::class);

        // Idempoten: seed ulang tidak menggandakan slide
        $this->assertEquals(3, CarouselSlide::count());

        // Gambar sampel yang nyata ada di public/images/carousel/samples/ ikut terpasang
        foreach (CarouselSlide::all() as $slide) {
            $this->assertNotNull($slide->image_path, "Slide '{$slide->title}' tanpa gambar");
            Storage::disk('public')->assertExists($slide->image_path);
        }
    }
}
