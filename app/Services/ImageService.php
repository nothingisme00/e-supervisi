<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    protected $manager;
    protected $gdAvailable = false;

    // Format output default (webp lebih efisien, jpeg untuk kompatibilitas)
    protected const OUTPUT_FORMAT = 'webp';

    public function __construct()
    {
        // Check if GD extension is available
        $this->gdAvailable = extension_loaded('gd');
        
        if ($this->gdAvailable) {
            try {
                // Create ImageManager with GD driver
                $driver = new \Intervention\Image\Drivers\Gd\Driver();
                $this->manager = new ImageManager($driver);
            } catch (\Exception $e) {
                $this->gdAvailable = false;
                \Log::warning('ImageService: GD driver initialization failed, falling back to simple upload. Error: ' . $e->getMessage());
            }
        } else {
            \Log::info('ImageService: GD extension not available, using simple file upload without optimization.');
        }
    }

    /**
     * Check if GD is available for image processing
     */
    public function isGdAvailable(): bool
    {
        return $this->gdAvailable && $this->manager !== null;
    }

    /**
     * Check if WebP is supported by GD
     */
    protected function isWebPSupported(): bool
    {
        return $this->isGdAvailable() && function_exists('imagewebp') && (imagetypes() & IMG_WEBP);
    }

    /**
     * Simple file upload without optimization (fallback when GD not available)
     * 
     * @param UploadedFile $file
     * @param string $path
     * @return string|false
     */
    protected function simpleUpload(UploadedFile $file, string $path = 'images')
    {
        try {
            $extension = $file->getClientOriginalExtension() ?: 'jpg';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $fullPath = $path . '/' . $filename;
            
            // Store file directly without optimization
            Storage::disk('public')->put($fullPath, file_get_contents($file));
            
            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Simple image upload failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload and optimize image with WebP conversion for smaller file size
     * Falls back to simple upload if GD is not available
     * 
     * @param UploadedFile $file
     * @param string $path
     * @param int $maxWidth
     * @param int $quality Quality (60-80 for WebP is equivalent to 85-95 JPEG)
     * @param bool $useWebP Whether to convert to WebP format
     * @return string|false
     */
    public function uploadAndOptimize(UploadedFile $file, string $path = 'images', int $maxWidth = 1200, int $quality = 80, bool $useWebP = true)
    {
        // Fallback to simple upload if GD not available
        if (!$this->isGdAvailable()) {
            return $this->simpleUpload($file, $path);
        }

        try {
            // Determine output format
            $outputFormat = ($useWebP && $this->isWebPSupported()) ? 'webp' : 'jpeg';
            $extension = $outputFormat === 'webp' ? 'webp' : 'jpg';

            // Generate unique filename with correct extension
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $fullPath = $path . '/' . $filename;

            // Load image
            $image = $this->manager->read($file);

            // Get original dimensions
            $originalWidth = $image->width();

            // Resize if image is larger than maxWidth
            if ($originalWidth > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            // Encode with optimal compression based on format
            if ($outputFormat === 'webp') {
                // WebP: quality 75-80 gives excellent results with small file size
                $encodedImage = $image->toWebp($quality);
            } else {
                // JPEG fallback: slightly higher quality to compensate
                $encodedImage = $image->toJpeg($quality + 5);
            }

            // Store optimized image
            Storage::disk('public')->put($fullPath, $encodedImage);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            // Fallback to simple upload on error
            return $this->simpleUpload($file, $path);
        }
    }

    /**
     * Upload and optimize avatar (small file size, 300x300)
     * Falls back to simple upload if GD is not available
     * 
     * @param UploadedFile $file
     * @return string|false
     */
    public function uploadAvatar(UploadedFile $file)
    {
        // Fallback to simple upload if GD not available
        if (!$this->isGdAvailable()) {
            return $this->simpleUpload($file, 'avatars');
        }

        try {
            $useWebP = $this->isWebPSupported();
            $extension = $useWebP ? 'webp' : 'jpg';
            $filename = uniqid() . '_avatar_' . time() . '.' . $extension;
            $fullPath = 'avatars/' . $filename;

            // Create square avatar (300x300 - optimal for avatar display)
            $image = $this->manager->read($file);
            
            // Resize and crop to square
            $image->cover(300, 300);

            // Encode with good quality for faces
            if ($useWebP) {
                $encodedImage = $image->toWebp(80);
            } else {
                $encodedImage = $image->toJpeg(85);
            }

            // Store
            Storage::disk('public')->put($fullPath, $encodedImage);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Avatar upload failed: ' . $e->getMessage());
            // Fallback to simple upload on error
            return $this->simpleUpload($file, 'avatars');
        }
    }

    /**
     * Upload and optimize document image with high quality but smaller size
     * 
     * @param UploadedFile $file
     * @param string|null $path Custom path for the document
     * @return string|false
     */
    public function uploadDocument(UploadedFile $file, ?string $path = null)
    {
        $storagePath = $path ?? 'documents';
        // Documents: max 1600px (enough for most screens), quality 82 for good text readability
        return $this->uploadAndOptimize($file, $storagePath, 1600, 82, true);
    }

    /**
     * Upload carousel/banner image (optimized for large display)
     * 
     * @param UploadedFile $file
     * @param string|null $path Custom path
     * @return string|false
     */
    public function uploadCarousel(UploadedFile $file, ?string $path = null)
    {
        $storagePath = $path ?? 'carousel';
        // Carousel: max 1920px for full-width display, quality 78 for good balance
        return $this->uploadAndOptimize($file, $storagePath, 1920, 78, true);
    }

    /**
     * Delete image from storage
     * 
     * @param string $path
     * @return bool
     */
    public function deleteImage(string $path)
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Image deletion failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create thumbnail from existing image
     * Returns false if GD is not available (thumbnails require image processing)
     * 
     * @param string $path
     * @param int $width
     * @param int $height
     * @return string|false
     */
    public function createThumbnail(string $path, int $width = 200, int $height = 200)
    {
        // Thumbnails require GD for image processing
        if (!$this->isGdAvailable()) {
            \Log::info('Thumbnail creation skipped: GD not available');
            return false;
        }

        try {
            $fullPath = Storage::disk('public')->path($path);
            
            if (!file_exists($fullPath)) {
                return false;
            }

            $image = $this->manager->read($fullPath);
            
            // Create thumbnail (cover for crop)
            $image->cover($width, $height);
            
            // Generate thumbnail path with WebP if supported
            $pathInfo = pathinfo($path);
            $useWebP = $this->isWebPSupported();
            $extension = $useWebP ? 'webp' : 'jpg';
            $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['filename'] . '.' . $extension;
            
            // Save thumbnail with optimal compression
            if ($useWebP) {
                $encodedImage = $image->toWebp(75);
            } else {
                $encodedImage = $image->toJpeg(80);
            }
            Storage::disk('public')->put($thumbnailPath, $encodedImage);

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('Thumbnail creation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get estimated file size category
     * 
     * @param int $width
     * @param int $quality
     * @return string
     */
    public function getEstimatedSizeCategory(int $width, int $quality): string
    {
        // Rough estimation for WebP format
        if ($width <= 400 && $quality <= 80) {
            return '10-50 KB';
        } elseif ($width <= 800 && $quality <= 80) {
            return '50-150 KB';
        } elseif ($width <= 1200 && $quality <= 80) {
            return '100-300 KB';
        } elseif ($width <= 1600 && $quality <= 82) {
            return '150-400 KB';
        } else {
            return '200-600 KB';
        }
    }
}
