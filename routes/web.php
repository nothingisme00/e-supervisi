<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD LOGIC BERDASARKAN ROLE
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'guru':
                return redirect()->route('guru.dashboard');
            case 'kepala_sekolah':
                return redirect()->route('kepala.dashboard');
            default:
                abort(403, 'Akses tidak diizinkan.');
        }
    })->name('dashboard');

    // ==========================
    // ADMIN ROUTES
    // ==========================
    Route::prefix('admin')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
        Route::resource('/users', UserController::class);
    });

    // ==========================
    // GURU ROUTES
    // ==========================
    Route::view('/guru/dashboard', 'guru.dashboard')->name('guru.dashboard');

    // ==========================
    // KEPALA SEKOLAH ROUTES
    // ==========================
    Route::view('/kepala/dashboard', 'kepala.dashboard')->name('kepala.dashboard');

    // ==========================
    // PROFILE ROUTES
    // ==========================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
