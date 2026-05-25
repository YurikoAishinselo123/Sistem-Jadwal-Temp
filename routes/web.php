<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/jadwal');
});

Route::get('/master-data', function () {
    return view('master-data');
});

Route::get('/beban-kerja', function () {
    return view('beban-kerja');
});

Route::get('/jadwal', function () {
    return view('jadwal');
});

Route::get('/login', function () {
    return view('auth-test');
});

Route::redirect('/auth-test', '/login');

// Google Auth Routes (uses web middleware with session store)
Route::get('/auth/google/redirect', [\App\Http\Controllers\Api\V1\AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [\App\Http\Controllers\Api\V1\AuthController::class, 'handleGoogleCallback']);
