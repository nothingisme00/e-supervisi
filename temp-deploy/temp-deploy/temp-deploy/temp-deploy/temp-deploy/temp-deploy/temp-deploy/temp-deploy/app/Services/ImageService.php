<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // Create ImageManager with GD driver
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Upload and optimize image
     * 
     * @param UploadedFile $file
     * @param string $path
     * @param int $maxWidth
     * @param int $quality
     * @return string|false
     */
    public function uploadAndOptimize(UploadedFile $file, string $path = 'images', int $maxWidth = 1200, int $quality = 85)
    {
        try {
            // Generate unique filename
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $fullPath = $path . '/' . $filename;

            // Load image
            $image = $this->manager->read($file);

            // Get original dimensions
            $originalWidth = $image->width();

            // Resize if image is larger than maxWidth
            if ($originalWidth > $maxWidth) {
                $image->scale(width: $maxWidth);
            }

            // Encode with quality compression
            $encodedImage = $image->toJpeg($quality);

            // Store optimized image
            Storage::disk('public')->put($fullPath, $encodedImage);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload and optimize avatar
     * 
     * @param UploadedFile $file
     * @return string|false
     */
    public function uploadAvatar(UploadedFile $file)
    {
        try {
            $filename = uniqid() . '_avatar_' . time() . '.jpg';
            $fullPath = 'avatars/' . $filename;

            // Create square avatar (400x400)
            $image = $this->manager->read($file);
            
            // Resize and crop to square
            $image->cover(400, 400);

            // Encode to JPEG
            $encodedImage = $image->toJpeg(85);

            // Store
            Storage::disk('public')->put($fullPath, $encodedImage);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Avatar upload failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload and optimize document image
     * 
     * @param UploadedFile $file
     * @return string|false
     */
    public function uploadDocument(UploadedFile $file)
    {
        // Documents might need higher quality
        return $this->uploadAndOptimize($file, 'documents', 1920, 90);
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
     * 
     * @param string $path
     * @param int $width
     * @param int $height
     * @return string|false
     */
    public function createThumbnail(string $path, int $width = 200, int $height = 200)
    {
        try {
            $fullPath = Storage::disk('public')->path($path);
            
            if (!file_exists($fullPath)) {
                return false;
            }

            $image = $this->manager->read($fullPath);
            
            // Create thumbnail (cover for crop)
            $image->cover($width, $height);
            
            // Generate thumbnail path
            $pathInfo = pathinfo($path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
            
            // Save thumbnail
            $encodedImage = $image->toJpeg(80);
            Storage::disk('public')->put($thumbnailPath, $encodedImage);

            return $thumbnailPath;
        } catch (\Exception $e) {
            \Log::error('Thumbnail creation failed: ' . $e->getMessage());
            return false;
        }
    }
}
