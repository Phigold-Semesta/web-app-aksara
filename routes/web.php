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
        
        // Dashboard (Melihat Laporan Statistik terintegrasi di sini)[cite: 1]
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Master Data (Folder: admin/master/...)[cite: 2]
        Route::prefix('master')->name('master.')->group(function() {
            // Mengelola Data User[cite: 1]
            Route::resource('user', AdminController::class); 
            // Mengelola Master Kategori[cite: 1]
            Route::resource('kategori', AdminController::class);
            // Mengelola Master Instruksi Pimpinan[cite: 1]
            Route::resource('instruksi', AdminController::class);
        });

        // Manajemen Surat (Input & Digitalisasi)[cite: 1]
        // Sesuai Sidebar: admin.manajemen_surat.index
        Route::resource('manajemen_surat', AdminController::class);

        // Manajemen Arsip[cite: 1]
        Route::resource('manajemen_arsip', AdminController::class);

        // Monitoring Audit Log[cite: 1]
        Route::get('/aktivitas', [AdminController::class, 'auditLog'])->name('aktivitas.index');
        
        // Statistik Tambahan
        Route::get('/statistik', [AdminController::class, 'lihatStatistik'])->name('statistik');
    });

    // ==========================================
    // 2. AKTOR: PETUGAS
    // ==========================================
    Route::middleware(['checkrole:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        
        // Dashboard[cite: 2]
        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');

        // Input & Digitalisasi Surat[cite: 1]
        // Sesuai Sidebar: petugas.manajemen_surat.index
        Route::resource('manajemen_surat', PetugasController::class);
        
        // Meneruskan Surat Ke Pimpinan[cite: 1]
        Route::get('/surat/{id}/teruskan', [PetugasController::class, 'teruskanKePimpinan'])->name('surat.teruskan');

        // Manajemen Arsip (Input Lokasi & Cek Retensi)[cite: 1]
        Route::resource('manajemen_arsip', PetugasController::class);

        // Melihat Laporan Statistik[cite: 1]
        Route::get('/statistik', [PetugasController::class, 'statistik'])->name('statistik');
    });

    // ==========================================
    // 3. AKTOR: PIMPINAN
    // ==========================================
    Route::middleware(['checkrole:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        
        // Dashboard[cite: 2]
        Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');

        // Menerima & Meninjau Surat / Instruksi[cite: 1]
        // Sesuai Sidebar: pimpinan.instruksi_surat.index
        Route::resource('instruksi_surat', PimpinanController::class);

        // Monitoring Riwayat Surat[cite: 1]
        Route::prefix('monitoring_riwayat')->name('monitoring_riwayat.')->group(function() {
            Route::get('/', [PimpinanController::class, 'monitoringRiwayat'])->name('index');
            Route::get('/{id}', [PimpinanController::class, 'showRiwayat'])->name('show');
        });

        // Monitoring Arsip Surat[cite: 1]
        Route::prefix('monitoring_arsip')->name('monitoring_arsip.')->group(function() {
            Route::get('/', [PimpinanController::class, 'monitoringArsip'])->name('index');
            Route::get('/{id}', [PimpinanController::class, 'showArsip'])->name('show');
            Route::get('/{id}/download', [PimpinanController::class, 'downloadArsip'])->name('download');
        });

        // Monitoring Audit Log (Sesuai Use Case Pimpinan)[cite: 1]
        Route::get('/audit-log', [PimpinanController::class, 'auditLog'])->name('aktivitas.index');
        
        // Melihat Laporan Statistik[cite: 1]
        Route::get('/statistik', [PimpinanController::class, 'lihatStatistik'])->name('statistik');
    });

});