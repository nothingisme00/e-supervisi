<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // Rate limiting untuk security
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik.");
        }

        $credentials = $request->validate([
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            // Cache user data untuk performa
            $user = Auth::user();
            Cache::put('user.' . $user->id, $user, now()->addMinutes(30));

            // Redirect berdasarkan role
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'guru' => redirect()->route('guru.dashboard'),
                'kepala' => redirect()->route('kepala.dashboard'),
                default => redirect()->route('dashboard')
            };
        }

        RateLimiter::hit($key);

        return back()->withErrors([
            'nik' => 'NIK atau password salah.',
        ])->onlyInput('nik');
    }

    public function destroy(Request $request): RedirectResponse
    {
        // Clear cache
        Cache::forget('user.' . Auth::id());
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}