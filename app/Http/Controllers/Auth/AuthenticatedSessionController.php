<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'nik' => ['required', 'string'],
        'password' => ['required', 'string'],
        'role' => ['required', 'string'],
    ]);

    // Coba autentikasi berdasarkan NIK dan password
    if (!Auth::attempt($request->only('nik', 'password'), $request->boolean('remember'))) {
        return back()->withErrors([
            'nik' => 'NIK atau password salah.',
        ])->withInput($request->only('nik', 'role'));
    }

    // Regenerasi session setelah login berhasil
    $request->session()->regenerate();

    $user = Auth::user();

    // 🔍 Validasi role yang dipilih di form dengan role di database
    if ($user->role !== $request->role) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return back()->withErrors([
            'role' => "Login gagal. Anda terdaftar sebagai " . ucfirst($user->role) . ", bukan sebagai " . ucfirst($request->role) . ".",
        ])->withInput($request->only('nik'));
    }

    // ✅ Redirect ke dashboard sesuai role yang benar
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'guru' => redirect()->route('guru.dashboard'),
        'kepala_sekolah' => redirect()->route('kepala_sekolah.dashboard'),
        default => redirect()->route('login'),
    };
}





    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{
    auth()->guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}

}
