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
        
        // Professional categories for Kepala Sekolah
        $supervisiPending = Supervisi::where('status', 'submitted')->count(); // Perlu Review
        $supervisiInProgress = Supervisi::where('status', 'under_review')->count(); // Sedang Review
        $supervisiReviewed = Supervisi::where('status', 'completed')->count(); // Telah Review

        // For backward compatibility
        $supervisiSubmitted = $supervisiPending;
        $supervisiUnderReview = $supervisiInProgress;
        $supervisiCompleted = $supervisiReviewed;

        // Recent supervisi that need attention
        $recentSupervisi = Supervisi::with('user')
            ->whereIn('status', ['submitted', 'under_review'])
            ->latest()
            ->take(10)
            ->get();

        return view('kepala.dashboard', compact(
            'totalSupervisi',
            'supervisiPending',
            'supervisiInProgress',
            'supervisiReviewed',
            'supervisiSubmitted',
            'supervisiUnderReview',
            'supervisiCompleted',
            'recentSupervisi'
        ));
    }
}
