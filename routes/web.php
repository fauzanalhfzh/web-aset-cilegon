<?php

use App\Http\Controllers\AsetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriAsetController;
use App\Http\Controllers\LaporanAsetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Management Routes
    Route::resource('users', UserController::class);
    Route::get('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');


    Route::resource('aset', AsetController::class);
    Route::get('aset/{aset}/approve', [AsetController::class, 'approve'])->name('aset.approve');
    Route::resource('kategori', KategoriAsetController::class);

    // Routes untuk Laporan Aset
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('aset', [LaporanAsetController::class, 'index'])->name('aset.index');
        Route::get('aset/{id}/detail', [LaporanAsetController::class, 'detail'])->name('aset.detail');
        Route::get('aset/export', [LaporanAsetController::class, 'export'])->name('aset.export');
        Route::get('aset/print', [LaporanAsetController::class, 'print'])->name('aset.print');
    });
});

require __DIR__ . '/auth.php';