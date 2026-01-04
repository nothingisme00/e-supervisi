<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
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
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'subtitle', 'description']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = CarouselSlide::max('order') + 1;

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('carousel', 'public');
        }

        CarouselSlide::create($data);

        return redirect()->route('admin.carousel.index')
            ->with('success', 'Slide berhasil ditambahkan!');
    }

    /**
     * Update the specified slide.
     */
    public function update(Request $request, CarouselSlide $carousel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['title', 'subtitle', 'description']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($carousel->image_path) {
                Storage::disk('public')->delete($carousel->image_path);
            }
            $data['image_path'] = $request->file('image')->store('carousel', 'public');
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
            Storage::disk('public')->delete($carousel->image_path);
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
