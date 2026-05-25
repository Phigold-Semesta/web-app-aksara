<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\KategoriSurat;
use App\Models\InstruksiDisposisi;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function index()
    {
        $totalPengguna = User::count();
        $totalArsip = 1402; // Sesuai data dummy aman atau hitungan model arsip Anda
        $totalKategori = KategoriSurat::count();
        
        // Mengambil 5 aktivitas log terbaru untuk dashboard
        $recentLogs = AuditLog::with('user')->latest('waktu_kejadian')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPengguna', 
            'totalArsip', 
            'totalKategori', 
            'recentLogs'
        ));
    }

    /**
     * Laporan & Statistik
     */
    public function lihatStatistik()
    {
        return view('admin.laporan.index');
    }

    /**
     * Kelola User + Contoh Pencatatan Audit Log di Controller jika ada Request POST/PUT/DELETE
     */
    public function kelolaUser(Request $request)
    {
        // JIKA ada proses simpan user baru (POST)
        if ($request->isMethod('post')) {
            $user = User::create($request->all());

            // PERBAIKAN: Tembak Audit Log menggunakan Interpolasi String yang Valid (Bebas Error)
            AuditLog::create([
                'aktivitas' => 'TAMBAH USER',
                'deskripsi' => auth()->user()->nama_lengkap . " membuat pengguna baru: {$user->nama_lengkap}",
                'ip_address' => $request->ip(),
                'waktu_kejadian' => now(),
                'id_user' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'User berhasil ditambahkan');
        }

        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Master Kategori Surat + Pencatatan Audit Log saat Tambah Data
     */
    public function masterKategori(Request $request)
    {
        // JIKA ada proses simpan kategori baru (POST)
        if ($request->isMethod('post')) {
            $kategoriBaru = KategoriSurat::create($request->all());

            // PERBAIKAN: Tembak Audit Log menggunakan Interpolasi String yang Valid (Bebas Error)
            AuditLog::create([
                'aktivitas' => 'MASTER KATEGORI',
                'deskripsi' => auth()->user()->nama_lengkap . " menambahkan kategori surat baru: {$kategoriBaru->nama_kategori}",
                'ip_address' => $request->ip(),
                'waktu_kejadian' => now(),
                'id_user' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
        }

        $kategori = KategoriSurat::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Master Instruksi Disposisi + Pencatatan Audit Log saat Tambah Data
     */
    public function masterInstruksi(Request $request)
    {
        // JIKA ada proses simpan instruksi baru (POST)
        if ($request->isMethod('post')) {
            $instruksiBaru = InstruksiDisposisi::create($request->all());

            // PERBAIKAN: Tembak Audit Log menggunakan Interpolasi String yang Valid (Bebas Error)
            AuditLog::create([
                'aktivitas' => 'MASTER INSTRUKSI',
                'deskripsi' => auth()->user()->nama_lengkap . " menambah instruksi disposisi baru: {$instruksiBaru->nama_instruksi}",
                'ip_address' => $request->ip(),
                'waktu_kejadian' => now(),
                'id_user' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Instruksi berhasil ditambahkan');
        }

        $instruksi = InstruksiDisposisi::all();
        return view('admin.instruksi.index', compact('instruksi'));
    }

    /**
     * Halaman Monitoring Seluruh Audit Log (Dinamis & Mengarah ke admin/aktivitas/index)
     */
    public function auditLog()
    {
        // Mengambil log terbaru diurutkan berdasarkan kolom waktu_kejadian asli dari phpMyAdmin
        $logs = AuditLog::with('user')->latest('waktu_kejadian')->get();
        
        return view('admin.aktivitas.index', compact('logs'));
    }

    /**
     * Input Surat + Log
     */
    public function inputSurat(Request $request) 
    { 
        if ($request->isMethod('post')) {
            // ... (Proses simpan data surat milikmu sebelumnya) ...

            // Tembak Audit Log langsung dari Controller
            AuditLog::create([
                'aktivitas' => 'INPUT SURAT',
                'deskripsi' => auth()->user()->nama_lengkap . ' melakukan input surat masuk/keluar baru.',
                'ip_address' => $request->ip(),
                'waktu_kejadian' => now(),
                'id_user' => auth()->id()
            ]);
        }

        return view('admin.surat.create'); 
    }

    /**
     * Kelola Arsip + Log
     */
    public function kelolaArsip(Request $request) 
    { 
        if ($request->isMethod('post')) {
            // ... (Proses simpan data arsip milikmu sebelumnya) ...

            // Tembak Audit Log langsung dari Controller
            AuditLog::create([
                'aktivitas' => 'MANAJEMEN ARSIP',
                'deskripsi' => auth()->user()->nama_lengkap . ' membuat dokumen arsip baru ke dalam sistem.',
                'ip_address' => $request->ip(),
                'waktu_kejadian' => now(),
                'id_user' => auth()->id()
            ]);
        }

        return view('admin.arsip.index'); 
    }
}