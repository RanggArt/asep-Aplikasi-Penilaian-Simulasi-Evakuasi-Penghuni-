<?php

use App\Http\Controllers\ApemController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\SuperAdminSettingsController;
use App\Models\Apem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ==========================================
// PORTAL UTAMA
// ==========================================
Route::get('/', function () {
    $asepEnabled = \App\Support\AsepAvailability::enabled();

    return view('portal', compact('asepEnabled'));
})->name('home');

// ==========================================
// MENU APLIKASI ASEP
// ==========================================
Route::middleware(['asep.enabled', 'auth', 'can:manage-apem'])->group(function () {
    Route::view('/asep', 'welcome')->name('asep.index');
    Route::view('/penilaian-umum', 'umum_evakuasi')->name('form.umum');
    Route::view('/penilaian-fsm', 'fsm_evakuasi')->name('form.fsm');
    Route::view('/penilaian-teknisi', 'teknisi_evakuasi')->name('form.teknisi');
    Route::view('/penilaian-tim-evakuasi', 'tim_evakuasi')->name('form.timevakuasi');
    Route::view('/penilaian-titik-kumpul', 'titik_kumpul')->name('form.titikkumpul');
    Route::view('/penilaian-rescue', 'rescue_p3k')->name('form.rescue');
    Route::view('/penilaian-pemadam-internal', 'pemadam_internal')->name('form.pemadam');
    Route::view('/penilaian-pengamanan', 'pengamanan')->name('form.pengamanan');
    Route::view('/rekapitulasi', 'rekapitulasi')->name('rekap');
});

// ==========================================
// GOOGLE LOGIN (PENDAFTAR)
// ==========================================
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// ==========================================
// RUTE YANG WAJIB LOGIN (AUTH)
// ==========================================
Route::middleware('auth')->group(function () {

    // 1. Dashboard Pendaftar
    Route::get('/dashboard', function () {
        // Gunakan get() agar bisa menampilkan lebih dari 1 riwayat pengajuan di tampilan
        $pengajuan = Apem::where('email', Auth::user()->email)->orderBy('created_at', 'desc')->get();

        // Arahkan ke file user-apem.blade.php
        return view('user-apem', compact('pengajuan'));
    })->name('dashboard');

    // 2. Formulir Pendaftar APEM
    Route::get('/apem', fn() => view('apem'))->name('apem.index');
    Route::post('/apem/submit', [ApemController::class, 'store'])->name('apem.store');

    // 3. Panel Admin APEM
    Route::middleware('can:manage-apem')->group(function () {
        Route::get('/admin/apem', [ApemController::class, 'indexAdmin'])->name('admin.apem.index');
        Route::get('/admin/apem/{id}/dokumen/{field}', [ApemController::class, 'showDocument'])->name('admin.apem.document');
        Route::post('/admin/apem/{id}/keputusan', [ApemController::class, 'updateStatus'])->name('admin.apem.keputusan');
    });

    Route::middleware('can:manage-app-settings')->group(function () {
        Route::get('/super-admin/pengaturan', [SuperAdminSettingsController::class, 'edit'])->name('super-admin.settings');
        Route::put('/super-admin/pengaturan/asep', [SuperAdminSettingsController::class, 'updateAsep'])->name('super-admin.settings.asep');
    });
});

// ==========================================
// RUTE AUTENTIKASI BREEZE
// ==========================================
require __DIR__ . '/auth.php';
