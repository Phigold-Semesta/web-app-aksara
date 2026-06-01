<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\KategoriSurat;
use App\Models\InstruksiDisposisi;
use App\Models\Surat; // Pastikan model Surat sudah dibuat dan diimpor dengan benar
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
     * =========================================================================
     * PERBAIKAN & REFACTOR: MASTER KATEGORI SURAT (ALUR CRUD HALAMAN PENUH / FULL PAGE)
     * =========================================================================
     */

    /**
     * 1. INDEX: Menampilkan daftar seluruh kategori surat
     */
    public function masterKategori(Request $request)
    {
        $kategori = KategoriSurat::all();
        return view('admin.master.kategori.index', compact('kategori'));
    }

    /**
     * 2. CREATE: Menampilkan halaman form tambah kategori baru
     */
    public function createKategori()
    {
        return view('admin.master.kategori.create');
    }

    /**
     * Kode ini disempurnakan agar tidak lagi error SQL 1364.
     */
   // FUNGSI STORE KATEGORI
public function storeKategori(Request $request)
{
    $request->validate([
        'kode_kategori' => 'required|string|max:50|unique:kategori_surat,kode_kategori',
        'nama_kategori' => 'required|string|max:255',
        'keterangan'    => 'nullable|string'
    ]);

    // Simpan data dari request
    $kategoriBaru = KategoriSurat::create([
        'kode_kategori' => $request->kode_kategori,
        'nama_kategori' => $request->nama_kategori,
        'keterangan'    => $request->keterangan
    ]);

    // Pencatatan Audit Log
    AuditLog::create([
        'aktivitas' => 'MASTER KATEGORI',
        'deskripsi' => auth()->user()->nama_lengkap . " menambahkan kategori surat baru: {$kategoriBaru->nama_kategori} (Kode: {$kategoriBaru->kode_kategori})",
        'ip_address' => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user' => auth()->id()
    ]);

    return redirect()->route('admin.master.kategori.index')->with('success', 'Kategori surat berhasil ditambahkan!');
}
    /**
     * 4. EDIT: Menampilkan halaman form edit kategori berdasarkan ID
     */
    public function editKategori($id)
    {
        $kategori = KategoriSurat::findOrFail($id);
        return view('admin.master.kategori.edit', compact('kategori'));
    }

    /**
     * 5. UPDATE: Memperbarui data kategori surat di database beserta Audit Log
     */
   /**
     * 5. UPDATE: Memperbarui data kategori surat
     * Diperbaiki untuk memastikan 'keterangan' ikut terupdate ke database.
     */
   // FUNGSI UPDATE KATEGORI
