<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SinkronisasiController;
use App\Http\Controllers\SettingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
});
//Route::middleware(['auth', 'verified'])->group(function () {
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('sinkronisasi')->name('sinkronisasi.')->group(function () {
        Route::get('/', [SinkronisasiController::class, 'index'])->name('index');
        Route::get('/nilai', [SinkronisasiController::class, 'nilai'])->name('nilai');
        Route::get('/erapor', [SinkronisasiController::class, 'erapor'])->name('erapor');
    });
    Route::prefix('pengaturan')->name('settings.')->group(function () {
        Route::get('/aplikasi', [SettingController::class, 'index'])->name('index');
        Route::get('/users', [SettingController::class, 'users'])->name('users');
        Route::get('/database', [SettingController::class, 'database'])->name('database');
        Route::get('/changelog', [SettingController::class, 'changelog'])->name('changelog');
        Route::get('/check-update', [SettingController::class, 'check-update'])->name('check-update');
    });
    Route::get('/pusat-unduhan', [SettingController::class, 'pusat_unduhan'])->name('pusat-unduhan');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyAvatar'])->name('profile.photo.destroy');
});

require __DIR__.'/auth.php';
