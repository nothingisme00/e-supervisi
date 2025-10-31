<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('isGuru', function ($user) {
            return $user->role === 'guru';
        });

        Gate::define('isKepalaSekolah', function ($user) {
            return $user->role === 'kepala_sekolah';
        });
    }
}