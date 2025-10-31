<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        // Add cache control headers to prevent browser caching
        return response()
            ->view('auth.custom-login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:18',
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
                    'nik' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ])->onlyInput('nik', 'role');
            }

            $request->session()->regenerate();

            // Redirect berdasarkan role dengan pesan sukses
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
            } elseif ($user->isGuru()) {
                return redirect()->route('guru.home')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            } elseif ($user->isKepalaSekolah()) {
                return redirect()->route('kepala.dashboard')->with('success', 'Selamat datang, ' . $user->name . '!');
            }
        }

        return back()->withErrors([
            'nik' => 'NIK, password, atau role tidak sesuai.',
        ])->onlyInput('nik', 'role');
    }

    public function logout(Request $request)
    {
        $userName = Auth::user()->name;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout. Sampai jumpa, ' . $userName . '!');
    }
}