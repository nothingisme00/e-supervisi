<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Waktu timeout dalam detik — satu sumber: config session.lifetime (menit)
     */
    protected function timeout(): int
    {
        return (int) config('session.lifetime') * 60;
    }

    /**
     * Handle an incoming request.
     * 
     * Middleware ini memeriksa apakah session user sudah expired berdasarkan
     * last_activity di database. Ini memastikan user ter-logout walaupun
     * menutup browser/tab tanpa logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check session timeout when using database session driver
        // (skip for array/file drivers used in testing)
        // PERHATIAN: produksi harus SESSION_DRIVER=database — driver lain
        // menonaktifkan enforcement idle-timeout ini (lihat .env.example)
        if (config('session.driver') !== 'database') {
            return $next($request);
        }

        if (Auth::check()) {
            $session = DB::table('sessions')
                ->where('user_id', Auth::id())
                ->orderBy('last_activity', 'desc')
                ->first();

            if ($session) {
                $lastActivity = $session->last_activity;
                $currentTime = time();
                
                // Jika session sudah expired (lebih dari timeout)
                if (($currentTime - $lastActivity) > $this->timeout()) {
                    // Hapus semua session user ini dari database
                    DB::table('sessions')
                        ->where('user_id', Auth::id())
                        ->delete();
                    
                    // Logout user
                    Auth::logout();
                    
                    // Invalidate session
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    // Redirect ke login dengan pesan
                    return redirect()->route('login')
                        ->with('warning', 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan login kembali.');
                }
            }
        }

        return $next($request);
    }
}
