<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Laporan;
use App\Models\Evaluasi;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total guru
        $totalGuru = Cache::remember('total_guru', 60, fn() => Guru::count());

        // Menghitung jumlah laporan supervisi (jika ada tabel laporan)
        $laporanMasuk = Cache::remember('laporan_masuk', 60, fn() => Laporan::count());

        // Menghitung jumlah evaluasi yang sudah memiliki nilai
        $evaluasiDisetujui = Cache::remember('evaluasi_disetujui', 60, fn() =>
            Evaluasi::whereNotNull('nilai')->count()
        );

        // Bisa juga ditambahkan rata-rata nilai evaluasi
        $rataNilai = Evaluasi::avg('nilai');

        // Ambil data user yang login
        $user = Auth::user();

        // Kirim ke view
        return view('admin.dashboard', compact(
            'totalGuru',
            'laporanMasuk',
            'evaluasiDisetujui',
            'rataNilai',
            'user'
        ));
    }
}
