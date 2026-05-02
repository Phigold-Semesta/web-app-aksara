<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PimpinanController;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi AKSARA LPSE Karawang
|--------------------------------------------------------------------------
*/

// --- HALAMAN UTAMA / LOGIN ---
Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- AREA TERPROTEKSI (Wajib Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Fitur Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Dashboard Universal (Redirector)
    Route::get('/dashboard', function() {
        return redirect()->route(auth()->user()->role . '.dashboard');
    })->name('dashboard');

    // ==========================================
    // 1. AKTOR: ADMINISTRATOR
    // ==========================================
    Route::middleware(['checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Master Data (Folder: admin/master/...)
        Route::prefix('master')->name('master.')->group(function() {
            // User
            Route::resource('user', AdminController::class); // Ganti method di controller sesuai resource
            // Kategori
            Route::resource('kategori', AdminController::class);
            // Instruksi
            Route::resource('instruksi', AdminController::class);
        });

        // Manajemen Surat (Folder: admin/manajemen_surat/...)
        Route::resource('surat', AdminController::class);

        // Manajemen Arsip (Folder: admin/manajemen_arsip/...)
        Route::resource('arsip', AdminController::class);

        // Aktivitas / Audit Log (Folder: admin/aktivitas/...)
        Route::get('/aktivitas', [AdminController::class, 'auditLog'])->name('aktivitas.index');
        
        // Statistik
        Route::get('/statistik', [AdminController::class, 'lihatStatistik'])->name('statistik');
    });

    // ==========================================
    // 2. AKTOR: PETUGAS
    // ==========================================
    Route::middleware(['checkrole:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        
        // Dashboard (Sesuai Revisi: petugas/dashboard.blade.php)
        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');

        // Manajemen Surat (Folder: petugas/manajemen_surat/...)
        // Fokus: create, store, index, show
        Route::resource('surat', PetugasController::class);
        Route::get('/surat/{id}/teruskan', [PetugasController::class, 'teruskanKePimpinan'])->name('surat.teruskan');

        // Manajemen Arsip (Folder: petugas/manajemen_arsip/...)
        Route::resource('arsip', PetugasController::class);

        // Statistik
        Route::get('/statistik', [PetugasController::class, 'lihatStatistik'])->name('statistik');
    });

    // ==========================================
    // 3. AKTOR: PIMPINAN
    // ==========================================
    Route::middleware(['checkrole:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');

        // Instruksi Surat / Disposisi (Folder: pimpinan/instruksi_surat/...)
        Route::resource('instruksi-surat', PimpinanController::class);

        // Monitoring Riwayat / Tracking (Folder: pimpinan/monitoring_riwayat/...)
        Route::prefix('monitoring-riwayat')->name('monitoring-riwayat.')->group(function() {
            Route::get('/', [PimpinanController::class, 'monitoringRiwayat'])->name('index');
            Route::get('/{id}', [PimpinanController::class, 'showRiwayat'])->name('show');
        });

        // Monitoring Arsip (Folder: pimpinan/monitoring_arsip/...)
        Route::prefix('monitoring-arsip')->name('monitoring-arsip.')->group(function() {
            Route::get('/', [PimpinanController::class, 'monitoringArsip'])->name('index');
            Route::get('/{id}', [PimpinanController::class, 'showArsip'])->name('show');
            Route::get('/{id}/download', [PimpinanController::class, 'downloadArsip'])->name('download');
        });

        // Statistik
        Route::get('/statistik', [PimpinanController::class, 'lihatStatistik'])->name('statistik');
    });

});