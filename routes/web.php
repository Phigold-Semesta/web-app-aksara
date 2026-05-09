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
| Semua rute telah disesuaikan dengan "use case diagram aplikasi AKSARA Revisi1_9.png"
| dan struktur folder di "direktori view aplikasi aksara_2.txt".
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

        // Master Data
        Route::prefix('master')->name('master.')->group(function() {
            Route::resource('user', AdminController::class); 
            Route::resource('kategori', AdminController::class);
            Route::resource('instruksi', AdminController::class);
        });

        // Manajemen Surat
        Route::resource('manajemen_surat', AdminController::class);

        // Manajemen Arsip
        Route::resource('manajemen_arsip', AdminController::class);

        // Monitoring Audit Log
        Route::get('/aktivitas', [AdminController::class, 'auditLog'])->name('aktivitas.index');
        
        // Statistik Tambahan
        Route::get('/statistik', [AdminController::class, 'lihatStatistik'])->name('statistik');
    });

    // ==========================================
    // 2. AKTOR: PETUGAS (Sempurna & Aktif)
    // ==========================================
    Route::middleware(['checkrole:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        
        // Dashboard (Statistik)
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');

        // PERBAIKAN: Route khusus untuk teruskan surat (Disesuaikan dengan pemanggilan di Blade)
        // Diletakkan di atas resource agar tidak bentrok dengan route standar Laravel
        Route::patch('/manajemen_surat/{id}/teruskan', [PetugasController::class, 'teruskanKePimpinan'])
             ->name('teruskan_pimpinan');

        // Manajemen Surat Resource
        Route::resource('manajemen_surat', PetugasController::class);
        
        // Route Tambahan untuk Status Surat
        Route::get('/manajemen_surat_status', [PetugasController::class, 'statusSurat'])->name('manajemen_surat.status');

        // Manajemen Arsip
        Route::get('/manajemen_arsip', [PetugasController::class, 'kelolaArsip'])->name('manajemen_arsip.index');
        
        // Arsip Operasional
        Route::resource('arsip_operasional', PetugasController::class)->except(['index']);

        // Statistik (Alias ke dashboard)
        Route::get('/statistik', [PetugasController::class, 'dashboard'])->name('statistik');
    });

    // ==========================================
    // 3. AKTOR: PIMPINAN
    // ==========================================
    Route::middleware(['checkrole:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');

        // Menerima & Meninjau Surat / Instruksi
        Route::resource('instruksi_surat', PimpinanController::class);

        // Monitoring Riwayat Surat
        Route::prefix('monitoring_riwayat')->name('monitoring_riwayat.')->group(function() {
            Route::get('/', [PimpinanController::class, 'monitoringRiwayat'])->name('index');
            Route::get('/{id}', [PimpinanController::class, 'showRiwayat'])->name('show');
        });

        // Monitoring Arsip Surat
        Route::prefix('monitoring_arsip')->name('monitoring_arsip.')->group(function() {
            Route::get('/', [PimpinanController::class, 'monitoringArsip'])->name('index');
            Route::get('/{id}', [PimpinanController::class, 'showArsip'])->name('show');
            Route::get('/{id}/download', [PimpinanController::class, 'downloadArsip'])->name('download');
        });

        // Monitoring Audit Log
        Route::get('/audit-log', [PimpinanController::class, 'auditLog'])->name('aktivitas.index');
        
        // Melihat Laporan Statistik
        Route::get('/statistik', [PimpinanController::class, 'lihatStatistik'])->name('statistik');
    });

});