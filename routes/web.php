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
Route::middleware(['checkrole:admin,petugas,pimpinan'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Laporan & Statistik
    Route::get('/laporan', [AdminController::class, 'lihatStatistik'])->name('laporan.index');

    // Master Data (Grup ini aman karena menggunakan prefix 'master')
Route::prefix('master')->name('master.')->group(function() {
    
    // Rute untuk User (Disesuaikan agar lebih rapi dan standar)
    Route::prefix('user')->name('user.')->group(function() {
        Route::get('/', [AdminController::class, 'kelolaUser'])->name('index');
        Route::get('/create', [AdminController::class, 'createUser'])->name('create');
        Route::post('/store', [AdminController::class, 'storeUser'])->name('store');
        Route::get('/{id}', [AdminController::class, 'showUser'])->name('show');
       // Rute untuk Edit dan Update (Lebih standar)
Route::get('/{id}/edit', [AdminController::class, 'editUser'])->name('edit');
Route::put('/{id}', [AdminController::class, 'updateUser'])->name('update');
        Route::delete('/{id}/delete', [AdminController::class, 'destroyUser'])->name('destroy');
    });
        
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
  // Rute Manajemen Surat (AKSARA)
    Route::get('/manajemen_surat', [App\Http\Controllers\AdminController::class, 'indexSurat'])->name('manajemen_surat.index');
    Route::get('/manajemen_surat/create', [App\Http\Controllers\AdminController::class, 'createSurat'])->name('manajemen_surat.create');
    Route::post('/manajemen_surat', [App\Http\Controllers\AdminController::class, 'storeSurat'])->name('manajemen_surat.store');
    Route::get('/manajemen_surat/{id}', [App\Http\Controllers\AdminController::class, 'showSurat'])->name('manajemen_surat.show');
    Route::get('/manajemen_surat/{id}/edit', [App\Http\Controllers\AdminController::class, 'editSurat'])->name('manajemen_surat.edit');
    Route::put('/manajemen_surat/{id}', [App\Http\Controllers\AdminController::class, 'updateSurat'])->name('manajemen_surat.update');
    Route::delete('/manajemen_surat/{id}', [App\Http\Controllers\AdminController::class, 'destroySurat'])->name('manajemen_surat.destroy');
   Route::get('/manajemen_arsip', [AdminController::class, 'arsipIndex'])->name('manajemen_arsip.index');
    Route::get('/manajemen_arsip/create', [AdminController::class, 'arsipCreate'])->name('manajemen_arsip.create');
    Route::post('/manajemen_arsip/store', [AdminController::class, 'arsipStore'])->name('manajemen_arsip.store');
    Route::get('/manajemen_arsip/{id}', [AdminController::class, 'arsipShow'])->name('manajemen_arsip.show');
    Route::get('/manajemen_arsip/{id}/edit', [AdminController::class, 'arsipEdit'])->name('manajemen_arsip.edit');
    Route::put('/manajemen_arsip/{id}/update', [AdminController::class, 'arsipUpdate'])->name('manajemen_arsip.update');
    Route::delete('/manajemen_arsip/{id}/delete', [AdminController::class, 'arsipDestroy'])->name('manajemen_arsip.destroy');
// Di dalam route group admin:
Route::patch('/manajemen_surat/{id}/teruskan', [AdminController::class, 'teruskanKePimpinan'])
     ->name('manajemen_surat.teruskan');
    Route::get('/aktivitas', [AdminController::class, 'auditLog'])->name('aktivitas.index');
    Route::get('/statistik', [AdminController::class, 'lihatStatistik'])->name('statistik');
});

  // ==========================================
    // 2. AKTOR: PETUGAS (Sempurna & Aktif - Pertahankan Total)
    // ==========================================
    Route::middleware(['checkrole:petugas,admin,pimpinan'])->prefix('petugas')->name('petugas.')->group(function () {
        
    // Dashboard (Statistik)
    Route::get('/dashboard', [PetugasController::class, 'dashboard'])->name('dashboard');

    // PERBAIKAN: Diselaraskan nama route ke 'teruskan_pimpinan' 
    // dan method ke 'PATCH' agar sesuai dengan form di view
    Route::patch('/manajemen_surat/{id}/teruskan', [PetugasController::class, 'teruskanKePimpinan'])
         ->name('teruskan_pimpinan');

    // Manajemen Surat Resource
    Route::resource('manajemen_surat', PetugasController::class);
    
    
    // Route Tambahan untuk Status Surat
    Route::get('/manajemen_surat_status', [PetugasController::class, 'statusSurat'])->name('manajemen_surat.status');

    // --- MANAJEMEN ARSIP ---
    Route::get('/manajemen_arsip', [PetugasController::class, 'kelolaArsip'])->name('manajemen_arsip.index');
    Route::get('/manajemen_arsip/create', [PetugasController::class, 'arsipCreate'])->name('manajemen_arsip.create');
    Route::post('/manajemen_arsip/store', [PetugasController::class, 'arsipStore'])->name('manajemen_arsip.store');
    
    Route::get('/manajemen_arsip/{id}', [PetugasController::class, 'arsip_show'])->name('manajemen_arsip.show');
    Route::get('/manajemen_arsip/{id}/edit', [PetugasController::class, 'arsipEdit'])->name('manajemen_arsip.edit');
    Route::put('/manajemen_arsip/{id}/update', [PetugasController::class, 'arsipUpdate'])->name('manajemen_arsip.update');
    Route::delete('/manajemen_arsip/{id}/delete', [PetugasController::class, 'arsipDestroy'])->name('manajemen_arsip.destroy');

    // --- ROUTE EXPORT DATA (Baru Ditambahkan) ---
    Route::get('/export/excel', [PetugasController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [PetugasController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/csv', [PetugasController::class, 'exportCsv'])->name('export.csv');

    // Statistik (Alias ke dashboard)
    Route::get('/statistik', [PetugasController::class, 'dashboard'])->name('statistik');
});
// ==========================================
    // 3. AKTOR: PIMPINAN (Sempurna & Aktif - Pertahankan Total)
    // ==========================================
    Route::middleware(['checkrole:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('dashboard');

       // Manajemen Surat
        Route::prefix('manajemen_surat')->name('manajemen_surat.')->group(function() {
            Route::get('/', [PimpinanController::class, 'indexManajemenSurat'])->name('index');
            
            // LETAKKAN RUTE SPESIFIK DI ATAS SEBELUM RUTE /{id} UMUM
            Route::get('/riwayat/{id}', [PimpinanController::class, 'showRiwayat'])->name('riwayat');
            Route::delete('/riwayat/{id}', [PimpinanController::class, 'hapusRiwayat'])->name('destroy_riwayat');

            // Route akses dokumen aman & posisikan show umum di paling bawah
            Route::get('/dokumen/{id}', [PimpinanController::class, 'tampilkanDokumen'])->name('tampilkan_dokumen');
            Route::post('/disposisi/store', [PimpinanController::class, 'simpanDisposisi'])->name('simpan_disposisi');
            
            Route::get('/{id}', [PimpinanController::class, 'showManajemenSurat'])->name('show');
        });

        // Monitoring Arsip Surat
        Route::prefix('monitoring_arsip')->name('monitoring_arsip.')->group(function() {
            Route::get('/', [PimpinanController::class, 'monitoringArsip'])->name('index');
            Route::get('/{id}', [PimpinanController::class, 'showArsip'])->name('show');
            Route::get('/{id}/download', [PimpinanController::class, 'downloadArsip'])->name('download');
        });

        // Monitoring Audit Log (Pastikan method auditLog ada di Controller)
        Route::get('/audit-log', [PimpinanController::class, 'auditLog'])->name('aktivitas.index');
        
        // Melihat Laporan Statistik
        Route::get('/statistik', [PimpinanController::class, 'laporan'])->name('statistik');
    });
});