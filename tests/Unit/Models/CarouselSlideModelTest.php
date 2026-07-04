<?php

namespace Tests\Unit\Models;

use App\Models\CarouselSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarouselSlideModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_scope_active(): void
    {
        CarouselSlide::factory()->count(3)->create(['is_active' => true]);
        CarouselSlide::factory()->count(2)->create(['is_active' => false]);

        $this->assertCount(3, CarouselSlide::active()->get());
    }

    public function test_scope_ordered(): void
    {
        CarouselSlide::factory()->create(['order' => 3]);
        CarouselSlide::factory()->create(['order' => 1]);
        CarouselSlide::factory()->create(['order' => 2]);

        $ordered = CarouselSlide::ordered()->get();
        $this->assertEquals(1, $ordered->first()->order);
        $this->assertEquals(3, $ordered->last()->order);
    }

    public function test_image_url_with_full_url(): void
    {
        $slide = CarouselSlide::factory()->create(['image_path' => 'https://example.com/image.jpg']);
        $this->assertEquals('https://example.com/image.jpg', $slide->image_url);
    }

    public function test_image_url_with_images_path(): void
    {
        $slide = CarouselSlide::factory()->create(['image_path' => 'images/carousel/samples/test.jpg']);
        $this->assertStringContainsString('images/carousel/samples/test.jpg', $slide->image_url);
    }

    public function test_image_url_with_storage_path(): void
    {
        $slide = CarouselSlide::factory()->create(['image_path' => 'carousel/test.webp']);
        $this->assertStringContainsString('storage/carousel/test.webp', $slide->image_url);
    }

    public function test_image_url_returns_null_when_no_path(): void
    {
        $slide = CarouselSlide::factory()->create(['image_path' => null]);
        $this->assertNull($slide->image_url);
    }

    public function test_is_active_is_cast_to_boolean(): void
    {
        $slide = CarouselSlide::factory()->create(['is_active' => 1]);
        $this->assertIsBool($slide->is_active);
    }

    public function test_order_is_cast_to_integer(): void
    {
        $slide = CarouselSlide::factory()->create(['order' => '5']);
        $this->assertIsInt($slide->order);
    }
}
