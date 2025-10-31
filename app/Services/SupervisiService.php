<?php

namespace App\Services;

use App\Models\Supervisi;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SupervisiService
{
    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(string $role, ?int $userId = null): array
    {
        return Cache::remember("dashboard.stats.{$role}.{$userId}", 300, function () use ($role, $userId) {
            switch ($role) {
                case 'admin':
                    return [
                        'totalGuru' => User::where('role', 'guru')->count(),
                        'totalSupervisi' => Supervisi::count(),
                        'pendingReview' => Supervisi::where('status', 'pending')->count(),
                        'totalKepala' => User::where('role', 'kepala')->count(),
                    ];
                
                case 'guru':
                    return [
                        'totalSupervisi' => Supervisi::where('guru_id', $userId)->count(),
                        'pending' => Supervisi::where('guru_id', $userId)->where('status', 'pending')->count(),
                        'approved' => Supervisi::where('guru_id', $userId)->where('status', 'approved')->count(),
                        'rejected' => Supervisi::where('guru_id', $userId)->where('status', 'rejected')->count(),
                    ];
                
                case 'kepala':
                    return [
                        'totalSupervisi' => Supervisi::count(),
                        'pendingReview' => Supervisi::where('status', 'pending')->count(),
                        'reviewed' => Supervisi::where('status', '!=', 'pending')->count(),
                        'totalGuru' => User::where('role', 'guru')->count(),
                    ];
                
                default:
                    return [];
            }
        });
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 5): \Illuminate\Support\Collection
    {
        return Cache::remember('recent.activities', 60, function () use ($limit) {
            return DB::table('supervisis')
                ->join('users', 'supervisis.guru_id', '=', 'users.id')
                ->select(
                    'supervisis.id',
                    'users.name',
                    'supervisis.materi',
                    'supervisis.created_at',
                    DB::raw("CONCAT('Supervisi baru dari ', users.name) as description")
                )
                ->orderBy('supervisis.created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }
}