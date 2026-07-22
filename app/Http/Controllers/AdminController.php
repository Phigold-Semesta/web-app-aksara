<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\KategoriSurat;
use App\Models\InstruksiDisposisi;
use App\Models\Arsip;
// Catatan: Pastikan Anda mengimpor model Surat jika sudah ada (misal: use App\Models\Surat;)

class AdminController extends Controller
{
  public function index()
    {
        // Langsung arahkan ke fungsi dashboard
        return $this->dashboard();
    }

    /**
     * Logika utama dashboard
     */
    public function dashboard()
    {
        $totalPengguna = User::count();
        $totalArsip = Arsip::count();
        $totalKategori = KategoriSurat::count();
        $logs = AuditLog::latest()->take(5)->get();

        // Data untuk grafik
        $labels = json_encode(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']);
        $dataArsip = json_encode([90, 130, 110, 160, 145, 190]);
        $dataSurat = json_encode([120, 185, 140, 210, 195, 245]);

        return view('admin.dashboard', compact(
            'totalPengguna', 
            'totalArsip', 
            'totalKategori', 
            'logs', 
            'labels', 
            'dataArsip', 
            'dataSurat'
        ));
    }

    /**
     * Laporan & Statistik - PENYEMPURNAAN: Diaktifkan dengan agregasi data nyata untuk Chart.js
     */
   
public function lihatStatistik()
    {
        // 1. Data Card: Menggunakan relasi kategori (Meniru PetugasController yang akurat 100%)
        $totalSuratMasuk = \App\Models\Surat::whereHas('kategori', function($q) {
            $q->where('nama_kategori', 'LIKE', '%Surat Masuk%');
        })->count();

        $totalSuratKeluar = \App\Models\Surat::whereHas('kategori', function($q) {
            $q->where('nama_kategori', 'LIKE', '%Surat Keluar%');
        })->count();

        // Untuk total disposisi tetap melacak status surat atau instruksi disposisi
        $totalDisposisi = \App\Models\Surat::where('status', 'LIKE', '%disposisi%')->count();

        // 2. Data Surat untuk Tabel
        $surats = \App\Models\Surat::with('kategori')->latest()->take(10)->get();

        // 3. Data Kategori
        $kategoriList = \App\Models\KategoriSurat::all();

        // ============================================================
        // 4. PENYEMPURNAAN: GENERATE DATA ARRAY UNTUK GRAFIK VIEW
        // ============================================================
        
        // A. Data untuk Grafik Donut (Hitung jumlah surat per masing-masing kategori berdasarkan relasi)
        $dataKategoriCounts = [];
        foreach ($kategoriList as $kategori) {
            $dataKategoriCounts[] = \App\Models\Surat::where('id_kategori', $kategori->id_kategori)->count();
        }

        // B. Data untuk Grafik Garis (Tren Sirkulasi 5 Bulan Terakhir)
        $labelsBulan = [];
        $dataSuratMasukChart = [];
        $dataSuratKeluarChart = [];

        for ($i = 4; $i >= 0; $i--) {
            $bulan = \Carbon\Carbon::now()->subMonths($i);
            $labelsBulan[] = $bulan->translatedFormat('M'); // Contoh: Jan, Feb, Mar

            // Hitung total surat masuk per bulan berdasarkan kategori
            $dataSuratMasukChart[] = \App\Models\Surat::whereHas('kategori', function($q) {
                $q->where('nama_kategori', 'LIKE', '%Surat Masuk%');
            })
            ->whereMonth('created_at', $bulan->month)
            ->whereYear('created_at', $bulan->year)
            ->count();

            // Hitung total surat keluar per bulan berdasarkan kategori
            $dataSuratKeluarChart[] = \App\Models\Surat::whereHas('kategori', function($q) {
                $q->where('nama_kategori', 'LIKE', '%Surat Keluar%');
            })
            ->whereMonth('created_at', $bulan->month)
            ->whereYear('created_at', $bulan->year)
            ->count();
        }

        // Penyelarasan variabel array grafik
        $dataSuratMasuk = $dataSuratMasukChart;
        $dataSuratKeluar = $dataSuratKeluarChart;

        // Pastikan mengembalikan view yang benar beserta data grafiknya
        return view('admin.laporan.index', compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalDisposisi',
            'surats',
            'kategoriList',
            'labelsBulan',
            'dataSuratMasuk',
            'dataSuratKeluar',
            'dataKategoriCounts'
        ));
    }
    
