<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDataController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\EncryptionDemoController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\RsaAuthController;

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

// Halaman utama menampilkan login RSA
Route::get('/', [LandingPageController::class, 'index'])
    ->name('landing');


/*
|--------------------------------------------------------------------------
| AUTENTIKASI ADMINISTRATOR RSA
|--------------------------------------------------------------------------
*/

// Menampilkan halaman login RSA
Route::get('/admin/login', [RsaAuthController::class, 'showLogin'])
    ->name('rsa.login');

// Membuat challenge RSA
Route::post('/admin/challenge', [RsaAuthController::class, 'challenge'])
    ->name('rsa.challenge');

// Memverifikasi tanda tangan RSA
Route::post('/admin/verify', [RsaAuthController::class, 'verify'])
    ->name('rsa.verify');

// Masuk dashboard dalam mode ciphertext
Route::post('/admin/ciphertext', [RsaAuthController::class, 'ciphertextMode'])
    ->name('rsa.ciphertext');

// Logout RSA
Route::post('/admin/logout', [RsaAuthController::class, 'logout'])
    ->name('rsa.logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD DAN DATA PUBLIK (MODE CIPHERTEXT)
|--------------------------------------------------------------------------
*/

// Dashboard bisa diakses tanpa login
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Daftar data terenkripsi
Route::get('/data', [AdminDataController::class, 'index'])
    ->name('data.index');

// Detail data terenkripsi
Route::get('/data/{id}', [AdminDataController::class, 'show'])
    ->name('data.show');


/*
|--------------------------------------------------------------------------
| FITUR KHUSUS ADMINISTRATOR RSA
|--------------------------------------------------------------------------
*/

// Seluruh route di dalam grup ini wajib memiliki session RSA valid
Route::middleware(['rsa.admin'])->group(function () {

    // Menampilkan form tambah data
    Route::get('/data/create', [AdminDataController::class, 'create'])
        ->name('data.create');

    // Menyimpan data terenkripsi
    Route::post('/data', [AdminDataController::class, 'store'])
        ->name('data.store');

    // Menampilkan form edit
    Route::get('/data/{id}/edit', [AdminDataController::class, 'edit'])
        ->name('data.edit');

    // Memperbarui data
    Route::put('/data/{id}', [AdminDataController::class, 'update'])
        ->name('data.update');

    // Menghapus data
    Route::delete('/data/{id}', [AdminDataController::class, 'destroy'])
        ->name('data.destroy');

    // Riwayat aktivitas
    Route::get('/history', [ActivityLogController::class, 'index'])
        ->name('history.index');

    // Informasi enkripsi
    Route::get('/encryption', function () {
        return view('encryption.index');
    })->name('encryption.index');

    // Demo enkripsi
    Route::get('/encryption/demo', [EncryptionDemoController::class, 'index'])
        ->name('encryption.demo');

    Route::post('/encryption/demo', [EncryptionDemoController::class, 'process'])
        ->name('encryption.demo.process');

});
