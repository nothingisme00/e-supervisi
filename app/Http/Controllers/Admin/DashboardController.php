<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supervisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalUsers = User::count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalSupervisi = Supervisi::count();
        $supervisiSubmitted = Supervisi::where('status', 'submitted')->count();
        $supervisiUnderReview = Supervisi::where('status', 'under_review')->count();
        $supervisiCompleted = Supervisi::where('status', 'completed')->count();

        // Guru dengan statistik supervisi mereka
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
            ->get();

        // Supervisi yang sedang direview
        $supervisiUnderReviewList = Supervisi::with(['user', 'reviewer'])
            ->whereIn('status', ['submitted', 'under_review'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Supervisi yang sudah completed (latest)
        $supervisiCompletedList = Supervisi::with(['user', 'reviewer'])
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
            'guruList',
            'supervisiUnderReviewList',
            'supervisiCompletedList'
        ));
    }
}
