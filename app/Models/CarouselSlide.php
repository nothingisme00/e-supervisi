<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CarouselSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope for active slides only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering slides
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Get the image URL
     * Supports:
     * - Relative path dari storage: 'carousel/image.jpg' → /storage/carousel/image.jpg
     * - Path dari public: 'images/carousel/samples/file.jpg' → /images/carousel/samples/file.jpg
     * - Full URL: 'https://...' → https://...
     */
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            // Jika sudah full URL (http/https), return as is
            if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
                return $this->image_path;
            }
            
            // Jika path dari public/images (static assets), gunakan asset()
            if (str_starts_with($this->image_path, 'images/')) {
                return asset($this->image_path);
            }
            
            // Default: path relatif dari disk 'public' (carousel/, avatars/, dll).
            // Pakai URL disk agar ikut ke object storage/bucket di produksi,
            // bukan di-hardcode ke symlink /storage lokal.
            return Storage::disk('public')->url($this->image_path);
        }
        return null;
    }
}
