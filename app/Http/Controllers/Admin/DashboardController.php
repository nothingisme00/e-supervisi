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
        // Cache statistics for 5 minutes (300 seconds)
        $totalUsers = cache()->remember('stats.total_users', 300, function() {
            return User::count();
        });
        
        $totalGuru = cache()->remember('stats.total_guru', 300, function() {
            return User::where('role', 'guru')->count();
        });
        
        $totalSupervisi = cache()->remember('stats.total_supervisi', 300, function() {
            return Supervisi::count();
        });
        
        // Kategori Review - Cached
        $supervisiPending = cache()->remember('stats.supervisi_pending', 300, function() {
            return Supervisi::where('status', 'submitted')->count();
        });
        
        $supervisiInProgress = cache()->remember('stats.supervisi_in_progress', 300, function() {
            return Supervisi::where('status', 'under_review')->count();
        });
        
        $supervisiReviewed = cache()->remember('stats.supervisi_reviewed', 300, function() {
            return Supervisi::where('status', 'completed')->count();
        });
        
        // Untuk backward compatibility
        $supervisiSubmitted = $supervisiPending;
        $supervisiUnderReview = $supervisiInProgress;
        $supervisiCompleted = $supervisiReviewed;

        // Guru dengan statistik supervisi mereka - Eager Loading
        $guruList = User::where('role', 'guru')
            ->withCount([
                'supervisi as total_supervisi',
                'supervisi as supervisi_draft' => function($query) {
                    $query->where('status', 'draft');
                },
                'supervisi as supervisi_submitted' => function($query) {
                    $query->where('status', 'submitted');
                },
                'supervisi as supervisi_under_review' => function($query) {
                    $query->where('status', 'under_review');
                },
                'supervisi as supervisi_completed' => function($query) {
                    $query->where('status', 'completed');
                }
            ])
            ->with(['supervisi' => function($query) {
                $query->latest()->take(1);
            }])
            ->orderBy('name', 'asc')
            ->limit(10) // Limit untuk performa
            ->get();

        // Supervisi yang sedang direview - Eager Loading with pagination
        $supervisiUnderReviewList = Supervisi::with(['user:id,name,nik', 'reviewer:id,name'])
            ->whereIn('status', ['submitted', 'under_review'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Supervisi yang sudah completed - Eager Loading with pagination
        $supervisiCompletedList = Supervisi::with(['user:id,name,nik', 'reviewer:id,name'])
            ->where('status', 'completed')
            ->orderBy('reviewed_at', 'desc')
            ->take(10)
            ->get();

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
