<?php

namespace Database\Seeders;

use App\Models\CarouselSlide;
use Illuminate\Database\Seeder;

class CarouselSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title' => 'Meningkatkan Kualitas',
                'subtitle' => 'Pendidikan Digital',
                'description' => 'Platform terintegrasi untuk pemantauan, evaluasi, dan pengembangan profesional guru yang lebih efektif.',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Monitoring Supervisi',
                'subtitle' => 'Real-time',
                'description' => 'Pantau perkembangan supervisi guru secara langsung dengan dashboard yang informatif dan mudah digunakan.',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Kolaborasi',
                'subtitle' => 'Guru & Kepala Sekolah',
                'description' => 'Tingkatkan komunikasi dan feedback antara guru dan kepala sekolah untuk pembelajaran yang lebih baik.',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            CarouselSlide::create($slide);
        }
    }
}
