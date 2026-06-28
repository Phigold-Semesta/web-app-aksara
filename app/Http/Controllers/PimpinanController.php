<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\KategoriSurat;
use App\Models\Arsip; 
use App\Models\InstruksiDisposisi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PimpinanController extends Controller
{
    /**
     * Dashboard Pimpinan
     */
   public function dashboard()
{
    // 1. Ambil data statistik (seperti yang sudah ada)
    $totalSuratMasuk = \App\Models\Surat::whereHas('kategori', function($q) {
                           $q->where('nama_kategori', 'like', '%Surat Masuk%');
                       })->count();
                       
    $totalSuratKeluar = \App\Models\Surat::whereHas('kategori', function($q) {
                            $q->where('nama_kategori', 'like', '%Surat Keluar%');
                        })->count();
                        
    $totalDisposisi = \App\Models\Surat::where('status', 'disposisi')->count();

    // 2. AMBIL DATA SURAT UNTUK TABEL (Inilah yang kurang!)
    $surats = \App\Models\Surat::with('kategori')->latest()->get();

    // 3. Ambil data kategori untuk tabel monitoring kategori di bawah (jika masih diperlukan)
    $kategoriList = \App\Models\KategoriSurat::all();

    // 4. Kirim semua data ke view
    return view('pimpinan.dashboard', compact(
        'totalSuratMasuk', 
        'totalSuratKeluar', 
        'totalDisposisi', 
        'surats', 
        'kategoriList'
    ));
}

    /**
     * Manajemen Surat
     */
    public function indexManajemenSurat()
    {
        $suratMasuk = Surat::where('status', 'pending')->get();
        $riwayat = Disposisi::with(['surat', 'user'])->latest()->get();
        
        return view('pimpinan.manajemen_surat.index', compact('suratMasuk', 'riwayat'));
    }

    /**
     * Menampilkan detail surat
     */
    public function showManajemenSurat($id)
    {
        $surat = Surat::findOrFail($id);
        $instruksi = InstruksiDisposisi::all(); 

        return view('pimpinan.manajemen_surat.show', compact('surat', 'instruksi'));
    }

    /**
     * Menampilkan dokumen dengan aman
     */
    public function tampilkanDokumen($id)
    {
        $surat = Surat::findOrFail($id);
        $filename = trim($surat->file_surat);
        
        $paths = [
            storage_path('app/public/' . $filename),
            storage_path('app/public/dokumen_surat/' . $filename)
        ];

        $path = null;
        foreach ($paths as $p) {
            if (file_exists($p)) {
                $path = $p;
                break;
            }
        }

        if (!$path) {
            Log::error("File tidak ditemukan di semua path yang diperiksa: " . implode(' atau ', $paths));
            abort(404, 'Dokumen fisik tidak ditemukan di sistem.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    /**
     * Menyimpan disposisi
     */
    public function simpanDisposisi(Request $request)
    {
        $request->validate([
            'id_surat'     => 'required|exists:surat,id_surat',
            'id_instruksi'  => 'required|exists:instruksi_disposisi,id_instruksi',
            'catatan'       => 'nullable|string'
        ]);

        Disposisi::create([
            'id_surat'         => $request->id_surat,
            'id_instruksi'     => $request->id_instruksi,
            'catatan_pimpinan' => $request->catatan,
            'id_user'          => Auth::id(),
            'tanggal_disposisi'=> now(),
        ]);

        $instruksi = InstruksiDisposisi::find($request->id_instruksi);
        $statusBaru = 'DISPOSISI'; 

        if ($instruksi && stripos($instruksi->nama_instruksi, 'Arsip') !== false) {
            $statusBaru = 'DIARSIPKAN'; 
            
            $arsipExists = Arsip::where('id_surat', $request->id_surat)->exists();
            if (!$arsipExists) {
                Arsip::create([
                    'id_surat'       => $request->id_surat,
                    'lokasi_fisik'   => 'Belum ditentukan',
                    'tanggal_arsip'  => now(),
                    'masa_retensi'   => 'N/A',
                    'status_retensi' => 'Aktif'
                ]);
            }
        }

        Surat::where('id_surat', $request->id_surat)->update(['status' => $statusBaru]);

        return redirect()->route('pimpinan.manajemen_surat.index')->with('success', 'Disposisi berhasil dikirim dengan status: ' . $statusBaru);
    }

    public function hapusRiwayat($id)
    {
        $riwayat = Disposisi::findOrFail($id);
        $riwayat->delete();
        
        return redirect()->back()->with('success', 'Riwayat berhasil dihapus!');
    }

    public function monitoringArsip()
    {
        $arsipSurat = Arsip::with('surat')->latest()->paginate(10);
        return view('pimpinan.monitoring_arsip.index', compact('arsipSurat'));
    }

    public function showArsip($id)
    {
        $arsip = Arsip::with('surat')->findOrFail($id);
        return view('pimpinan.monitoring_arsip.show', compact('arsip'));
    }

    /**
     * Memperbaiki logika download agar lebih fleksibel
     */
    public function downloadArsip($id)
    {
        $arsip = Arsip::with('surat')->findOrFail($id);
        $filename = trim($arsip->surat->file_surat);
        
        if (empty($filename)) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        // Mencari file di lokasi yang sama dengan tampilkanDokumen
        $paths = [
            storage_path('app/public/' . $filename),
            storage_path('app/public/dokumen_surat/' . $filename)
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return response()->download($path);
            }
        }

        return redirect()->back()->with('error', 'File fisik dokumen tidak ditemukan di server.');
    }

    public function laporan()
    {
        $data = [
            'totalSuratMasuk'  => Surat::where('status', 'masuk')->count(),
            'totalSuratKeluar' => Surat::where('status', 'keluar')->count(),
            'totalDisposisi'   => Disposisi::count(),
            'kategoriList'     => KategoriSurat::all()
        ];

        return view('pimpinan.laporan', $data);
    }
}