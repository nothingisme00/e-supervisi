<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * Clear all dashboard statistics cache
     */
    public static function clearDashboardCache()
    {
        $keys = [
            'stats.total_users',
            'stats.total_guru',
            'stats.total_supervisi',
            'stats.supervisi_pending',
            'stats.supervisi_in_progress',
            'stats.supervisi_reviewed',
            'kepala.stats.total_supervisi',
            'kepala.stats.supervisi_pending',
            'kepala.stats.supervisi_in_progress',
            'kepala.stats.supervisi_reviewed',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Clear user-related cache
     */
    public static function clearUserCache()
    {
        Cache::forget('stats.total_users');
        Cache::forget('stats.total_guru');
    }

    /**
     * Clear supervisi-related cache
     */
    public static function clearSupervisiCache()
    {
        $keys = [
            'stats.total_supervisi',
            'stats.supervisi_pending',
            'stats.supervisi_in_progress',
            'stats.supervisi_reviewed',
            'kepala.stats.total_supervisi',
            'kepala.stats.supervisi_pending',
            'kepala.stats.supervisi_in_progress',
            'kepala.stats.supervisi_reviewed',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
