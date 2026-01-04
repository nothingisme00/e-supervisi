<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'data' => $request->user()
    ]);
});

// API Routes untuk integrasi eksternal
// Semua routes menggunakan auth:sanctum middleware untuk keamanan
// Format response: { success: boolean, message: string, data: object }

// Contoh struktur API routes (uncomment jika sudah ada controller)
/*
Route::middleware('auth:sanctum')->group(function () {
    // Supervisi API
    Route::prefix('supervisi')->group(function () {
        Route::get('/', [SupervisiApiController::class, 'index']);
        Route::get('/{id}', [SupervisiApiController::class, 'show']);
        Route::post('/', [SupervisiApiController::class, 'store']);
        Route::put('/{id}', [SupervisiApiController::class, 'update']);
        Route::delete('/{id}', [SupervisiApiController::class, 'destroy']);
    });
    
    // User API
    Route::prefix('users')->group(function () {
        Route::get('/', [UserApiController::class, 'index']);
        Route::get('/{id}', [UserApiController::class, 'show']);
    });
});
*/