    /**
     * Kelola User (Read) - PERBAIKAN: Menggunakan paginate() agar sinkron dengan template Blade
     */
 
/**
 * Menampilkan Daftar Pengguna dengan fitur Search, Filter, dan Pagination Dinamis
 */
public function kelolaUser(Request $request)
{
    // Mulai query dari model User
    $query = User::query();

    // 1. Logika Searching
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
              ->orWhere('username', 'like', '%' . $request->search . '%');
        });
    }

    // 2. Logika Filtering Role
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // 3. Logika Filtering Per Halaman
    // Jika 'all', kita ambil total count, jika tidak maka gunakan nilai input atau default 10
    $perPage = $request->per_page;
    
    if ($perPage === 'all') {
        $users = $query->latest()->get(); // Ambil semua data tanpa pagination
    } else {
        $perPage = in_array($perPage, ['5', '10', '25']) ? $perPage : 10;
        $users = $query->latest()->paginate((int)$perPage)->withQueryString();
    }

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
 * Simpan Pengguna Baru (Create Store) - Diperbarui untuk kestabilan
 */
public function storeUser(Request $request)
{
    // 1. Validasi: Menambahkan validasi untuk jabatan agar sinkron dengan database
    $request->validate([
        'username'     => 'required|string|max:255|unique:user,username',
        'password'     => 'required|string|min:6',
        'nama_lengkap' => 'required|string|max:255',
        'role'         => 'required|in:admin,petugas,pimpinan',
        'jabatan'      => 'nullable|string|max:100' // Ditambahkan karena kolom di DB bersifat wajib
    ]);

    // 2. Gunakan DB Transaction untuk memastikan integritas data
    return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
        
        // Simpan User dengan menyertakan jabatan
        $user = User::create([
            'username'     => $request->username,
            'password'     => bcrypt($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'role'         => $request->role,
            'jabatan'      => $request->jabatan ?? 'Staf', // Memberikan default jika kosong
        ]);

        // 3. Tembak Audit Log
        AuditLog::create([
            'aktivitas'      => 'TAMBAH USER',
            'deskripsi'      => auth()->user()->nama_lengkap . " membuat pengguna baru dengan nama: {$user->nama_lengkap} (Role: {$user->role})",
            'ip_address'     => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user'        => auth()->id()
        ]);

        return redirect()->route('admin.master.user.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    });
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
    // 1. Pastikan findOrFail mencari berdasarkan kolom yang benar jika perlu
    // Jika 'id' pada route adalah 'id_user', maka ini sudah aman.
    $user = User::findOrFail($id);

    // 2. Perbaikan Validasi Unique
    // Syntax: unique:tabel,kolom,id_yang_dikecualikan,nama_kolom_primary_key
    $request->validate([
        'username' => 'required|unique:user,username,' . $id . ',id_user',
        'nama_lengkap' => 'required',
        'role' => 'required'
    ]);

    $user->username = $request->username;
    $user->nama_lengkap = $request->nama_lengkap;
    $user->role = $request->role;

    // Perbaikan: Pastikan jabatan juga diupdate jika ada di form
    if ($request->has('jabatan')) {
        $user->jabatan = $request->jabatan;
    }

    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    // 3. Tembak Audit Log
    AuditLog::create([
        'aktivitas' => 'UBAH USER',
        'deskripsi' => auth()->user()->nama_lengkap . " mengubah data pengguna: {$user->nama_lengkap}",
        'ip_address' => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user' => auth()->id() // Pastikan kolom ini sesuai dengan struktur tabel audit_logs Anda
    ]);

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

        return redirect()->route('admin.master.user.index')->with('success', 'Pengguna berhasil dihapus dari sistem!');
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
    // Mengambil nilai per_page dari request, default ke 10
    $perPage = $request->input('per_page', 5);
    $search = $request->input('search');

    // Query dasar
    $query = KategoriSurat::query();

    // Logika pencarian
    if ($search) {
        $query->where('nama_kategori', 'like', '%' . $search . '%')
              ->orWhere('kode_kategori', 'like', '%' . $search . '%');
    }

    // Cek apakah user memilih "Semua" atau angka tertentu
    if ($perPage === 'all') {
        $kategori = $query->latest()->get();
    } else {
        $kategori = $query->latest()->paginate((int)$perPage);
    }

    return view('admin.master.kategori.index', compact('kategori'));
}

  /**
     * 2. CREATE: Menampilkan halaman form tambah kategori baru
     */
    public function createKategori()
    {
        // Mengambil semua data kategori untuk dropdown
        $kategoris = KategoriSurat::all();

        // Mengirim variabel $kategoris ke view
        return view('admin.master.kategori.create', compact('kategoris'));
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
   public function masterInstruksi(Request $request)
{
    // Mengambil nilai filter dari request, default ke 10 baris
    $perPage = $request->input('per_page', 10);
    $search = $request->input('search');

    // Memulai query
    $query = InstruksiDisposisi::query();

    // Menambahkan filter pencarian jika ada
    if ($search) {
        $query->where('nama_instruksi', 'LIKE', '%' . $search . '%');
    }

    // Menangani kasus "all" pada per_page
    if ($perPage === 'all') {
        $instruksi = $query->get();
        // Jika ingin tetap bisa di-looping sama seperti paginator, 
        // kita bisa membuatnya menjadi collection. 
        // Namun, jika data sangat banyak, disarankan tetap gunakan paginate.
    } else {
        $instruksi = $query->paginate((int)$perPage)->withQueryString();
    }

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
        $request->validate([
            'nama_instruksi' => 'required|string|max:255',
            'deskripsi'      => 'nullable|string'
        ]);
        
        // Simpan secara eksplisit agar aman dari Mass Assignment
        $instruksiBaru = InstruksiDisposisi::create([
            'nama_instruksi' => $request->nama_instruksi,
            'deskripsi'      => $request->deskripsi
        ]);

        AuditLog::create([
            'aktivitas'      => 'MASTER INSTRUKSI',
            'deskripsi'      => auth()->user()->nama_lengkap . " menambah instruksi disposisi baru: {$instruksiBaru->nama_instruksi}",
            'ip_address'     => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user'        => auth()->id()
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
        $request->validate([
            'nama_instruksi' => 'required|string|max:255',
            'deskripsi'      => 'nullable|string'
        ]);
        
        $instruksi = InstruksiDisposisi::findOrFail($id);
        
        // Update data dengan field yang sudah disesuaikan
        $instruksi->update([
            'nama_instruksi' => $request->nama_instruksi,
            'deskripsi'      => $request->deskripsi
        ]);

        AuditLog::create([
            'aktivitas'      => 'UPDATE INSTRUKSI',
            'deskripsi'      => auth()->user()->nama_lengkap . " mengubah instruksi menjadi: {$instruksi->nama_instruksi}",
            'ip_address'     => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user'        => auth()->id()
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
            'aktivitas'      => 'HAPUS INSTRUKSI',
            'deskripsi'      => auth()->user()->nama_lengkap . " menghapus instruksi: {$nama}",
            'ip_address'     => request()->ip(),
            'waktu_kejadian' => now(),
            'id_user'        => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Instruksi berhasil dihapus');
    }

 public function auditLog(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Query Audit Log
    |--------------------------------------------------------------------------
    */
    $query = AuditLog::with('user');

    /*
    |--------------------------------------------------------------------------
    | Filter Tanggal
    |--------------------------------------------------------------------------
    */
    if ($request->filled('start_date')) {
        $query->whereDate('waktu_kejadian', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('waktu_kejadian', '<=', $request->end_date);
    }

    /*
    |--------------------------------------------------------------------------
    | Pencarian
    |--------------------------------------------------------------------------
    */
    if ($request->filled('search')) {

        $search = trim($request->search);

        $query->where(function ($q) use ($search) {

            $q->where('aktivitas', 'LIKE', "%{$search}%")
              ->orWhere('deskripsi', 'LIKE', "%{$search}%")
              ->orWhere('ip_address', 'LIKE', "%{$search}%")
              ->orWhereHas('user', function ($user) use ($search) {

                    $user->where('nama_lengkap', 'LIKE', "%{$search}%")
                         ->orWhere('username', 'LIKE', "%{$search}%")
                         ->orWhere('jabatan', 'LIKE', "%{$search}%")
                         ->orWhere('role', 'LIKE', "%{$search}%");

              });

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Jumlah Data Per Halaman
    |--------------------------------------------------------------------------
    */
    $perPage = $request->input('per_page', 5);

    // Jika memilih Semua Data
    if ($perPage === 'all') {

        $perPage = max($query->count(), 1);

    } else {

        $perPage = (int) $perPage;

        // Hanya izinkan 5,10,25
        if (!in_array($perPage, [5, 10, 25])) {
            $perPage = 5;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil Data Audit Log
    |--------------------------------------------------------------------------
    */
    $logs = $query
        ->orderByDesc('waktu_kejadian')
        ->paginate($perPage)
        ->withQueryString();

    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */
    return view('admin.aktivitas.index', compact('logs'));
}

  /**
     * =========================================================================
     * MANAJEMEN SURAT (CRUD LENGKAP - AKSARA)
     * =========================================================================
     */

    // 1. INDEX: Menampilkan daftar surat
    public function indexSurat(Request $request)
    {
        $surats = \App\Models\Surat::latest()->paginate(10);
        return view('admin.manajemen_surat.index', compact('surats'));
    }

   public function createSurat()
{
    // 1. Ambil semua data kategori dari database
    $kategoris = \App\Models\KategoriSurat::all();

    // 2. Kirim data tersebut ke view menggunakan compact
    return view('admin.manajemen_surat.create', compact('kategoris'));
}

// 3. STORE: Menyimpan data surat baru
public function storeSurat(Request $request)
{
    // 1. Validasi Input dari Form (file_surat ditambahkan di sini)
    $validatedData = $request->validate([
        'perihal'       => 'required|string|max:255',
        'nomor_surat'   => 'required|string|max:100',
        'asal_instansi' => 'required|string|max:255',
        'id_kategori'   => 'required|exists:kategori_surat,id_kategori', 
        'tanggal_surat' => 'required|date',
        'tanggal_terima'=> 'nullable|date',
        'file_surat'    => 'nullable|file|mimes:pdf|max:5120', // maksimal 5MB, hanya PDF
    ]);

    // 2. Tangani Tanggal Terima
    if (empty($validatedData['tanggal_terima'])) {
        $validatedData['tanggal_terima'] = now()->format('Y-m-d');
    }

    // 3. Tangani Upload File Surat (SOLUSI JENIUS: upload file asli, bukan teks placeholder)
    if ($request->hasFile('file_surat')) {
        $file = $request->file('file_surat');
        
        // Nama file unik: timestamp + nama asli, biar tidak ada bentrok nama file
        $namaFile = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        
        // Simpan ke storage/app/public/dokumen_surat
        $file->storeAs('dokumen_surat', $namaFile, 'public');
        
        $validatedData['file_surat'] = $namaFile;
    } else {
        // Jangan pakai string placeholder — biarkan null agar mudah dicek di view
        $validatedData['file_surat'] = null;
    }

    // 4. Tangani Status (Beri status awal untuk sistem disposisi)
    $validatedData['status'] = 'Belum Dikirim'; 

    // 5. Tangani ID User (Ambil dari sesi admin/petugas yang sedang login)
    $validatedData['id_user'] = \Illuminate\Support\Facades\Auth::id();

    // 6. Eksekusi Simpan Data
    $surat = \App\Models\Surat::create($validatedData);

    // 7. Catat Audit Log
    AuditLog::create([
        'aktivitas'      => 'INPUT SURAT',
        'deskripsi'      => auth()->user()->nama_lengkap . " melakukan input surat baru: {$surat->perihal}",
        'ip_address'     => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user'        => auth()->id()
    ]);

    return redirect()->route('admin.manajemen_surat.index')->with('success', 'Surat berhasil ditambahkan!');
}

// 6. UPDATE: Memperbarui data surat
public function updateSurat(Request $request, $id)
{
    $surat = \App\Models\Surat::findOrFail($id);

    // 1. Validasi Input dari Form (sama seperti storeSurat, file_surat opsional)
    $validatedData = $request->validate([
        'perihal'       => 'required|string|max:255',
        'nomor_surat'   => 'required|string|max:100',
        'asal_instansi' => 'required|string|max:255',
        'id_kategori'   => 'required|exists:kategori_surat,id_kategori',
        'tanggal_surat' => 'required|date',
        'tanggal_terima'=> 'nullable|date',
        'file_surat'    => 'nullable|file|mimes:pdf|max:5120', // maksimal 5MB, hanya PDF
    ]);

    // 2. Tangani Tanggal Terima
    if (empty($validatedData['tanggal_terima'])) {
        $validatedData['tanggal_terima'] = $surat->tanggal_terima ?? now()->format('Y-m-d');
    }

    // 3. Tangani Upload File Surat (SOLUSI JENIUS: hanya proses kalau ADA file baru)
    if ($request->hasFile('file_surat')) {
        $file = $request->file('file_surat');
        $namaFile = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $file->storeAs('dokumen_surat', $namaFile, 'public');

        // Hapus file lama dari storage supaya tidak menumpuk file "sampah"
        if (!empty($surat->file_surat) && \Illuminate\Support\Facades\Storage::disk('public')->exists('dokumen_surat/' . $surat->file_surat)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('dokumen_surat/' . $surat->file_surat);
        }

        $validatedData['file_surat'] = $namaFile;
    } else {
        // PENTING: Kalau admin tidak upload file baru saat edit,
        // JANGAN sentuh field file_surat sama sekali —
        // supaya file lama yang sudah ada tidak ikut tertimpa/kosong.
        unset($validatedData['file_surat']);
    }

    // 4. Eksekusi Update Data (mengganti $request->all() yang berbahaya)
    $surat->update($validatedData);

    // 5. Catat Audit Log
    AuditLog::create([
        'aktivitas' => 'UPDATE SURAT',
        'deskripsi' => \Illuminate\Support\Facades\Auth::user()->nama_lengkap . " mengubah data surat: {$surat->perihal}",
        'ip_address' => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user' => \Illuminate\Support\Facades\Auth::id()
    ]);

    return redirect()->route('admin.manajemen_surat.index')->with('success', 'Surat berhasil diperbarui!');
}

    // 4. SHOW: Detail surat
    public function showSurat($id)
    {
        $surat = \App\Models\Surat::findOrFail($id);
        return view('admin.manajemen_surat.show', compact('surat'));
    }

    // 5. EDIT: Form edit
    public function editSurat($id)
    {
        $surat = \App\Models\Surat::findOrFail($id);
        return view('admin.manajemen_surat.edit', compact('surat'));
    }

    
    

    // 7. DESTROY: Menghapus surat
    public function destroySurat(Request $request, $id)
    {
        $surat = \App\Models\Surat::findOrFail($id);
        $perihal = $surat->perihal;
        $surat->delete();

        AuditLog::create([
            'aktivitas' => 'HAPUS SURAT',
            'deskripsi' => \Illuminate\Support\Facades\Auth::user()->nama_lengkap . " menghapus surat: {$perihal}",
            'ip_address' => $request->ip(),
            'waktu_kejadian' => now(),
            'id_user' => \Illuminate\Support\Facades\Auth::id()
        ]);

        return redirect()->route('admin.manajemen_surat.index')->with('success', 'Surat berhasil dihapus!');
    }

    public function teruskanKePimpinan(Request $request, $id)
{
    $surat = \App\Models\Surat::findOrFail($id);
    
    // Pastikan status disamakan menjadi 'pending'
    $surat->update(['status' => 'pending']); 

    AuditLog::create([
        'aktivitas'      => 'TERUSKAN SURAT',
        'deskripsi'      => auth()->user()->nama_lengkap . " meneruskan surat ke pimpinan: {$surat->perihal}",
        'ip_address'     => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user'        => auth()->id()
    ]);

    return redirect()->back()->with('success', 'Surat berhasil diteruskan ke pimpinan!');
}

  // 1. Menampilkan Halaman List Arsip
public function arsipIndex(Request $request) 
{ 
    $query = \App\Models\Arsip::query();

    // Fitur Pencarian
    if ($request->has('search')) {
        $query->whereHas('surat', function($q) use ($request) {
            $q->where('perihal', 'like', '%' . $request->search . '%')
              ->orWhere('nomor_surat', 'like', '%' . $request->search . '%');
        });
    }

    // Fitur Filter Status
    if ($request->has('status') && $request->status != '') {
        $query->where('status_retensi', $request->status);
    }

    $arsips = $query->latest()->paginate(10);
    
    return view('admin.manajemen_arsip.index', compact('arsips'));
}

// 2. Form Tambah Arsip (DIPERBAIKI)
public function arsipCreate() 
{
    // Mengambil hanya surat yang belum diarsipkan agar tidak muncul duplikat di dropdown
    $surats = \App\Models\Surat::whereDoesntHave('arsip')->get();
    
    return view('admin.manajemen_arsip.create', compact('surats'));
}

// 3. Proses Simpan Arsip (DIPERBAIKI)
public function arsipStore(Request $request) 
{
    // Validasi data
    $request->validate([
        'id_surat'      => 'required|exists:surat,id_surat',
        'lokasi_fisik'  => 'required|string|max:255',
        'tanggal_arsip' => 'required|date',
        'retensi_nilai' => 'required|numeric|min:1',
        'retensi_satuan'=> 'required|in:days,weeks,months,years',
    ]);

    // Menghitung tanggal kadaluarsa (Masa Retensi)
    $tanggal_arsip = \Carbon\Carbon::parse($request->tanggal_arsip);
    $masa_retensi = $tanggal_arsip->copy()->add(
        $request->retensi_nilai, 
        $request->retensi_satuan
    );

    // Menyimpan data ke tabel arsip
    $arsip = \App\Models\Arsip::create([
        'id_surat'      => $request->id_surat,
        'lokasi_fisik'  => $request->lokasi_fisik,
        'tanggal_arsip' => $request->tanggal_arsip,
        'masa_retensi'  => $masa_retensi, // Simpan hasil perhitungan
        'status_retensi'=> 'Aktif',       // Default saat dibuat
    ]);

    // Audit Log
    \App\Models\AuditLog::create([
        'aktivitas'      => 'MANAJEMEN ARSIP',
        'deskripsi'      => auth()->user()->nama_lengkap . ' membuat dokumen arsip baru untuk surat ID: ' . $request->id_surat,
        'ip_address'     => $request->ip(),
        'waktu_kejadian' => now(),
        'id_user'        => auth()->id()
    ]);

    return redirect()->route('admin.manajemen_arsip.index')->with('success', 'Arsip berhasil ditambah!');
}
// 4. Detail Arsip
public function arsipShow($id) 
{
    $arsip = \App\Models\Arsip::findOrFail($id);
    return view('admin.manajemen_arsip.show', compact('arsip'));
}

// 5. Form Edit
public function arsipEdit($id) 
{
    $arsip = \App\Models\Arsip::findOrFail($id);
    return view('admin.manajemen_arsip.edit', compact('arsip'));
}

// 6. Proses Update
public function arsipUpdate(Request $request, $id) 
{
    $arsip = \App\Models\Arsip::findOrFail($id);
    $arsip->update($request->all());

    return redirect()->route('admin.manajemen_arsip.index')->with('success', 'Arsip berhasil diupdate!');
}

// 7. Hapus Arsip
public function arsipDestroy($id) 
{
    $arsip = \App\Models\Arsip::findOrFail($id);
    $arsip->delete();

    return redirect()->route('admin.manajemen_arsip.index')->with('success', 'Arsip berhasil dihapus!');
}
}