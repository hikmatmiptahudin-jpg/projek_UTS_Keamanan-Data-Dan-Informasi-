
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDataController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\EncryptionDemoController;

// Halaman utama
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Semua pengguna yang sudah login
Route::middleware(['auth'])->group(function () {

    // Dashboard untuk admin dan user
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Daftar seluruh ciphertext untuk admin dan user
    Route::get('/data', [AdminDataController::class, 'index'])
        ->name('data.index');

    // Profil untuk semua pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Fitur khusus admin
    Route::middleware(['admin'])->group(function () {

        // Tambah data
        Route::get('/data/create', [AdminDataController::class, 'create'])
            ->name('data.create');

        Route::post('/data', [AdminDataController::class, 'store'])
            ->name('data.store');

        // Edit dan update data
        Route::get('/data/{id}/edit', [AdminDataController::class, 'edit'])
            ->name('data.edit');

        Route::put('/data/{id}', [AdminDataController::class, 'update'])
            ->name('data.update');

        Route::patch('/data/{id}', [AdminDataController::class, 'update']);

        // Hapus data
        Route::delete('/data/{id}', [AdminDataController::class, 'destroy'])
            ->name('data.destroy');

        // Riwayat aktivitas
        Route::get('/history', [ActivityLogController::class, 'index'])
            ->name('history.index');

        // Halaman informasi enkripsi
        Route::get('/encryption', function () {
            return view('encryption.index');
        })->name('encryption.index');

        // Demo enkripsi
        Route::get('/encryption/demo', [EncryptionDemoController::class, 'index'])
            ->name('encryption.demo');

        Route::post('/encryption/demo', [EncryptionDemoController::class, 'process'])
            ->name('encryption.demo.process');
    });

    // Detail data untuk admin dan user.
    // Diletakkan paling akhir agar tidak menangkap URL /data/create.
    Route::get('/data/{id}', [AdminDataController::class, 'show'])
        ->name('data.show');

});

// Autentikasi Laravel Breeze
require __DIR__.'/auth.php';
