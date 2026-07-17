<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\ModulKategori;
use App\Services\ImageService;
use App\Services\PdfPageCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    private const PESAN_PDF_RUSAK = 'File PDF tidak dapat dibaca. Pastikan PDF valid dan tidak terkunci kata sandi.';

    public function __construct(private PdfPageCounter $pageCounter, private ImageService $imageService)
    {
    }

    public function index()
    {
        $moduls = Modul::with(['kategori', 'videos'])->latest()->get();
        $kategoris = ModulKategori::orderBy('nama')->get();

        return view('admin.modul.index', compact('moduls', 'kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateModul($request, fileRequired: true);

        $path = $request->file('file')->store('modul', 'local');

        try {
            $jumlahHalaman = $this->pageCounter->count(Storage::disk('local')->path($path));
        } catch (\InvalidArgumentException) {
            Storage::disk('local')->delete($path);

            return back()->withInput()->withErrors(['file' => self::PESAN_PDF_RUSAK]);
        }

        $modul = Modul::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'modul_kategori_id' => $validated['modul_kategori_id'],
            'file_path' => $path,
            'thumbnail_path' => $this->storeThumbnail($request),
            'jumlah_halaman' => $jumlahHalaman,
            'is_active' => true,
        ]);

        $this->syncVideos($modul, $validated['videos'] ?? []);

        try {
            $guru = \App\Models\User::where('role', 'guru')->where('is_active', true)->get();
            \Illuminate\Support\Facades\Notification::send($guru, new \App\Notifications\ModulBaruDiunggah($modul));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('admin.modul.index')->with('success', 'Modul berhasil ditambahkan.');
    }

    public function update(Request $request, Modul $modul)
    {
        $validated = $this->validateModul($request, fileRequired: false);

        $data = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'modul_kategori_id' => $validated['modul_kategori_id'],
        ];

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('modul', 'local');

            try {
                $data['jumlah_halaman'] = $this->pageCounter->count(Storage::disk('local')->path($path));
            } catch (\InvalidArgumentException) {
                Storage::disk('local')->delete($path);

                return back()->withInput()->withErrors(['file' => self::PESAN_PDF_RUSAK]);
            }

            Storage::disk('local')->delete($modul->file_path);
            $data['file_path'] = $path;

            // Sampul menggambarkan halaman 1 PDF lama, jadi selalu diganti saat PDF diganti.
            if ($modul->thumbnail_path) {
                Storage::disk('public')->delete($modul->thumbnail_path);
            }
            $data['thumbnail_path'] = $this->storeThumbnail($request);
        }

        $modul->update($data);
        $this->syncVideos($modul, $validated['videos'] ?? []);

        return redirect()->route('admin.modul.index')->with('success', 'Modul berhasil diperbarui.');
    }

    public function toggle(Modul $modul)
    {
        $modul->update(['is_active' => ! $modul->is_active]);

        return redirect()->route('admin.modul.index')->with('success', 'Status modul berhasil diubah.');
    }

    public function storeKategori(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:modul_kategoris,nama',
        ]);

        ModulKategori::create($validated + ['is_active' => true]);

        return redirect()->route('admin.modul.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function toggleKategori(ModulKategori $modulKategori)
    {
        $modulKategori->update(['is_active' => ! $modulKategori->is_active]);

        return redirect()->route('admin.modul.index')->with('success', 'Status kategori berhasil diubah.');
    }

    private function validateModul(Request $request, bool $fileRequired): array
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'modul_kategori_id' => 'required|exists:modul_kategoris,id',
            'file' => ($fileRequired ? 'required' : 'nullable') . '|file|mimes:pdf|max:20480',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'videos' => 'nullable|array|max:5',
            'videos.*.judul' => 'nullable|required_with:videos.*.youtube_url|string|max:255',
            'videos.*.youtube_url' => [
                'nullable',
                'required_with:videos.*.judul',
                'url',
                'regex:#^https?://(?:(?:www\.|m\.)?youtube\.com/(?:watch\?(?:[^\s]*&)?v=|shorts/)|youtu\.be/)[A-Za-z0-9_-]{11}#',
            ],
        ]);
    }

    /** Simpan sampul PDF hasil render browser. Kegagalan tidak boleh memblokir modul — kembalikan null. */
    private function storeThumbnail(Request $request): ?string
    {
        if (! $request->hasFile('thumbnail')) {
            return null;
        }

        return $this->imageService->uploadAndOptimize($request->file('thumbnail'), 'modul-thumbnails', 640, 75) ?: null;
    }

    /** Ganti seluruh daftar video modul dengan input baru (abaikan baris kosong). */
    private function syncVideos(Modul $modul, array $videos): void
    {
        $modul->videos()->delete();

        foreach ($videos as $video) {
            if (! empty($video['judul']) && ! empty($video['youtube_url'])) {
                $modul->videos()->create([
                    'judul' => $video['judul'],
                    'youtube_url' => $video['youtube_url'],
                ]);
            }
        }
    }
}
