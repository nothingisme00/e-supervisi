<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = ModulKategori::active()->orderBy('nama')->get();

        $moduls = Modul::active()
            ->with('kategori')
            ->when($request->filled('kategori'), fn ($q) => $q->where('modul_kategori_id', $request->integer('kategori')))
            ->orderBy('judul')
            ->get();

        $progressByModul = ModulProgress::where('user_id', auth()->id())
            ->whereIn('modul_id', $moduls->pluck('id'))
            ->get()
            ->keyBy('modul_id');

        return view('guru.modul.index', compact('moduls', 'kategoris', 'progressByModul'));
    }

    public function show(Modul $modul)
    {
        abort_unless($modul->is_active, 404);

        $modul->load('videos');

        $progress = ModulProgress::firstOrCreate(
            ['user_id' => auth()->id(), 'modul_id' => $modul->id],
            ['halaman_terjauh' => 1]
        );
        $progress->update(['terakhir_dibuka_at' => now()]);

        $fileMissing = ! Storage::disk('local')->exists($modul->file_path);

        return view('guru.modul.baca', compact('modul', 'progress', 'fileMissing'));
    }

    public function file(Modul $modul)
    {
        abort_unless($modul->is_active, 404);

        $path = Storage::disk('local')->path($modul->file_path);

        abort_unless(file_exists($path), 404, 'File modul tidak ditemukan');

        return response()->file($path);
    }

    public function saveProgress(Request $request, Modul $modul)
    {
        abort_unless($modul->is_active, 404);

        $validated = $request->validate([
            'halaman' => 'required|integer|min:1|max:' . $modul->jumlah_halaman,
        ]);

        $progress = ModulProgress::firstOrCreate(
            ['user_id' => auth()->id(), 'modul_id' => $modul->id],
            ['halaman_terjauh' => 1]
        );

        if ($validated['halaman'] > $progress->halaman_terjauh) {
            $progress->halaman_terjauh = $validated['halaman'];
        }
        $progress->terakhir_dibuka_at = now();
        $progress->save();

        return response()->json(['success' => true, 'halaman_terjauh' => $progress->halaman_terjauh]);
    }
}
