<?php

use Illuminate\Support\Facades\Route;

// 0. Halaman Utama
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 1. Penilaian Umum Evakuasi
Route::get('/penilaian-umum', function () {
    return view('umum_evakuasi');
})->name('form.umum');

// 2. Penilaian FSM
Route::get('/penilaian-fsm', function () {
    return view('fsm_evakuasi');
})->name('form.fsm');

// 3. Penilaian Tim Teknisi
Route::get('/penilaian-teknisi', function () {
    return view('teknisi_evakuasi');
})->name('form.teknisi');

// 4. Penilaian Tim Evakuasi
Route::get('/penilaian-tim-evakuasi', function () {
    return view('tim_evakuasi');
})->name('form.timevakuasi');

// 5. Penilaian Tim Penanganan Titik Kumpul
Route::get('/penilaian-titik-kumpul', function () {
    return view('titik_kumpul');
})->name('form.titikkumpul');

// 6. Penilaian Tim Rescue dan P3K
Route::get('/penilaian-rescue', function () {
    return view('rescue_p3k');
})->name('form.rescue');

// 7. Penilaian Tim Pemadam Kebakaran Internal
Route::get('/penilaian-pemadam-internal', function () {
    return view('pemadam_internal');
})->name('form.pemadam');

// 8. Penilaian Tim Pengamanan
Route::get('/penilaian-pengamanan', function () {
    return view('pengamanan');
})->name('form.pengamanan');

// Rute untuk Kalkulator Rekapitulasi
Route::get('/rekapitulasi', function () {
    return view('rekapitulasi');
})->name('rekap');
