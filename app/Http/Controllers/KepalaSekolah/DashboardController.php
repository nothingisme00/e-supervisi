<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache all statistics in a single query (performance optimization)
        $stats = cache()->remember('kepala.stats.all', 300, function() {
            return Supervisi::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'under_review' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
            ")->first();
        });

        $totalSupervisi = $stats->total;
        $supervisiPending = $stats->pending;
        $supervisiInProgress = $stats->in_progress;
        $supervisiReviewed = $stats->completed;

        // For backward compatibility
        $supervisiSubmitted = $supervisiPending;
        $supervisiUnderReview = $supervisiInProgress;
        $supervisiCompleted = $supervisiReviewed;

        // Single query to get all recent supervisi, then filter in PHP (avoid 4 separate queries)
        $allRecentSupervisi = cache()->remember('kepala.recent_supervisi', 60, function() {
            return Supervisi::with('user:id,name,nik,tingkat')
                ->whereIn('status', ['submitted', 'under_review', 'completed'])
                ->latest()
                ->take(30) // Get enough for all 3 lists
                ->get();
        });

        // Filter from cached collection (no additional queries)
        $recentSupervisi = $allRecentSupervisi->whereIn('status', ['submitted', 'under_review'])->take(10)->values();
        $pendingList = $allRecentSupervisi->where('status', 'submitted')->take(5)->values();
        $inProgressList = $allRecentSupervisi->where('status', 'under_review')->take(5)->values();
        $completedList = $allRecentSupervisi->where('status', 'completed')->take(5)->values();

        return view('kepala.dashboard', compact(
            'totalSupervisi',
            'supervisiPending',
            'supervisiInProgress',
            'supervisiReviewed',
            'supervisiSubmitted',
            'supervisiUnderReview',
            'supervisiCompleted',
            'recentSupervisi',
            'pendingList',
            'inProgressList',
            'completedList'
        ));
    }
}
