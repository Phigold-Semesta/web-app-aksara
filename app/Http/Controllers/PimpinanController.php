<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\KategoriSurat;
use App\Models\Arsip; 
use Illuminate\Support\Facades\Storage;

class PimpinanController extends Controller
{
    /**
     * Dashboard Pimpinan
     */
    public function dashboard()
    {
        $data = [
            'totalSuratMasuk'  => Surat::where('status', 'masuk')->count(),
            'totalSuratKeluar' => Surat::where('status', 'keluar')->count(),
            'totalDisposisi'   => Disposisi::count(),
            'kategoriList'     => KategoriSurat::all()
        ];

        return view('pimpinan.dashboard', $data);
    }

    /**
     * Manajemen Surat (Penggabungan Tinjau Surat & Riwayat)
     */
    public function indexManajemenSurat()
    {
        $suratMasuk = Surat::where('status', 'Proses')->get();
        $riwayat = Disposisi::with(['surat', 'user'])->latest()->get();
        
        return view('pimpinan.manajemen_surat.index', compact('suratMasuk', 'riwayat'));
    }

    /**
     * Menampilkan detail surat untuk Manajemen Surat
     */
    public function showManajemenSurat($id)
    {
        $surat = Surat::findOrFail($id);
        return view('pimpinan.manajemen_surat.show', compact('surat'));
    }

    /**
     * Menyimpan disposisi baru
     */
    public function simpanDisposisi(Request $request)
    {
        $request->validate([
            'id_surat'      => 'required|exists:surat,id_surat',
            'id_instruksi'  => 'required|exists:instruksi_disposisi,id_instruksi',
            'catatan'       => 'nullable|string'
        ]);

        Disposisi::create([
            'id_surat'         => $request->id_surat,
            'id_instruksi'     => $request->id_instruksi,
            'catatan_pimpinan' => $request->catatan,
            'id_user'          => auth()->id(),
            'tanggal_disposisi'=> now(),
        ]);

        return redirect()->back()->with('success', 'Disposisi berhasil dikirim!');
    }

    /**
     * Menghapus riwayat disposisi
     */
    public function hapusRiwayat($id)
    {
        $riwayat = Disposisi::findOrFail($id);
        $riwayat->delete();
        
        return redirect()->back()->with('success', 'Riwayat berhasil dihapus!');
    }

    /**
     * Monitoring arsip surat (Index)
     */
    public function monitoringArsip()
    {
        $arsipSurat = Arsip::with('surat')->latest()->paginate(10);
        return view('pimpinan.monitoring_arsip.index', compact('arsipSurat'));
    }

    /**
     * Menampilkan detail arsip untuk Pimpinan
     */
    public function showArsip($id)
    {
        $arsip = Arsip::with('surat')->findOrFail($id);
        return view('pimpinan.monitoring_arsip.show', compact('arsip'));
    }

   /**
     * Download dokumen arsip untuk Pimpinan
     */
    public function downloadArsip($id)
    {
        // 1. Ambil data arsip beserta relasi suratnya
        $arsip = Arsip::with('surat')->findOrFail($id);
        
        // 2. Pastikan file_surat ada dan tidak null
        if (!$arsip->surat || empty($arsip->surat->file_surat)) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan di database.');
        }

        // 3. Tentukan path yang benar (sesuaikan dengan folder penyimpanan Anda, misal: 'dokumen_surat')
        // Pastikan path ini sinkron dengan tempat Anda menyimpan file saat upload
        $path = storage_path('app/public/dokumen_surat/' . $arsip->surat->file_surat);

        // 4. Cek apakah file fisik benar-benar ada di server
        if (file_exists($path)) {
            return response()->download($path);
        }

        // 5. Jika file tidak ada, kembalikan pesan error yang jelas
        return redirect()->back()->with('error', 'File fisik dokumen tidak ditemukan di server.');
    }

    /**
     * Laporan & Statistik
     */
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