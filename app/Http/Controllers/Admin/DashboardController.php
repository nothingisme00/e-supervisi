<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supervisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache all user statistics in single query (performance optimization)
        $userStats = cache()->remember('stats.users_all', 300, function() {
            return User::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN role = 'guru' THEN 1 ELSE 0 END) as total_guru
            ")->first();
        });
        
        $totalUsers = $userStats->total;
        $totalGuru = $userStats->total_guru;
        
        // Cache all supervisi statistics in single query (performance optimization)
        $supervisiStats = cache()->remember('stats.supervisi_all', 300, function() {
            return Supervisi::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = '" . Supervisi::STATUS_SUBMITTED . "' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = '" . Supervisi::STATUS_UNDER_REVIEW . "' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = '" . Supervisi::STATUS_COMPLETED . "' THEN 1 ELSE 0 END) as completed
            ")->first();
        });
        
        $totalSupervisi = $supervisiStats->total;
        $supervisiPending = $supervisiStats->pending;
        $supervisiInProgress = $supervisiStats->in_progress;
        $supervisiReviewed = $supervisiStats->completed;
        
        // Untuk backward compatibility
        $supervisiSubmitted = $supervisiPending;
        $supervisiUnderReview = $supervisiInProgress;
        $supervisiCompleted = $supervisiReviewed;

        // Guru dengan statistik supervisi mereka - Optimized with caching
        $guruList = cache()->remember('admin.guru_list', 60, function() {
            return User::where('role', 'guru')
                ->select('id', 'name', 'nik', 'tingkat') // Only needed columns
                ->withCount([
                    'supervisi as total_supervisi',
                    'supervisi as supervisi_draft' => fn($q) => $q->where('status', Supervisi::STATUS_DRAFT),
                    'supervisi as supervisi_submitted' => fn($q) => $q->where('status', Supervisi::STATUS_SUBMITTED),
                    'supervisi as supervisi_under_review' => fn($q) => $q->where('status', Supervisi::STATUS_UNDER_REVIEW),
                    'supervisi as supervisi_completed' => fn($q) => $q->where('status', Supervisi::STATUS_COMPLETED)
                ])
                ->orderBy('name', 'asc')
                ->limit(10)
                ->get();
        });

        // Single query for all recent supervisi, then filter in PHP (avoid 2 separate queries)
        $allRecentSupervisi = cache()->remember('admin.recent_supervisi', 60, function() {
            return Supervisi::with(['user:id,name,nik', 'reviewer:id,name'])
                ->whereIn('status', [Supervisi::STATUS_SUBMITTED, Supervisi::STATUS_UNDER_REVIEW, Supervisi::STATUS_COMPLETED])
                ->orderBy('updated_at', 'desc')
                ->take(20)
                ->get();
        });

        // Filter from cached collection (no additional queries)
        $supervisiUnderReviewList = $allRecentSupervisi
            ->whereIn('status', [Supervisi::STATUS_SUBMITTED, Supervisi::STATUS_UNDER_REVIEW])
            ->take(10)->values();
        $supervisiCompletedList = $allRecentSupervisi
            ->where('status', Supervisi::STATUS_COMPLETED)
            ->take(10)->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGuru',
            'totalSupervisi',
            'supervisiSubmitted',
            'supervisiUnderReview',
            'supervisiCompleted',
            'supervisiPending',
            'supervisiInProgress',
            'supervisiReviewed',
            'guruList',
            'supervisiUnderReviewList',
            'supervisiCompletedList'
        ));
    }
}
