<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the carousel slides.
     */
    public function index()
    {
        $slides = CarouselSlide::ordered()->get();
        return view('admin.carousel.index', compact('slides'));
    }

    /**
     * Store a newly created slide.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // Increased to 10MB
                'is_active' => 'boolean',
            ]);

            $data = $request->only(['title', 'description']);
            $data['is_active'] = $request->boolean('is_active', true);
            $data['order'] = CarouselSlide::max('order') + 1;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                \Log::info('Carousel upload attempt', [
                    'filename' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);
                
                // Kompresi dan optimasi gambar carousel ke WebP (max 1920px, ~200-400KB)
                $path = $this->imageService->uploadCarousel($file);
                if ($path) {
                    $data['image_path'] = $path;
                    \Log::info('Carousel upload success', ['path' => $path]);
                } else {
                    \Log::error('Carousel upload failed - ImageService returned false');
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'Gagal mengupload gambar. Silakan coba lagi.']);
                }
            }

            CarouselSlide::create($data);

            return redirect()->route('admin.carousel.index')
                ->with('success', 'Slide berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Carousel store error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified slide.
     */
    public function update(Request $request, CarouselSlide $carousel)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'description']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($carousel->image_path) {
                $this->imageService->deleteImage($carousel->image_path);
            }
            // Kompresi dan optimasi gambar carousel ke WebP (max 1920px, ~200-400KB)
            $path = $this->imageService->uploadCarousel($request->file('image'));
            if ($path) {
                $data['image_path'] = $path;
            }
        }

        $carousel->update($data);

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Slide berhasil diperbarui!');
    }

    /**
     * Remove the specified slide.
     */
    public function destroy(CarouselSlide $carousel)
    {
        // Delete image file
        if ($carousel->image_path) {
            $this->imageService->deleteImage($carousel->image_path);
        }

        $carousel->delete();

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Slide berhasil dihapus!');
    }

    /**
     * Reorder slides.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:carousel_slides,id',
        ]);

        foreach ($request->order as $index => $id) {
            CarouselSlide::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle slide active status.
     */
    public function toggle(CarouselSlide $carousel)
    {
        $carousel->update(['is_active' => !$carousel->is_active]);

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Status slide berhasil diubah!');
    }
}
