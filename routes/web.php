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
        // Menggunakan Facade Auth untuk akses yang lebih aman dan terdeteksi IDE
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Memastikan user ada sebelum mengakses properti 'role'
        if ($user) {
            return redirect()->route($user->role . '.dashboard');
        }
        
        // Jika terjadi error sesi, arahkan kembali ke login
        return redirect()->route('login');
    })->name('dashboard');

    // ==========================================
// 1. AKTOR: ADMINISTRATOR (DISEMPURNAKAN & SINKRON)
// ==========================================
// ==========================================
// 1. AKTOR: ADMINISTRATOR (DISEMPURNAKAN)
// ==========================================
Route::middleware(['checkrole:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Laporan & Statistik
    Route::get('/laporan', [AdminController::class, 'lihatStatistik'])->name('laporan.index');

    // Master Data (Grup ini aman karena menggunakan prefix 'master')
    Route::prefix('master')->name('master.')->group(function() {
        Route::get('/user', [AdminController::class, 'kelolaUser'])->name('user.index');
        Route::get('/user/create', [AdminController::class, 'createUser'])->name('user.create');
        Route::post('/user/store', [AdminController::class, 'storeUser'])->name('user.store');
        Route::get('/user/show/{id}', [AdminController::class, 'showUser'])->name('user.show');
        Route::get('/user/edit/{id}', [AdminController::class, 'editUser'])->name('user.edit');
        Route::put('/user/update/{id}', [AdminController::class, 'updateUser'])->name('user.update');
        Route::delete('/user/delete/{id}', [AdminController::class, 'destroyUser'])->name('user.destroy');
        
        Route::get('/kategori', [AdminController::class, 'masterKategori'])->name('kategori.index');
        Route::get('/kategori/create', [AdminController::class, 'createKategori'])->name('kategori.create');
        Route::post('/kategori/store', [AdminController::class, 'storeKategori'])->name('kategori.store');
        Route::get('/kategori/edit/{id}', [AdminController::class, 'editKategori'])->name('kategori.edit');
        Route::put('/kategori/update/{id}', [AdminController::class, 'updateKategori'])->name('kategori.update');
        Route::delete('/kategori/delete/{id}', [AdminController::class, 'destroyKategori'])->name('kategori.destroy');
        
        Route::get('/instruksi', [AdminController::class, 'masterInstruksi'])->name('instruksi.index');
        Route::get('/instruksi/create', [AdminController::class, 'createInstruksi'])->name('instruksi.create');
        Route::post('/instruksi/store', [AdminController::class, 'storeInstruksi'])->name('instruksi.store');
        Route::get('/instruksi/edit/{id}', [AdminController::class, 'editInstruksi'])->name('instruksi.edit');
        Route::put('/instruksi/update/{id}', [AdminController::class, 'updateInstruksi'])->name('instruksi.update');
        Route::delete('/instruksi/delete/{id}', [AdminController::class, 'destroyInstruksi'])->name('instruksi.destroy');
    });

    // Rute Penting (Pastikan ini berada di level yang sama dengan Dashboard, TIDAK dibungkus prefix lagi)
    Route::get('/manajemen_surat', [AdminController::class, 'inputSurat'])->name('manajemen_surat.index');
    Route::get('/manajemen_arsip', [AdminController::class, 'kelolaArsip'])->name('manajemen_arsip.index');
    Route::get('/aktivitas', [AdminController::class, 'auditLog'])->name('aktivitas.index');
    Route::get('/statistik', [AdminController::class, 'lihatStatistik'])->name('statistik');
});

    // ==========================================
    // 2. AKTOR: PETUGAS (Sempurna & Aktif - Pertahankan Total)
    // ==========================================
    Route::middleware(['checkrole:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        
        // Dashboard (Statistik)
        Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');

        // Fitur Teruskan Surat ke Pimpinan
        Route::patch('/manajemen_surat/{id}/teruskan', [PetugasController::class, 'teruskanKePimpinan'])
             ->name('teruskan_pimpinan');

        // Manajemen Surat Resource
        Route::resource('manajemen_surat', PetugasController::class);
        
        // Route Tambahan untuk Status Surat
        Route::get('/manajemen_surat_status', [PetugasController::class, 'statusSurat'])->name('manajemen_surat.status');

        // --- MANAJEMEN ARSIP ---
        // Urutan diperbaiki: Static Route dulu, baru Parameter Route {id}
        Route::get('/manajemen_arsip', [PetugasController::class, 'kelolaArsip'])->name('manajemen_arsip.index');
        Route::get('/manajemen_arsip/create', [PetugasController::class, 'arsipCreate'])->name('manajemen_arsip.create');
        Route::post('/manajemen_arsip/store', [PetugasController::class, 'arsipStore'])->name('manajemen_arsip.store');
        
        // Route dengan parameter diletakkan di bawah agar tidak bentrok
        Route::get('/manajemen_arsip/{id}', [PetugasController::class, 'arsip_show'])->name('manajemen_arsip.show');
        Route::get('/manajemen_arsip/{id}/edit', [PetugasController::class, 'arsipEdit'])->name('manajemen_arsip.edit');
        Route::put('/manajemen_arsip/{id}/update', [PetugasController::class, 'arsipUpdate'])->name('manajemen_arsip.update');
        Route::delete('/manajemen_arsip/{id}/delete', [PetugasController::class, 'arsipDestroy'])->name('manajemen_arsip.destroy');

        // Statistik (Alias ke dashboard)
        Route::get('/statistik', [PetugasController::class, 'dashboard'])->name('statistik');
    });

    // ==========================================
    // 3. AKTOR: PIMPINAN (Sempurna & Aktif - Pertahankan Total)
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