<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\KategoriSurat;
use App\Models\InstruksiDisposisi;
// Catatan: Pastikan Anda mengimpor model Surat jika sudah ada (misal: use App\Models\Surat;)

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
     * Laporan & Statistik - PENYEMPURNAAN: Diaktifkan dengan agregasi data nyata untuk Chart.js
     */
    public function lihatStatistik()
    {
        // Mengambil hitungan nyata untuk operan ke view laporan
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalPimpinan = User::where('role', 'pimpinan')->count();
        $kategoriList = KategoriSurat::withCount([])->get(); // Siap dikembangkan jika ada relasi surat
        
        // Mengirim data penunjang agar halaman statistik bekerja dinamis
        return view('admin.laporan.index', compact(
            'totalPetugas',
            'totalPimpinan',
            'kategoriList'
        ));
    }

    /**
     * Kelola User (Read) - PERBAIKAN: Menggunakan paginate() agar sinkron dengan template Blade
     */
    public function kelolaUser(Request $request)
    {
        // Mengubah User::all() menjadi paginate(10) agar fitur halaman aktif dan tidak memicu Error 500
        $users = User::latest()->paginate(10);
        return view('admin.master.user.index', compact('users'));
    }

    /**
     * PERBAIKAN: Menampilkan Halaman Form Tambah Pengguna Baru (Create Page)
     */
    public function createUser()
    {
        return view('admin.master.user.create');
    }

    /**
     * Simpan Pengguna Baru (Create Store) - Integrasi Manajemen User Baru Berpindah Halaman
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'nama_lengkap' => 'required',
            'role' => 'required'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'role' => $request->role,
        ]);

        // Tembak Audit Log
        AuditLog::create([
            'aktivitas' => 'TAMBAH USER',
            'deskripsi' => auth()->user()->nama_lengkap . " membuat pengguna baru dengan nama: {$user->nama_lengkap} (Role: {$user->role})",
            'ip_address' => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user' => auth()->id()
        ]);

        // Disempurnakan ke rute index master user
        return redirect()->route('admin.master.user.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    /**
     * PERBAIKAN: Menampilkan Halaman Detail Pengguna (Show Page)
     */
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.master.user.show', compact('user'));
    }

    /**
     * PERBAIKAN: Menampilkan Halaman Form Edit Pengguna (Edit Page)
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.master.user.edit', compact('user'));
    }

    /**
     * Perbarui Data Pengguna (Update) - Integrasi Manajemen User Baru Berpindah Halaman
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $id,
            'nama_lengkap' => 'required',
            'role' => 'required'
        ]);

        $user->username = $request->username;
        $user->nama_lengkap = $request->nama_lengkap;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Tembak Audit Log
        AuditLog::create([
            'aktivitas' => 'UBAH USER',
            'deskripsi' => auth()->user()->nama_lengkap . " mengubah data pengguna: {$user->nama_lengkap}",
            'ip_address' => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user' => auth()->id()
        ]);

        // Disempurnakan ke rute index master user
        return redirect()->route('admin.master.user.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Hapus Pengguna (Delete) - Integrasi Manajemen User Baru
     */
    public function destroyUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $namaUser = $user->nama_lengkap;
        
        $user->delete();

        // Tembak Audit Log
        AuditLog::create([
            'aktivitas' => 'HAPUS USER',
            'deskripsi' => auth()->user()->nama_lengkap . " menghapus pengguna bernama: {$namaUser}",
            'ip_address' => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user' => auth()->id()
        ]);

        return redirect()->route('admin.master.user.index')->with('success', 'Pengguna berhasil deleted dari sistem!');
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

            // PERBAIKAN TOTAL: String digabung utuh, ID menggunakan helper auth()->id() yang valid
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