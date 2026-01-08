# Instruksi Coding AI E-Supervisi

## Gambaran Umum Proyek

E-Supervisi adalah aplikasi web Laravel 12 untuk mengelola supervisi dan evaluasi pembelajaran guru di sekolah. Sistem ini memungkinkan guru mengajukan permintaan supervisi dengan dokumen pembelajaran, dan kepala sekolah untuk meninjau, memberikan umpan balik, dan menyetujui pengajuan melalui alur kerja terstruktur.

## Arsitektur & Konsep Inti

### Sistem Multi-Role

Tiga peran berbeda dengan route group dan controller terpisah:

-   **Admin** (`role === 'admin'`): Manajemen pengguna, konfigurasi sistem
-   **Kepala Sekolah** (`role === 'kepala_sekolah'`): Meninjau pengajuan, memberikan umpan balik
-   **Guru** (`role === 'guru'`): Membuat dan mengelola permintaan supervisi

**Penting**: Pengecekan role menggunakan Laravel Gates yang didefinisikan di [app/Providers/AuthServiceProvider.php](app/Providers/AuthServiceProvider.php). Selalu gunakan `can:isAdmin`, `can:isGuru`, atau `can:isKepalaSekolah` middleware di routes, jangan pernah gunakan perbandingan role langsung di middleware.

### Status Alur Kerja Supervisi

Progres status: `draft` → `submitted` → `in_progress` → `reviewed` → `completed`

-   Draft dapat direvisi berkali-kali sebelum pengajuan
-   Status `revision_requested` memungkinkan guru untuk memperbarui dan mengajukan ulang
-   Lihat [app/Models/Supervisi.php](app/Models/Supervisi.php) untuk relasi

### Relasi Model Data

Entitas inti: `Supervisi` (satu per permintaan supervisi)

-   `hasMany`: `DokumenEvaluasi` (RPP, materi, evaluasi - beberapa file upload)
-   `hasOne`: `ProsesPembelajaran` (form tunggal dengan detail proses pembelajaran)
-   `hasMany`: `Feedback` (komunikasi antara guru dan kepala sekolah)
-   `belongsTo`: `User` (pembuat), `reviewer` (dievaluasi oleh kepala sekolah)

**Penting**: Model Supervisi mengimplementasikan cascade delete di method `boot()` - semua record terkait otomatis terhapus.

## Alur Kerja Pengembangan

### Menjalankan Aplikasi

```bash
# Development (menjalankan server, queue, logs, vite bersamaan)
composer dev

# Layanan individual
php artisan serve              # Web server di localhost:8000
npm run dev                    # Vite dev server untuk assets
php artisan queue:listen       # Pemrosesan background job
php artisan pail               # Real-time log viewer
```

### Operasi Database

```bash
php artisan migrate            # Jalankan migrations
php artisan db:seed            # Seed dengan admin default (NIK: 1234567890123456, password: admin123)
php artisan migrate:fresh --seed  # Reset dan seed ulang database
```

**Kredensial Default**: Admin NIK `1234567890123456` / password `admin123` (wajib ganti password saat login pertama via flag `must_change_password`).

### Build Assets

```bash
npm run dev    # Development dengan HMR
npm run build  # Optimasi production (output ke public/build/)
```

## Pola & Konvensi Penting

### Manajemen Cache

Proyek menggunakan pembersihan cache terpusat via [app/Helpers/CacheHelper.php](app/Helpers/CacheHelper.php):

-   `CacheHelper::clearSupervisiCache()` - Hapus statistik supervisi
-   `CacheHelper::clearUserCache()` - Hapus jumlah pengguna
-   `CacheHelper::clearDashboardCache()` - Hapus semua cache dashboard

**Penting**: Method `boot()` model otomatis membersihkan cache terkait saat create/update/delete. Saat menambahkan statistik dashboard, update cache keys di CacheHelper.

### Penanganan Gambar

Gunakan [app/Services/ImageService.php](app/Services/ImageService.php) untuk semua upload gambar:

```php
$imageService = app(ImageService::class);
$path = $imageService->uploadAndOptimize($file, 'path', $maxWidth, $quality);
$avatarPath = $imageService->uploadAvatar($file);  // Auto-crop ke 400x400
$docPath = $imageService->uploadDocument($file);   // Kualitas lebih tinggi untuk dokumen
```

**Alasan**: Optimasi otomatis dengan Intervention/Image (GD driver), mencegah upload file besar menghabiskan ruang disk.

### Custom Middleware

-   **MustChangePassword**: Memaksa perubahan password untuk pengguna baru (flag `must_change_password`)
-   **PreventBackHistory**: Menambahkan header cache-control untuk mencegah masalah tombol back browser setelah logout
-   **SessionTimeout**: Tidak ditampilkan tapi kemungkinan ada berdasarkan pola autentikasi

