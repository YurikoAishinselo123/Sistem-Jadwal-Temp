<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\MasterDataController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('master-data', [MasterDataController::class, 'index']);
    Route::apiResource('schedules', ScheduleController::class);
});
