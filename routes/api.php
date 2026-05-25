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

    // ─── Aggregated Master Data (for frontend dropdowns) ───
    Route::get('master-data', [MasterDataController::class, 'index']);

    // ─── Master Data CRUD ───────────────────────────────────
    Route::apiResource('makuls',   MakulController::class);
    Route::apiResource('dosens',   DosenController::class);
    Route::apiResource('laborans', LaboranController::class);
    Route::apiResource('prodis',   ProdiController::class);
    Route::apiResource('ruangans', RuanganController::class);

    // Periodes: CRUD + Tutup action
    Route::apiResource('periodes', PeriodeController::class);
    Route::post('periodes/{periode}/tutup', [PeriodeController::class, 'tutup'])
         ->name('periodes.tutup');

    // ─── Schedules ──────────────────────────────────────────
    Route::apiResource('schedules', ScheduleController::class);

    // ─── Beban Kerja ────────────────────────────────────────
    Route::post('beban-kerja/dosen', [BebanKerjaController::class, 'dosen']);
    Route::post('beban-kerja/ruangan', [BebanKerjaController::class, 'ruangan']);
});
