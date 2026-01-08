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
            return $user->role === \App\Models\User::ROLE_ADMIN;
        });

        Gate::define('isGuru', function ($user) {
            return $user->role === \App\Models\User::ROLE_GURU;
        });

        Gate::define('isKepalaSekolah', function ($user) {
            return $user->role === \App\Models\User::ROLE_KEPALA_SEKOLAH;
        });
    }
}