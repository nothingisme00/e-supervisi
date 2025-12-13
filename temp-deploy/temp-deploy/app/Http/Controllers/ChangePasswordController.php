<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.letters' => 'Password harus mengandung huruf.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        // Update password dan set must_change_password = false
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false
        ]);

        // Redirect berdasarkan role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Password berhasil diubah!');
        } elseif ($user->isGuru()) {
            return redirect()->route('guru.home')->with('success', 'Password berhasil diubah!');
        } elseif ($user->isKepalaSekolah()) {
            return redirect()->route('kepala.dashboard')->with('success', 'Password berhasil diubah!');
        }

        return redirect('/');
    }
}
