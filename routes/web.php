<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('test');
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
