<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        // Get active carousel slides
        $carouselSlides = CarouselSlide::active()->ordered()->get();
        
        // Add cache control headers to prevent browser caching
        return response()
            ->view('auth.login', compact('carouselSlides'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:16',
            'password' => 'required|string',
            'role' => 'required|in:admin,guru,kepala_sekolah'
        ]);

        // Credentials untuk Auth::attempt (hanya field yang ada di user table untuk autentikasi)
        $credentials = [
            'nik' => $request->nik,
            'password' => $request->password,
            'role' => $request->role
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();

            // Cek apakah user aktif
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'nik' => 'NIK, password, atau role tidak sesuai.',
                ])->onlyInput('nik', 'role');
            }

            $request->session()->regenerate();
            
            // Set session flag untuk menampilkan welcome modal
            session(['just_logged_in' => true]);

            // Cek apakah user harus mengganti password
            if ($user->must_change_password) {
                return redirect()->route('change-password')
                    ->with('info', 'Anda harus mengganti password default untuk keamanan akun Anda.');
            }

            // Redirect berdasarkan role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isGuru()) {
                return redirect()->route('guru.home');
            } elseif ($user->isKepalaSekolah()) {
                return redirect()->route('kepala.dashboard');
            }
        }

        return back()->withErrors([
            'nik' => 'NIK, password, atau role tidak sesuai.',
        ])->onlyInput('nik', 'role');
    }

    public function logout(Request $request)
    {
        $userName = Auth::check() ? Auth::user()->name : null;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($userName) {
            return redirect('/')->with('success', 'Anda telah berhasil logout. Sampai jumpa, ' . $userName . '!');
        }
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}