<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\Guru\HomeController as GuruHomeController;
use App\Http\Controllers\Guru\SupervisiController;
use App\Http\Controllers\Guru\ProsesController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\KepalaSekolah\DashboardController as KepalaDashboardController;
use App\Http\Controllers\KepalaSekolah\EvaluasiController;

// Public Routes
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isGuru()) {
            return redirect()->route('guru.home');
        } elseif ($user->isKepalaSekolah()) {
            return redirect()->route('kepala.dashboard');
        }
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [CustomLoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

// Change Password Routes (must be authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password.show');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');
});

// Protected Routes
Route::middleware(['auth', 'prevent.back'])->group(function () {

    // Guru Routes
    Route::prefix('guru')->name('guru.')->middleware('can:isGuru')->group(function () {
        Route::get('/home', [GuruHomeController::class, 'index'])->name('home');
        
        // Supervisi Routes
        Route::prefix('supervisi')->name('supervisi.')->group(function () {
            Route::get('/create', [SupervisiController::class, 'create'])->name('create');
            Route::get('/{id}/continue', [SupervisiController::class, 'continue'])->name('continue');
            Route::get('/{id}/evaluasi', [SupervisiController::class, 'showEvaluasi'])->name('evaluasi');
            Route::post('/{id}/upload', [SupervisiController::class, 'uploadDocument'])->name('upload');
            Route::post('/{id}/delete-document', [SupervisiController::class, 'deleteDocument'])->name('delete-document');
            Route::get('/{id}/check-documents', [SupervisiController::class, 'checkDocuments'])->name('check-documents');
            Route::get('/{id}/detail', [GuruHomeController::class, 'detail'])->name('detail');
            
            // Proses Routes
            Route::get('/{id}/proses', [ProsesController::class, 'show'])->name('proses');
            Route::post('/{id}/proses/save', [ProsesController::class, 'save'])->name('proses.save');
            Route::post('/{id}/submit', [ProsesController::class, 'submit'])->name('submit');
        });
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('can:isAdmin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', AdminUserController::class);
        Route::post('/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Kepala Sekolah Routes
    Route::prefix('kepala')->name('kepala.')->middleware('can:isKepalaSekolah')->group(function () {
        Route::get('/dashboard', [KepalaDashboardController::class, 'index'])->name('dashboard');
        
        // Evaluasi Routes
        Route::prefix('evaluasi')->name('evaluasi.')->group(function () {
            Route::get('/', [EvaluasiController::class, 'index'])->name('index');
            Route::get('/{id}', [EvaluasiController::class, 'show'])->name('show');
            Route::post('/{id}/feedback', [EvaluasiController::class, 'giveFeedback'])->name('feedback');
            Route::post('/{id}/complete', [EvaluasiController::class, 'complete'])->name('complete');
        });
    });
});