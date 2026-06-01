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
     * Menampilkan ringkasan eksekutif dan statistik
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
     * Menampilkan daftar surat untuk ditinjau
     */
    public function tinjauSurat()
    {
        // Mengambil surat yang statusnya perlu ditinjau
        $surats = Surat::where('status', 'Proses')->get();
        return view('pimpinan.surat.tinjau', compact('surats'));
    }

    /**
     * Menyimpan disposisi baru
     */
    public function simpanDisposisi(Request $request)
    {
        // Logika simpan ke tabel 'disposisi' sesuai ERD aplikasi Aksara
        // Pimpinan memilih id_instruksi dari tabel instruksi_disposisi
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
     * Monitoring riwayat disposisi
     */
    public function monitoringRiwayat()
    {
        $riwayat = Disposisi::with(['surat', 'user'])->latest()->get();
        return view('pimpinan.monitoring.riwayat', compact('riwayat'));
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
     * Sesuai dengan halaman yang kita buat sebelumnya
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