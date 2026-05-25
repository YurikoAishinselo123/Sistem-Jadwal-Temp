<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Master Data Controllers
use App\Http\Controllers\Api\V1\MasterDataController;
use App\Http\Controllers\Api\V1\MakulController;
use App\Http\Controllers\Api\V1\DosenController;
use App\Http\Controllers\Api\V1\LaboranController;
use App\Http\Controllers\Api\V1\ProdiController;
use App\Http\Controllers\Api\V1\RuanganController;
use App\Http\Controllers\Api\V1\PeriodeController;

// Schedule Controller
use App\Http\Controllers\Api\V1\ScheduleController;

// Beban Kerja Controller
use App\Http\Controllers\Api\V1\BebanKerjaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // ─── Authentication Routes ──────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/register', [\App\Http\Controllers\Api\V1\AuthController::class, 'register'])->middleware('throttle:6,1');
        Route::post('/login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login'])->middleware('throttle:6,1');
        Route::post('/refresh', [\App\Http\Controllers\Api\V1\AuthController::class, 'refresh']);
        Route::post('/forgot-password', [\App\Http\Controllers\Api\V1\AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [\App\Http\Controllers\Api\V1\AuthController::class, 'resetPassword']);
        
        // Email Verification Routes
        Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Api\V1\AuthController::class, 'verifyEmail'])->name('verification.verify');
        
        Route::middleware('auth:api')->group(function () {
            Route::post('/email/resend', [\App\Http\Controllers\Api\V1\AuthController::class, 'resendVerificationEmail']);
            Route::post('/logout', [\App\Http\Controllers\Api\V1\AuthController::class, 'logout']);
            Route::get('/me', [\App\Http\Controllers\Api\V1\AuthController::class, 'me']);
        });
    });

    // ─── Public Read Endpoints ──────────────────────────────
    Route::get('master-data', [MasterDataController::class, 'index']);

    Route::apiResource('makuls', MakulController::class)->only(['index', 'show']);
    Route::apiResource('dosens', DosenController::class)->only(['index', 'show']);
    Route::apiResource('laborans', LaboranController::class)->only(['index', 'show']);
    Route::apiResource('prodis', ProdiController::class)->only(['index', 'show']);
    Route::apiResource('ruangans', RuanganController::class)->only(['index', 'show']);
    Route::apiResource('periodes', PeriodeController::class)->only(['index', 'show']);

    Route::get('jadwal', [ScheduleController::class, 'index']);
    Route::get('jadwal/{schedule}', [ScheduleController::class, 'show']);
    Route::get('schedules', [ScheduleController::class, 'index']);
    Route::get('schedules/{schedule}', [ScheduleController::class, 'show']);

    Route::match(['get', 'post'], 'beban-kerja/dosen', [BebanKerjaController::class, 'dosen']);
    Route::match(['get', 'post'], 'beban-kerja/ruangan', [BebanKerjaController::class, 'ruangan']);
    Route::match(['get', 'post'], 'beban-kerja/laboran', [BebanKerjaController::class, 'laboran']);

    // ─── Protected Write Endpoints ──────────────────────────
    Route::middleware('auth:api')->group(function () {
        Route::apiResource('makuls', MakulController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('dosens', DosenController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('laborans', LaboranController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('prodis', ProdiController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('ruangans', RuanganController::class)->only(['store', 'update', 'destroy']);
        Route::apiResource('periodes', PeriodeController::class)->only(['store', 'update', 'destroy']);
        Route::post('periodes/{periode}/tutup', [PeriodeController::class, 'tutup'])->name('periodes.tutup');

        Route::post('jadwal', [ScheduleController::class, 'store']);
        Route::put('jadwal/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('jadwal/{schedule}', [ScheduleController::class, 'destroy']);

        Route::post('schedules', [ScheduleController::class, 'store']);
        Route::put('schedules/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy']);
    });
});
