<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        // Jika belum login → arahkan ke halaman login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $currentRoute = $request->route()->getName();

        // Tentukan dashboard sesuai role user
        $targetRoute = match ($user->role) {
            'admin' => 'admin.dashboard',
            'guru' => 'guru.dashboard',
            'kepala_sekolah' => 'kepala_sekolah.dashboard',
            default => 'login',
        };

        // 🚫 Jika role tidak cocok, arahkan ke dashboard yang benar
        if (!in_array($user->role, $roles)) {
            // Hindari loop jika sudah di dashboard sesuai role
            if ($currentRoute === $targetRoute) {
                return $next($request);
            }

            return redirect()->route($targetRoute);
        }

        return $next($request);
    }
}
