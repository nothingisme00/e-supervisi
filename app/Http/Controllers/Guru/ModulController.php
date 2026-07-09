<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\ModulKategori;
use App\Models\ModulProgress;
use Illuminate\Http\Request;

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
}