**Penting**: Route download file mengecualikan middleware `prevent.back` untuk menghindari konflik header - lihat [routes/web.php](routes/web.php#L48-53).

### Organisasi Route

Routes diorganisir dengan prefix groups dan penamaan konsisten:

-   Admin routes: `admin.{resource}.{action}` (contoh: `admin.users.reset-password`)
-   Guru routes: `guru.supervisi.{action}` (contoh: `guru.supervisi.proses.save`)
-   Kepala routes: `kepala.evaluasi.{action}`

**Penting**: Route login menggunakan `throttle:5,1` (5 percobaan per menit), ganti password menggunakan `throttle:10,1`.

### Komponen Livewire

Penggunaan Livewire terbatas - ditemukan di [app/Livewire/Admin/UserManagement.php](app/Livewire/Admin/UserManagement.php). Saat menambahkan komponen Livewire:

-   Gunakan untuk elemen UI reaktif (tabel data, filter real-time)
-   Simpan manajemen state di properties komponen
-   Emit events untuk komunikasi antar komponen

## Penyimpanan File & Upload

Storage menggunakan public disk dengan symlink (`php artisan storage:link`):

-   Dokumen: `storage/app/public/documents/`
-   Avatar: `storage/app/public/avatars/`
-   Gambar: `storage/app/public/images/`

**Pola akses**: File disimpan via `Storage::disk('public')->put()`, diakses via route `storage/{path}` atau `asset('storage/{path})`.

## Catatan Deployment

### Shared Hosting Tanpa SSH

Proyek menyertakan script helper di [deployment-helpers/](deployment-helpers/):

-   `generate-key.php` - Generate APP_KEY via browser
-   `migrate-database.php` - Jalankan migrations via HTTP
-   `optimize.php` - Cache config/routes/views via HTTP

**Keamanan**: HAPUS file-file ini segera setelah digunakan di production!

### Optimasi Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

Lihat [HOSTING-REQUIREMENTS.md](HOSTING-REQUIREMENTS.md) untuk spesifikasi server (minimum 2GB RAM, PHP 8.2+, MySQL 8.0).

## Testing & Quality

### Testing (PHPUnit terkonfigurasi)

```bash
php artisan test              # Jalankan test suite
php artisan test --filter=UserTest  # Test spesifik
```

### Code Style (Laravel Pint)

```bash
./vendor/bin/pint            # Auto-fix code style
./vendor/bin/pint --test     # Check tanpa fixing
```

## Tugas Umum

### Menambahkan Role Pengguna Baru

1. Tambahkan konstanta role ke model User
2. Tambahkan definisi Gate di AuthServiceProvider
3. Buat route group khusus role di web.php
4. Tambahkan middleware ke routes: `->middleware('can:isNewRole')`
5. Update CacheHelper jika role memiliki statistik dashboard

### Menambahkan Status Baru ke Workflow

1. Update pengecekan status di logic model Supervisi atau controller
2. Tambahkan styling badge di Blade views (cek [resources/views/guru/home.blade.php](resources/views/guru/home.blade.php) untuk pola)
3. Update statistik dashboard jika status mempengaruhi hitungan
4. Hapus cache terkait via CacheHelper

### Menambahkan File Upload Baru

1. Gunakan ImageService untuk gambar, penanganan file Laravel standar untuk dokumen
2. Tambahkan aturan validasi di controller (ukuran max, tipe MIME)
3. Simpan ke public disk dengan path deskriptif
4. Tambahkan route download/preview jika diperlukan (kecualikan middleware prevent.back)
5. Tangani penghapusan di cascade model atau controller

## Panduan Livewire

### Kapan Menggunakan Livewire

Gunakan Livewire untuk fitur-fitur yang memerlukan interaktivitas real-time tanpa reload halaman:

-   **Tabel Data dengan Filter**: Tabel yang bisa difilter, sort, dan search secara real-time
-   **Form dengan Validasi Real-time**: Form yang menampilkan error saat user mengetik
-   **Modal/Dialog Interaktif**: Modal yang bisa dibuka/tutup tanpa reload
-   **Komponen yang Sering Update**: Counter, notifikasi, status yang berubah otomatis

### Kapan TIDAK Menggunakan Livewire

Gunakan Blade biasa untuk:

-   Form CRUD standar yang tidak perlu validasi real-time
-   Halaman static (dashboard view, detail view)
-   Report/Export yang tidak interaktif
-   Halaman yang jarang di-update user

### Contoh Implementasi

Lihat [app/Livewire/Admin/UserManagement.php](app/Livewire/Admin/UserManagement.php) untuk pattern yang benar.

## Panduan API Routes

### Struktur API

API menggunakan `routes/api.php` dengan prefix `/api`:

```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('supervisi')->group(function () {
        Route::get('/', [SupervisiApiController::class, 'index']);
        Route::get('/{id}', [SupervisiApiController::class, 'show']);
        Route::post('/', [SupervisiApiController::class, 'store']);
        Route::put('/{id}', [SupervisiApiController::class, 'update']);
        Route::delete('/{id}', [SupervisiApiController::class, 'destroy']);
    });
});
```

### Response Format

Semua API harus return JSON dengan format konsisten:

```php
// Success
return response()->json([
    'success' => true,
    'message' => 'Data berhasil diambil',
    'data' => $data
], 200);

// Error
return response()->json([
    'success' => false,
    'message' => 'Supervisi tidak ditemukan',
    'errors' => []
], 404);
```

### Autentikasi API

Gunakan Laravel Sanctum untuk autentikasi API:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## Panduan Queue Jobs

### Kapan Menggunakan Queue

Gunakan queue untuk operasi yang memakan waktu lama (>3 detik):

-   Mengirim email massal (notifikasi ke banyak user)
-   Generate laporan PDF besar
-   Optimasi/resize gambar batch
-   Export data ke Excel/CSV
-   Sinkronisasi dengan sistem eksternal

### Membuat Queue Job

```bash
php artisan make:job SendSupervisiNotification
```

### Struktur Job

```php
// app/Jobs/SendSupervisiNotification.php
<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSupervisiNotification implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $supervisi;

    public function __construct($supervisi)
    {
        $this->supervisi = $supervisi;
    }

    public function handle()
    {
        // Kirim email atau notifikasi
        Mail::to($this->supervisi->user->email)
            ->send(new SupervisiSubmitted($this->supervisi));
    }
}
```

### Dispatch Job

```php
// Di Controller
use App\Jobs\SendSupervisiNotification;

SendSupervisiNotification::dispatch($supervisi);
```

### Menjalankan Queue Worker

```bash
php artisan queue:work
# Atau gunakan 'composer dev' yang sudah include queue:listen
```

## Panduan Testing

### Struktur Test

```bash
php artisan make:test SupervisiTest        # Feature test
php artisan make:test SupervisiTest --unit  # Unit test
```

### Test yang Wajib Dibuat

1. **Model Test**: Test relationships, scopes, mutators
2. **Controller Test**: Test semua endpoint (CRUD)
3. **Middleware Test**: Test authorization dan authentication
4. **Validation Test**: Test form validation rules

### Contoh Feature Test

```php
// tests/Feature/SupervisiTest.php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Supervisi;

class SupervisiTest extends TestCase
{
    public function test_guru_can_create_supervisi()
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)
            ->post('/guru/supervisi/store', [
                'tanggal_supervisi' => now()->addDays(7),
                'catatan' => 'Test supervisi'
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('supervisi', [
            'user_id' => $guru->id,
            'status' => 'draft'
        ]);
    }

    public function test_admin_cannot_create_supervisi()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get('/guru/supervisi/create');

        $response->assertForbidden();
    }
}
```

### Menjalankan Test

```bash
php artisan test                    # Semua test
php artisan test --filter=Supervisi # Test spesifik
php artisan test --coverage         # Dengan coverage report
```

## Konstanta Status Workflow

### Implementasi Konstanta

Tambahkan konstanta di Model Supervisi untuk menghindari typo:

```php
// app/Models/Supervisi.php
class Supervisi extends Model
{
    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REVISION_REQUESTED = 'revision_requested';

    // Helper method untuk list semua status
    public static function getStatuses()
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_REVIEWED,
            self::STATUS_COMPLETED,
            self::STATUS_REVISION_REQUESTED,
        ];
    }
}
```

### Penggunaan di Controller

```php
// SALAH - Rawan typo
$supervisi->status = 'darft'; // Typo tidak ketahuan

// BENAR - Aman dan autocomplete
$supervisi->status = Supervisi::STATUS_DRAFT;

// Checking status
if ($supervisi->status === Supervisi::STATUS_DRAFT) {
    // Lakukan sesuatu
}
```

### Validation Rule

```php
// Validasi dengan konstanta
'status' => ['required', Rule::in(Supervisi::getStatuses())]
```

## Referensi File Kunci

-   [routes/web.php](routes/web.php) - Semua definisi route dengan middleware
-   [routes/api.php](routes/api.php) - API routes untuk integrasi eksternal
-   [app/Models/Supervisi.php](app/Models/Supervisi.php) - Model inti dengan relasi dan konstanta
-   [app/Helpers/CacheHelper.php](app/Helpers/CacheHelper.php) - Manajemen cache
-   [app/Services/ImageService.php](app/Services/ImageService.php) - Optimasi gambar
-   [app/Jobs/](app/Jobs/) - Background jobs untuk operasi berat
-   [tests/Feature/](tests/Feature/) - Feature tests untuk endpoint
-   [composer.json](composer.json) - Dependencies dan script `dev` kustom
-   [README.md](README.md) - Dokumentasi instalasi dan penggunaan
