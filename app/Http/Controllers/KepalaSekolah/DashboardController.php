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
        // Cache statistics for 5 minutes
        $totalSupervisi = cache()->remember('kepala.stats.total_supervisi', 300, function() {
            return Supervisi::count();
        });
        
        $supervisiPending = cache()->remember('kepala.stats.supervisi_pending', 300, function() {
            return Supervisi::where('status', 'submitted')->count();
        });
        
        $supervisiInProgress = cache()->remember('kepala.stats.supervisi_in_progress', 300, function() {
            return Supervisi::where('status', 'under_review')->count();
        });
        
        $supervisiReviewed = cache()->remember('kepala.stats.supervisi_reviewed', 300, function() {
            return Supervisi::where('status', 'completed')->count();
        });

        // For backward compatibility
        $supervisiSubmitted = $supervisiPending;
        $supervisiUnderReview = $supervisiInProgress;
        $supervisiCompleted = $supervisiReviewed;

        // Recent supervisi with eager loading - only load needed columns
        $recentSupervisi = Supervisi::with('user:id,name,nik,tingkat')
            ->whereIn('status', ['submitted', 'under_review'])
            ->latest()
            ->take(10)
            ->get();

        // Separate lists for each status
        $pendingList = Supervisi::with('user:id,name,nik,tingkat')
            ->where('status', 'submitted')
            ->latest()
            ->take(5)
            ->get();
            
        $inProgressList = Supervisi::with('user:id,name,nik,tingkat')
            ->where('status', 'under_review')
            ->latest()
            ->take(5)
            ->get();
            
        $completedList = Supervisi::with('user:id,name,nik,tingkat')
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

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
