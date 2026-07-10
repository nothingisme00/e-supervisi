<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\ModulProgress;
use App\Models\User;
use Illuminate\Http\Request;

class ModulProgressController extends Controller
{
    public function index(Request $request)
    {
        $moduls = Modul::active()->with('kategori')->orderBy('judul')->get();
        $gurus = User::where('role', 'guru')->where('is_active', true)->orderBy('name')->get();

        $mode = $request->input('mode') === 'guru' ? 'guru' : 'modul';
        $selectedModul = null;
        $selectedGuru = null;
        $rows = collect();

        if ($mode === 'modul' && $moduls->isNotEmpty()) {
            $selectedModul = $moduls->firstWhere('id', $request->integer('modul_id')) ?? $moduls->first();
            $progressByUser = ModulProgress::with('modul')
                ->where('modul_id', $selectedModul->id)
                ->get()
                ->keyBy('user_id');
            // Guru tanpa progres tetap tampil 0% — ketiadaan data adalah informasi.
            $rows = $gurus->map(fn (User $guru) => [
                'label' => $guru->name,
                'persen' => $progressByUser->get($guru->id)?->persen() ?? 0,
                'terakhir' => $progressByUser->get($guru->id)?->terakhir_dibuka_at,
            ]);
        }

        if ($mode === 'guru' && $gurus->isNotEmpty()) {
            $selectedGuru = $gurus->firstWhere('id', $request->integer('guru_id')) ?? $gurus->first();
            $progressByModul = ModulProgress::with('modul')
                ->where('user_id', $selectedGuru->id)
                ->get()
                ->keyBy('modul_id');
            $rows = $moduls->map(fn (Modul $modul) => [
                'label' => $modul->judul,
                'persen' => $progressByModul->get($modul->id)?->persen() ?? 0,
                'terakhir' => $progressByModul->get($modul->id)?->terakhir_dibuka_at,
            ]);
        }

        return view('kepala.modul-progress.index', compact('moduls', 'gurus', 'mode', 'selectedModul', 'selectedGuru', 'rows'));
    }
}
