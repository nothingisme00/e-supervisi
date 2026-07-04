<?php

namespace Database\Seeders;

use App\Models\CarouselSlide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CarouselSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Catatan:
     * - Gambar sample (jika ada) harus disimpan di: public/images/carousel/samples/
     * - Akan di-copy otomatis ke: storage/app/public/carousel/
     * - Jika tidak ada gambar sample, slide tetap dibuat (tanpa gambar)
     */
    public function run(): void
    {
        // Path ke gambar sample di public/images (opsional)
        $sampleImages = [
            'images/carousel/samples/education-1.jpg',
            'images/carousel/samples/education-2.jpg',
            'images/carousel/samples/education-3.jpg',
        ];

        $slides = [
            [
                'title' => 'Meningkatkan Kualitas',
                'subtitle' => 'Pendidikan Digital',
                'description' => 'Platform terintegrasi untuk pemantauan, evaluasi, dan pengembangan profesional guru yang lebih efektif.',
                'image_path' => null,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Monitoring Supervisi',
                'subtitle' => 'Real-time',
                'description' => 'Pantau perkembangan supervisi guru secara langsung dengan dashboard yang informatif dan mudah digunakan.',
                'image_path' => null,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Kolaborasi',
                'subtitle' => 'Guru & Kepala Sekolah',
                'description' => 'Tingkatkan komunikasi dan feedback antara guru dan kepala sekolah untuk pembelajaran yang lebih baik.',
                'image_path' => null,
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $index => $slide) {
            // Jika ada gambar sample, copy ke storage
            if (isset($sampleImages[$index])) {
                $sourcePath = public_path($sampleImages[$index]);
                
                if (File::exists($sourcePath)) {
                    $extension = File::extension($sourcePath);
                    $filename = 'sample_' . ($index + 1) . '_' . time() . '.' . $extension;
                    $destinationPath = 'carousel/' . $filename;
                    
                    // Copy ke storage/app/public/carousel/
                    try {
                        Storage::disk('public')->put(
                            $destinationPath,
                            File::get($sourcePath)
                        );
                        
                        $slide['image_path'] = $destinationPath;
                        $this->command->info("✓ Copied {$sampleImages[$index]} → storage/{$destinationPath}");
                    } catch (\Exception $e) {
                        $this->command->warn("⚠ Failed to copy {$sampleImages[$index]}: " . $e->getMessage());
                    }
                } else {
                    $this->command->warn("⚠ Sample image not found: {$sampleImages[$index]}");
                }
            }
            
            CarouselSlide::create($slide);
            $this->command->info("✓ Created slide: {$slide['title']}");
        }
        
        $this->command->info("\n✓ Carousel slides seeded successfully!");
        $this->command->warn("\nCatatan:");
        $this->command->line("  - Jika tidak ada gambar, slide tetap dibuat (upload manual via admin)");
        $this->command->line("  - Simpan gambar sample di: public/images/carousel/samples/");
        $this->command->line("  - Nama file: education-1.jpg, education-2.jpg, education-3.jpg");
    }
}