public function updateKategori(Request $request, $id)
{
    $kategori = KategoriSurat::findOrFail($id);

    $request->validate([
        'kode_kategori' => 'required|string|max:50|unique:kategori_surat,kode_kategori,' . $id . ',id_kategori',
        'nama_kategori' => 'required|string|max:255',
        'keterangan'    => 'nullable|string'
    ]);

    $kategori->update([
        'kode_kategori' => $request->kode_kategori,
        'nama_kategori' => $request->nama_kategori,
        'keterangan'    => $request->keterangan
    ]);

    // Pencatatan Audit Log
    AuditLog::create([
        'aktivitas' => 'UBAH KATEGORI',
        'deskripsi' => auth()->user()->nama_lengkap . " mengubah kategori surat: {$kategori->nama_kategori} (Kode: {$kategori->kode_kategori})",
        'ip_address' => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user' => auth()->id()
    ]);

    return redirect()->route('admin.master.kategori.index')->with('success', 'Kategori surat berhasil diperbarui!');
}
    /**
     * 6. DESTROY: Menghapus data kategori surat dari database beserta Audit Log
     */
    public function destroyKategori(Request $request, $id)
    {
        $kategori = KategoriSurat::findOrFail($id);
        $namaKategori = $kategori->nama_kategori;

        $kategori->delete();

        // Pencatatan Audit Log otomatis
        AuditLog::create([
            'aktivitas' => 'HAPUS KATEGORI',
            'deskripsi' => auth()->user()->nama_lengkap . " menghapus kategori surat: {$namaKategori}",
            'ip_address' => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user' => auth()->id()
        ]);

        return redirect()->route('admin.master.kategori.index')->with('success', 'Kategori surat berhasil dihapus dari sistem!');
    }

  /**
     * Menampilkan daftar instruksi
     */
    public function masterInstruksi()
    {
        $instruksi = InstruksiDisposisi::all();
        return view('admin.master.instruksi.index', compact('instruksi'));
    }

    /**
     * Menampilkan form tambah instruksi
     */
    public function createInstruksi()
    {
        return view('admin.master.instruksi.create');
    }

    /**
     * Menyimpan data instruksi baru
     */
    public function storeInstruksi(Request $request)
    {
        $request->validate(['nama_instruksi' => 'required|string|max:255']);
        
        $instruksiBaru = InstruksiDisposisi::create($request->all());

        AuditLog::create([
            'aktivitas' => 'MASTER INSTRUKSI',
            'deskripsi' => auth()->user()->nama_lengkap . " menambah instruksi disposisi baru: {$instruksiBaru->nama_instruksi}",
            'ip_address' => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user' => auth()->id()
        ]);

        return redirect()->route('admin.master.instruksi.index')->with('success', 'Instruksi berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit instruksi
     */
    public function editInstruksi($id)
    {
        $instruksi = InstruksiDisposisi::findOrFail($id);
        return view('admin.master.instruksi.edit', compact('instruksi'));
    }

    /**
     * Mengupdate data instruksi
     */
    public function updateInstruksi(Request $request, $id)
    {
        $request->validate(['nama_instruksi' => 'required|string|max:255']);
        
        $instruksi = InstruksiDisposisi::findOrFail($id);
        $instruksi->update($request->all());

        AuditLog::create([
            'aktivitas' => 'UPDATE INSTRUKSI',
            'deskripsi' => auth()->user()->nama_lengkap . " mengubah instruksi menjadi: {$instruksi->nama_instruksi}",
            'ip_address' => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user' => auth()->id()
        ]);

        return redirect()->route('admin.master.instruksi.index')->with('success', 'Instruksi berhasil diperbarui');
    }

    /**
     * Menghapus data instruksi
     */
    public function destroyInstruksi($id)
    {
        $instruksi = InstruksiDisposisi::findOrFail($id);
        $nama = $instruksi->nama_instruksi;
        $instruksi->delete();

        AuditLog::create([
            'aktivitas' => 'HAPUS INSTRUKSI',
            'deskripsi' => auth()->user()->nama_lengkap . " menghapus instruksi: {$nama}",
            'ip_address' => request()->ip(),
            'waktu_kejadian' => now(),
            'id_user' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Instruksi berhasil dihapus');
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
 * 1. INDEX: Menampilkan daftar surat
 */
public function indexSurat() 
{
    $surat = Surat::latest()->paginate(10);
    return view('admin.manajemen_surat.index', compact('surat'));
}

/**
 * Menampilkan Form Tambah Surat
 */
public function createSurat() 
{ 
    // Mengambil data kategori untuk dropdown form
    $kategoris = KategoriSurat::all();
    return view('admin.manajemen_surat.create', compact('kategoris')); 
}

/**
 * 2. STORE: Menyimpan data surat baru
 */
public function storeSurat(Request $request) 
{
    $request->validate([
        'nomor_surat' => 'required',
        'perihal' => 'required',
        'id_kategori' => 'required'
    ]);

    $surat = Surat::create($request->all());

    AuditLog::create([
        'aktivitas' => 'INPUT SURAT',
        'deskripsi' => auth()->user()->nama_lengkap . " melakukan input surat baru: {$surat->nomor_surat}",
        'ip_address' => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user' => auth()->id()
    ]);

    return redirect()->route('admin.manajemen_surat.index')->with('success', 'Surat berhasil ditambah!');
}

/**
 * 3. EDIT: Menampilkan form edit
 */
public function editSurat($id) 
{
    $surat = Surat::findOrFail($id);
    $kategoris = KategoriSurat::all();
    return view('admin.manajemen_surat.edit', compact('surat', 'kategoris'));
}

/**
 * 4. UPDATE: Memproses update
 */
public function updateSurat(Request $request, $id) 
{
    $surat = Surat::findOrFail($id);
    $surat->update($request->all());

    AuditLog::create([
        'aktivitas' => 'UPDATE SURAT',
        'deskripsi' => auth()->user()->nama_lengkap . " memperbarui surat: {$surat->nomor_surat}",
        'ip_address' => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user' => auth()->id()
    ]);

    return redirect()->route('admin.manajemen_surat.index')->with('success', 'Surat berhasil diperbarui!');
}

/**
 * 5. DESTROY: Menghapus surat
 */
public function destroySurat($id) 
{
    $surat = Surat::findOrFail($id);
    $nomor = $surat->nomor_surat;
    $surat->delete();

    AuditLog::create([
        'aktivitas' => 'HAPUS SURAT',
        'deskripsi' => auth()->user()->nama_lengkap . " menghapus surat: {$nomor}",
        'ip_address' => request()->ip(),
        'waktu_kejadian' => now(),
        'id_user' => auth()->id()
    ]);

    return redirect()->route('admin.manajemen_surat.index')->with('success', 'Surat berhasil dihapus!');
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