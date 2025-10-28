<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Guru\SupervisiController;
use Illuminate\Support\Facades\Auth;

// ✅ Halaman root
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $role = auth()->user()->role;

    return match ($role) {
        'admin' => to_route('admin.dashboard'),
        'guru' => to_route('guru.dashboard'),
        'kepala_sekolah' => to_route('kepala_sekolah.dashboard'),
        default => to_route('login'),
    };
});

// ✅ Route untuk user yang sudah login
Route::middleware(['auth','prevent-back-history'])->group(function () {

    // ADMIN
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('/users', UserController::class);
    });

    // GURU
    Route::prefix('guru')->middleware('role:guru')->group(function () {
        Route::get('/dashboard', function () {
            return view('guru.dashboard');
        })->name('guru.dashboard');

        Route::get('/supervisi/create', [SupervisiController::class, 'create'])->name('guru.supervisi.create');
        Route::post('/supervisi', [SupervisiController::class, 'store'])->name('guru.supervisi.store');
    });

    // KEPALA SEKOLAH
    Route::prefix('kepala_sekolah')
    ->middleware(['auth', 'prevent-back-history', 'role:kepala_sekolah'])
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\KepalaSekolah\DashboardController::class, 'index'])
            ->name('kepala_sekolah.dashboard');
    });

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
