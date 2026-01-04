<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    /**
     * Show the change password form
     */
    public function showChangePasswordForm()
    {
        // Redirect if user doesn't need to change password
        if (!auth()->user()->must_change_password) {
            $user = auth()->user();
            if ($user->role === 'guru') {
                return redirect()->route('guru.home');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'kepala_sekolah') {
                return redirect()->route('kepala.dashboard');
            }
        }

        // Get active carousel slides
        $carouselSlides = CarouselSlide::active()->ordered()->get();

        // Add cache control headers to prevent browser caching
        return response()
            ->view('auth.change-password', compact('carouselSlides'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
        ]);

        $user = auth()->user();

        // Update password and remove must_change_password flag
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        // Redirect based on role
        if ($user->role === 'guru') {
            return redirect()->route('guru.home')
                ->with('success', 'Password berhasil diubah. Selamat datang di sistem E-Supervisi!');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Password berhasil diubah. Selamat datang di sistem E-Supervisi!');
        } elseif ($user->role === 'kepala_sekolah') {
            return redirect()->route('kepala.dashboard')
                ->with('success', 'Password berhasil diubah. Selamat datang di sistem E-Supervisi!');
        }

        // Fallback
        return redirect('/')
            ->with('success', 'Password berhasil diubah.');
    }
}
