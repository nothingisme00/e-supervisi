<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    /**
     * All supervisi-related cache keys
     */
    private static function getSupervisiCacheKeys(): array
    {
        return [
            // Legacy individual keys
            'stats.total_supervisi',
            'stats.supervisi_pending',
            'stats.supervisi_in_progress',
            'stats.supervisi_reviewed',
            // Admin consolidated keys
            'stats.supervisi_all',
            'admin.guru_list',
            'admin.recent_supervisi',
            // Kepala consolidated keys
            'kepala.stats.all',
            'kepala.stats.total_supervisi',
            'kepala.stats.supervisi_pending',
            'kepala.stats.supervisi_in_progress',
            'kepala.stats.supervisi_reviewed',
            'kepala.recent_supervisi',
        ];
    }

    /**
     * All user-related cache keys
     */
    private static function getUserCacheKeys(): array
    {
        return [
            'stats.total_users',
            'stats.total_guru',
            'stats.users_all',      // Admin consolidated user stats
            'admin.guru_list',      // Guru list in admin dashboard
        ];
    }

    /**
     * Clear all dashboard statistics cache
     */
    public static function clearDashboardCache(): void
    {
        $keys = array_merge(
            self::getUserCacheKeys(),
            self::getSupervisiCacheKeys()
        );

        self::forgetKeys($keys);
    }

    /**
     * Clear user-related cache
     */
    public static function clearUserCache(): void
    {
        self::forgetKeys(self::getUserCacheKeys());
    }

    /**
     * Clear supervisi-related cache
     */
    public static function clearSupervisiCache(): void
    {
        self::forgetKeys(self::getSupervisiCacheKeys());
    }

    /**
     * Helper to forget multiple cache keys efficiently
     */
    private static function forgetKeys(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}
