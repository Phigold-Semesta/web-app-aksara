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
            Route::resource('user', AdminController::class); 
            Route::resource('kategori', AdminController::class);
            Route::resource('instruksi', AdminController::class);
        });

        // Manajemen Surat (Input & Digitalisasi)[cite: 1]
        Route::resource('manajemen_surat', AdminController::class);

        // Manajemen Arsip[cite: 1]
        Route::resource('manajemen_arsip', AdminController::class);

        // Monitoring Audit Log[cite: 1]
        Route::get('/aktivitas', [AdminController::class, 'auditLog'])->name('aktivitas.index');
        
        // Statistik Tambahan
        Route::get('/statistik', [AdminController::class, 'lihatStatistik'])->name('statistik');
    });

    // ==========================================
    // 2. AKTOR: PETUGAS (Sempurna & Aktif)
    // ==========================================
    Route::middleware(['checkrole:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        
        // Dashboard & Laporan (Sesuai image_cf3a21.png)[cite: 2]
        Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');

        // Input & Digitalisasi Surat[cite: 1, 3]
        // Menggunakan resource untuk index, create, store, edit, update, destroy
        Route::resource('manajemen_surat', PetugasController::class)->names([
            'index' => 'manajemen_surat.index',
            'create' => 'manajemen_surat.create',
            'store' => 'manajemen_surat.store',
        ]);
        
        // Route Tambahan untuk Status Surat (Jika ingin halaman terpisah dari index)
        Route::get('/manajemen_surat/status', [PetugasController::class, 'statusSurat'])->name('manajemen_surat.status');

        // Meneruskan Surat Ke Pimpinan (Workflow Utama)[cite: 1, 3]
        // Diubah menjadi POST/PATCH untuk keamanan data
        Route::patch('/surat/{id}/teruskan', [PetugasController::class, 'teruskanKePimpinan'])->name('surat.teruskan');

        // Manajemen Arsip (Input Lokasi & Cek Retensi)[cite: 1, 3]
        Route::get('/manajemen_arsip', [PetugasController::class, 'kelolaArsip'])->name('manajemen_arsip.index');
        Route::resource('arsip', PetugasController::class)->except(['index']); // Untuk operasional arsip lainnya

        // Melihat Laporan Statistik[cite: 1]
        // Dialihkan ke dashboard jika data statistik sudah ada di dashboard
        Route::get('/statistik', [PetugasController::class, 'index'])->name('statistik');
    });

    // ==========================================
    // 3. AKTOR: PIMPINAN
    // ==========================================
    Route::middleware(['checkrole:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        
        // Dashboard[cite: 2]
        Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');

        // Menerima & Meninjau Surat / Instruksi[cite: 1]
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

        // Monitoring Audit Log[cite: 1]
        Route::get('/audit-log', [PimpinanController::class, 'auditLog'])->name('aktivitas.index');
        
        // Melihat Laporan Statistik[cite: 1]
        Route::get('/statistik', [PimpinanController::class, 'lihatStatistik'])->name('statistik');
    });

});