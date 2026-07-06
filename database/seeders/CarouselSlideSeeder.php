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
     * - Idempoten: seed ulang memperbarui slide berdasarkan `order`, tidak menduplikasi
     */
    public function run(): void
    {
        // Nama file sesuai aset nyata di public/images/carousel/samples/
        $sampleImages = [
            'images/carousel/samples/brooke-cagle-g1Kr4Ozfoac-unsplash.jpg',
            'images/carousel/samples/edwin-andrade-4V1dC_eoCwg-unsplash.jpg',
            'images/carousel/samples/kenny-eliason-1-aA2Fadydc-unsplash.jpg',
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
            // Jika ada gambar sample, copy ke storage (nama deterministik agar idempoten)
            if (isset($sampleImages[$index])) {
                $sourcePath = public_path($sampleImages[$index]);

                if (File::exists($sourcePath)) {
                    $extension = File::extension($sourcePath);
                    $destinationPath = 'carousel/sample_' . ($index + 1) . '.' . $extension;

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

            CarouselSlide::updateOrCreate(['order' => $slide['order']], $slide);
            $this->command->info("✓ Created slide: {$slide['title']}");
        }

        $this->command->info("\n✓ Carousel slides seeded successfully!");
    }
}
