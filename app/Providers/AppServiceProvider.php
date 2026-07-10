<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.modern', function ($view) {
            $user = auth()->user();
            $view->with('unreadNotifCount', $user ? $user->unreadNotifications()->count() : 0);
            $view->with('recentNotifs', $user ? $user->notifications()->take(5)->get() : collect());
        });
    }
}
