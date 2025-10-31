<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSupervisi = Supervisi::count();
        $supervisiSubmitted = Supervisi::where('status', 'submitted')->count();
        $supervisiReviewed = Supervisi::where('status', 'reviewed')->count();
        $supervisiCompleted = Supervisi::where('status', 'completed')->count();

        $recentSupervisi = Supervisi::with('user')
            ->whereIn('status', ['submitted', 'reviewed'])
            ->latest()
            ->take(10)
            ->get();

        return view('kepala.dashboard', compact(
            'totalSupervisi',
            'supervisiSubmitted',
            'supervisiReviewed',
            'supervisiCompleted',
            'recentSupervisi'
        ));
    }
}
