<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\KelasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Ini adalah definisi semua route aplikasi web.
| Route akan diproses oleh RouteServiceProvider dan menggunakan middleware "web".
|
*/

// -------------------------
// AUTH ROUTES
// -------------------------
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.index'));
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// -------------------------
// PROTECTED ROUTES (auth)
// -------------------------
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard.index'))->name('dashboard');

    // Absensi
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/simpan', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/history', [AbsensiController::class, 'history'])->name('absensi.history');
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');
    Route::get('/absensi/export-pdf', [AbsensiController::class, 'exportPdf'])->name('absensi.exportPdf');

    // Resource: Siswa & Kelas
    Route::resource('siswa', SiswaController::class);
    Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);

});
