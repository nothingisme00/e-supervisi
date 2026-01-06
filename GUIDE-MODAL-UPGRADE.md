# Panduan Upgrade Guide Modal

## Masalah yang Diperbaiki

1. ✅ Modal tidak muncul di mobile view
2. ✅ Menambahkan navigasi step (Previous/Next) untuk desktop
3. ✅ Step-by-step content yang lebih lengkap

## Perubahan yang Sudah Diterapkan

### 1. JavaScript Functions

-   `openGuideModal()` - Sudah diperbaiki untuk mobile bottom sheet
-   `closeGuideModal()` - Animasi slide down untuk mobile
-   `nextStep()`, `prevStep()`, `updateStepDisplay()` - Navigasi step untuk desktop

### 2. Data Attribute

-   `<body data-role="{{ auth()->user()->role }}">` - Mendeteksi role untuk jumlah steps

## Yang Perlu Ditambahkan di HTML Modal

Untuk mendukung fitur navigasi step, Anda perlu menambahkan konten step-by-step yang lengkap untuk desktop. Berikut struktur yang diperlukan:

### Untuk Role GURU (5 Steps):

```html
<!-- Desktop: Step Navigation -->
<div class="hidden md:block">
    <!-- Progress Bar dengan Dots -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-indigo-600 text-xl"
                >route</span
            >
            <h4>
                Langkah <span id="currentStepNum">1</span> dari
                <span id="totalSteps">5</span>
            </h4>
        </div>
        <div class="flex gap-1">
            <div
                class="step-dot w-2.5 h-2.5 rounded-full bg-indigo-600"
                data-step="1"
            ></div>
            <div
                class="step-dot w-2.5 h-2.5 rounded-full bg-gray-300"
                data-step="2"
            ></div>
            <div
                class="step-dot w-2.5 h-2.5 rounded-full bg-gray-300"
                data-step="3"
            ></div>
            <div
                class="step-dot w-2.5 h-2.5 rounded-full bg-gray-300"
                data-step="4"
            ></div>
            <div
                class="step-dot w-2.5 h-2.5 rounded-full bg-gray-300"
                data-step="5"
            ></div>
        </div>
    </div>

    <!-- Step Content Container -->
    <div id="stepContentContainer">
        <!-- Step 1: Mulai Pengajuan -->
        <div class="step-content active" data-step="1">
            <div
                class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 rounded-2xl p-6 border border-blue-100"
            >
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl flex items-center justify-center font-bold text-xl"
                    >
                        1
                    </div>
                    <div>
                        <h4 class="text-lg font-bold">Mulai Pengajuan</h4>
                        <p class="text-sm text-blue-600">Buat supervisi baru</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">
                    Klik tombol
                    <strong class="text-blue-600">"Mulai Supervisi"</strong> di
                    beranda:
                </p>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500"
                            >check_circle</span
                        >
                        Pilih tanggal rencana supervisi
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500"
                            >check_circle</span
                        >
                        Sistem akan membuat draft otomatis
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500"
                            >check_circle</span
                        >
                        Anda bisa melanjutkan kapan saja
                    </li>
                </ul>
            </div>
        </div>

        <!-- Step 2, 3, 4, 5 dengan struktur sama -->
        <!-- ... tambahkan sesuai kebutuhan ... -->
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-between mt-6 pt-4 border-t">
        <button id="prevStepBtn" onclick="prevStep()" disabled>
            <span class="material-symbols-outlined">arrow_back</span>
            Sebelumnya
        </button>
        <button id="nextStepBtn" onclick="nextStep()">
            Selanjutnya
            <span class="material-symbols-outlined">arrow_forward</span>
        </button>
    </div>
</div>

<!-- Mobile: Simple List (Sudah Ada) -->
<div class="md:hidden space-y-4">
    <!-- Journey list sederhana untuk mobile -->
</div>
```

## Testing

1. **Mobile**: Buka di perangkat mobile atau DevTools (F12) → Toggle device toolbar

    - Modal harus slide up dari bawah
    - Menampilkan list sederhana tanpa navigasi

2. **Desktop**: Buka di browser normal
    - Modal muncul di tengah dengan scale animation
    - Progress dots terlihat di atas
    - Tombol Previous/Next berfungsi
    - Step terakhir menampilkan "Selesai"

## Status Implementasi

-   ✅ JavaScript logic untuk navigasi step
-   ✅ Mobile bottom sheet animation
-   ✅ Desktop scale & fade animation
-   ⚠️ HTML content untuk 5 steps guru (hanya 3 step yang ada, perlu ditambah 2 lagi)
-   ⚠️ Progress dots untuk semua role

## Next Steps

Jika Anda ingin menambahkan 2 step lagi untuk Guru:

-   Step 4: Upload Video Pembelajaran
-   Step 5: Pantau Feedback & Revisi

Gunakan template HTML di atas dan sesuaikan konten sesuai kebutuhan.
