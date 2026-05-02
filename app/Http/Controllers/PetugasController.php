<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Arsip;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    /**
     * Menampilkan Dashboard Petugas dengan Statistik & Tabel Riwayat
     */
    public function index()
    {
        // Statistik Real-time untuk Card
        $stats = [
            'surat_masuk'  => Surat::where('id_kategori', 1)->count(),
            'surat_keluar' => Surat::where('id_kategori', 2)->count(),
            'total_arsip'  => Arsip::count(),
            'update_time'  => now()->diffForHumans(), // Contoh: 2 minutes ago
        ];

        // Ringkasan Monitoring (Limit 5)
        $surats = Surat::with('kategori')->latest()->take(5)->get();

        // Data Lengkap untuk Tabel Laporan (Akan diproses oleh DataTables Export)
        $riwayat_surats = Surat::with(['kategori', 'user'])->latest()->get();

        return view('petugas.dashboard', compact('stats', 'surats', 'riwayat_surats'));
    }

    /**
     * Form Input & Digitalisasi Surat
     */
    public function inputSurat()
    {
        $kategoris = Kategori::all(); 
        return view('petugas.manajemen_surat.create', compact('kategoris'));
    }

    /**
     * Menyimpan data surat dan mengunggah file
     */
    public function storeSurat(Request $request)
    {
        $request->validate([
            'nomor_surat'   => 'required|unique:surat,nomor_surat',
            'tanggal_surat' => 'required|date',
            'asal_instansi' => 'required|string|max:255',
            'perihal'       => 'required|string',
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'file_dokumen'  => 'required|mimes:pdf,jpg,png|max:2048', 
        ]);

        try {
            DB::beginTransaction();

            $fileName = null;
            if ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/dokumen_surat', $fileName);
            }

            Surat::create([
                'nomor_surat'    => $request->nomor_surat,
                'perihal'        => $request->perihal,
                'asal_instansi'  => $request->asal_instansi,
                'tanggal_surat'  => $request->tanggal_surat,
                'tanggal_terima' => now(),
                'file_surat'     => $fileName,
                'status'         => 'pending',
                'id_user'        => Auth::id(),
                'id_kategori'    => $request->id_kategori,
            ]);

            DB::commit();
            return redirect()->route('petugas.status_surat')
                             ->with('success', 'Surat Berhasil Didigitalisasi!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function teruskanKePimpinan($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->update(['status' => 'diteruskan']);
        return back()->with('success', 'Surat berhasil diteruskan ke Pimpinan.');
    }

    public function statusSurat()
    {
        $surats = Surat::with('kategori')
                      ->where('id_user', Auth::id())
                      ->latest()
                      ->paginate(10);
        return view('petugas.manajemen_surat.index', compact('surats'));
    }

    public function kelolaArsip()
    {
        $arsips = Arsip::with('surat')->latest()->paginate(10);
        return view('petugas.manajemen_arsip.index', compact('arsips'));
    }
}