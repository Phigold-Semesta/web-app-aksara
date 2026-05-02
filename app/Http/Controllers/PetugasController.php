<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Arsip;
use App\Models\KategoriSurat; // Perbaikan: Sesuaikan dengan nama model yang benar
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
            'update_time'  => now()->diffForHumans(), 
        ];

        // Ringkasan Monitoring (Limit 5) untuk Dashboard
        $surats = Surat::with('kategori')->latest()->take(5)->get();

        // Data Lengkap untuk Tabel Laporan (Akan diproses oleh DataTables Export)
        $riwayat_surats = Surat::with(['kategori', 'user'])->latest()->get();

        return view('petugas.dashboard', compact('stats', 'surats', 'riwayat_surats'));
    }

    /**
     * Form Input & Digitalisasi Surat (Method standar Resource: create)
     * DISESUAIKAN: Mengikuti Route::resource('manajemen_surat', ...)
     */
    public function create()
    {
        $kategoris = KategoriSurat::all(); // Perbaikan: Gunakan Model KategoriSurat
        return view('petugas.manajemen_surat.create', compact('kategoris'));
    }

    /**
     * Menyimpan data surat dan mengunggah file (Method standar Resource: store)
     * DISESUAIKAN: Mengikuti Route::resource('manajemen_surat', ...)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'   => 'required|unique:surat,nomor_surat',
            'tanggal_surat' => 'required|date',
            'asal_instansi' => 'required|string|max:255',
            'perihal'        => 'required|string',
            'id_kategori'   => 'required|exists:kategori_surat,id_kategori', // Perbaikan: nama tabel kategori_surat
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
            
            // DISESUAIKAN: Redirect ke index manajemen_surat sesuai web.php
            return redirect()->route('petugas.manajemen_surat.index')
                             ->with('success', 'Surat Berhasil Didigitalisasi!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan daftar status surat (Index Manajemen Surat)
     */
    public function indexManajemenSurat() // Ini dipetakan ke manajemen_surat.index
    {
        $surats = Surat::with('kategori')
                      ->latest()
                      ->paginate(10);
        return view('petugas.manajemen_surat.index', compact('surats'));
    }

    /**
     * Meneruskan Surat Ke Pimpinan
     */
    public function teruskanKePimpinan($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->update(['status' => 'diteruskan']);
        return back()->with('success', 'Surat berhasil diteruskan ke Pimpinan.');
    }

    /**
     * Route Tambahan untuk Status Surat (Halaman khusus jika diperlukan)
     */
    public function statusSurat()
    {
        $surats = Surat::with('kategori')
                      ->where('id_user', Auth::id())
                      ->latest()
                      ->paginate(10);
        return view('petugas.manajemen_surat.index', compact('surats'));
    }

    /**
     * Manajemen Arsip
     */
    public function kelolaArsip()
    {
        $arsips = Arsip::with('surat')->latest()->paginate(10);
        return view('petugas.manajemen_arsip.index', compact('arsips'));
    }
}