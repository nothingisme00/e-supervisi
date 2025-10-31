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

        $credentials = [
            'nik' => $request->nik,
            'password' => $request->password,
            'role' => $request->role,
            'is_active' => true
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isGuru()) {
                return redirect()->route('guru.home');
            } elseif ($user->isKepalaSekolah()) {
                return redirect()->route('kepala.dashboard');
            }
        }

        return back()->withErrors([
            'nik' => 'NIK atau password salah, atau role tidak sesuai.',
        ])->onlyInput('nik', 'role');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}