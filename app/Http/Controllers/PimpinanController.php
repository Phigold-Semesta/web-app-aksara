<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Disposisi;

class PimpinanController extends Controller
{
    public function index()
    {
        return view('pimpinan.dashboard');
    }

    public function tinjauSurat()
    {
        // Mengambil surat yang statusnya perlu ditinjau
        $surats = Surat::where('status', 'Proses')->get();
        return view('pimpinan.surat.tinjau', compact('surats'));
    }

    public function simpanDisposisi(Request $request)
    {
        // Logika simpan ke tabel 'disposisi' sesuai ERD aplikasi Aksara_5.png
        // Pimpinan memilih id_instruksi dari tabel instruksi_disposisi
        return redirect()->back()->with('success', 'Disposisi berhasil dikirim!');
    }

    public function monitoringRiwayat()
    {
        $riwayat = Disposisi::with(['surat', 'user'])->get();
        return view('pimpinan.monitoring.riwayat', compact('riwayat'));
    }

    public function monitoringArsip()
    {
        return view('pimpinan.monitoring.arsip');
    }
}