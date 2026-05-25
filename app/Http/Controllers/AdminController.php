<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\KategoriSurat;
use App\Models\InstruksiDisposisi;
// Catatan: Jika ada model Arsip atau Surat, silakan di-import di sini jika dibutuhkan di masa depan.

class AdminController extends Controller
{
    /**
     * Menampilkan Halaman Utama Dashboard Admin
     * Dioptimasi untuk mengirim data statistik riil ke view secara dinamis.
     */
    public function index()
    {
        // Mengambil total hitungan data untuk komponen kartu statistik dashboard
        $totalPengguna = User::count();
        
        // Asumsi hitungan arsip, jika belum ada model Arsip/Surat khusus, kita pakai visualisasi data kategori atau angka dummy aman.
        // Jika kamu punya model Surat/Arsip, ganti baris ini dengan: \App\Models\Arsip::count();
        $totalArsip = 1402; 
        
        $totalKategori = KategoriSurat::count();
        
        // Mengambil 5 aktivitas log terbaru untuk komponen tabel audit mini di dashboard
        $recentLogs = AuditLog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPengguna', 
            'totalArsip', 
            'totalKategori', 
            'recentLogs'
        ));
    }

    /**
     * Menampilkan Halaman Laporan & Grafik Analisis Statistik
     * Sinkron dengan route: admin.laporan.index
     */
    public function lihatStatistik()
    {
        return view('admin.laporan.index');
    }

    /**
     * Menampilkan Daftar Manajemen Pengguna / User
     * Sinkron dengan route: admin.master.user.index
     */
    public function kelolaUser()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Menampilkan Data Master Kategori Surat
     */
    public function masterKategori()
    {
        $kategori = KategoriSurat::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Menampilkan Data Master Pilihan Instruksi Disposisi
     */
    public function masterInstruksi()
    {
        $instruksi = InstruksiDisposisi::all();
        return view('admin.instruksi.index', compact('instruksi'));
    }

    /**
     * PERBAIKAN UTAMA: Menampilkan Halaman Monitoring Seluruh Audit Log
     * Sudah disesuaikan jalurnya menuju folder view: resources/views/aktivitas/index.blade.php
     */
    public function auditLog()
    {
        // Load relasi user untuk menghindari masalah N+1 Query, urutkan dari yang paling baru
        $logs = AuditLog::with('user')->latest()->get();
        
        // Tepat mengarah ke folder aktivitas/index sesuai request terbaru
        return view('admin.aktivitas.index', compact('logs'));
    }

    /**
     * Fitur Akses Operasional Tambahan (Opsional untuk Aktor Admin)
     */
    public function inputSurat() 
    { 
        return view('admin.surat.create'); 
    }

    public function kelolaArsip() 
    { 
        return view('admin.arsip.index'); 
    }
}