<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CacheHelper;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheHelperTest extends TestCase
{
    public function test_clear_dashboard_cache_removes_all_keys(): void
    {
        // Set some cache values
        Cache::put('stats.total_supervisi', 10);
        Cache::put('stats.total_users', 5);
        Cache::put('stats.total_guru', 3);
        Cache::put('stats.supervisi_all', ['total' => 10]);

        CacheHelper::clearDashboardCache();

        $this->assertNull(Cache::get('stats.total_supervisi'));
        $this->assertNull(Cache::get('stats.total_users'));
        $this->assertNull(Cache::get('stats.total_guru'));
        $this->assertNull(Cache::get('stats.supervisi_all'));
    }

    public function test_clear_user_cache(): void
    {
        Cache::put('stats.total_users', 5);
        Cache::put('stats.total_guru', 3);
        Cache::put('stats.users_all', ['total' => 5]);
        Cache::put('admin.guru_list', []);

        CacheHelper::clearUserCache();

        $this->assertNull(Cache::get('stats.total_users'));
        $this->assertNull(Cache::get('stats.total_guru'));
        $this->assertNull(Cache::get('stats.users_all'));
        $this->assertNull(Cache::get('admin.guru_list'));
    }

    public function test_clear_supervisi_cache(): void
    {
        Cache::put('stats.total_supervisi', 10);
        Cache::put('stats.supervisi_pending', 3);
        Cache::put('stats.supervisi_all', ['total' => 10]);
        Cache::put('admin.recent_supervisi', []);

        CacheHelper::clearSupervisiCache();

        $this->assertNull(Cache::get('stats.total_supervisi'));
        $this->assertNull(Cache::get('stats.supervisi_pending'));
        $this->assertNull(Cache::get('stats.supervisi_all'));
        $this->assertNull(Cache::get('admin.recent_supervisi'));
    }

    public function test_clear_cache_does_not_affect_unrelated_keys(): void
    {
        Cache::put('unrelated_key', 'should_remain');
        Cache::put('stats.total_users', 5);

        CacheHelper::clearUserCache();

        $this->assertEquals('should_remain', Cache::get('unrelated_key'));
    }
}
