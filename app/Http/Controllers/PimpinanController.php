<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Disposisi;
use App\Models\KategoriSurat;

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
        // Mengambil surat yang perlu ditinjau (status 'Proses')
        $suratMasuk = Surat::where('status', 'Proses')->get();
        
        // Mengambil riwayat disposisi
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
     * Monitoring arsip surat
     */
    public function monitoringArsip()
    {
        return view('pimpinan.monitoring.arsip');
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