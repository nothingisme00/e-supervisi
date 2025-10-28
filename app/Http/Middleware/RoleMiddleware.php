<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Jika belum login, arahkan ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Jika role user tidak sesuai
        if (!in_array($user->role, $roles)) {
            // Logout paksa dan arahkan ke login
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'access' => 'Anda tidak memiliki izin untuk mengakses halaman ini.',
            ]);
        }

        return $next($request);
    }
}
