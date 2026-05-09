<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Arsip;
use App\Models\KategoriSurat; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    /**
     * DASHBOARD PETUGAS
     * Menampilkan statistik dan ringkasan surat terbaru.
     */
    public function dashboard()
    {
        // Statistik Real-time untuk Card
        // Asumsi: id_kategori 1 = Masuk, 2 = Keluar
        $stats = [
            'surat_masuk'  => Surat::where('id_kategori', 1)->count(),
            'surat_keluar' => Surat::where('id_kategori', 2)->count(),
            'total_arsip'  => Arsip::count(),
            'update_time'  => now()->diffForHumans(), 
        ];

        // Ringkasan Monitoring (Limit 5) untuk Dashboard
        $surats = Surat::with('kategori')->latest()->take(5)->get();

        // Data Lengkap untuk Tabel Laporan
        $riwayat_surats = Surat::with(['kategori', 'user'])->latest()->get();

        return view('petugas.dashboard', compact('stats', 'surats', 'riwayat_surats'));
    }

    /**
     * MANAJEMEN SURAT: INDEX
     * Menampilkan daftar semua surat dengan pagination.
     */
    public function index()
    {
        $surats = Surat::with(['kategori', 'user'])
                      ->latest()
                      ->paginate(10);
                      
        return view('petugas.manajemen_surat.index', compact('surats'));
    }

    /**
     * MANAJEMEN SURAT: CREATE
     * Menampilkan form input surat baru.
     */
    public function create()
    {
        $kategoris = KategoriSurat::all(); 
        return view('petugas.manajemen_surat.create', compact('kategoris'));
    }

    /**
     * MANAJEMEN SURAT: STORE
     * Validasi dan simpan data surat beserta upload file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'   => 'required|unique:surat,nomor_surat',
            'tanggal_surat' => 'required|date',
            'asal_instansi' => 'required|string|max:255',
            'perihal'        => 'required|string',
            'id_kategori'   => 'required|exists:kategori_surat,id_kategori',
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
            
            return redirect()->route('petugas.manajemen_surat.index')
                             ->with('success', 'Surat Berhasil Didigitalisasi!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * MANAJEMEN SURAT: SHOW
     * Menampilkan detail surat.
     */
    public function show($id)
    {
        $surat = Surat::with(['kategori', 'user'])->findOrFail($id);
        return view('petugas.manajemen_surat.show', compact('surat'));
    }

    /**
     * MANAJEMEN SURAT: EDIT (SOLUSI UNTUK ERROR ANDA)
     * Menampilkan form edit untuk data surat tertentu.
     */
    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        $kategoris = KategoriSurat::all();
        return view('petugas.manajemen_surat.edit', compact('surat', 'kategoris'));
    }

    /**
     * MANAJEMEN SURAT: UPDATE (SOLUSI UNTUK PROSES EDIT)
     * Mengupdate data surat di database.
     */
    public function update(Request $request, $id)
    {
        $surat = Surat::findOrFail($id);

        $request->validate([
            'nomor_surat'   => 'required|unique:surat,nomor_surat,' . $id . ',id_surat',
            'tanggal_surat' => 'required|date',
            'asal_instansi' => 'required|string|max:255',
            'perihal'        => 'required|string',
            'id_kategori'   => 'required|exists:kategori_surat,id_kategori',
            'file_dokumen'  => 'nullable|mimes:pdf,jpg,png|max:2048', 
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('file_dokumen')) {
                // Hapus file lama jika ada untuk menghemat storage
                if ($surat->file_surat) {
                    Storage::delete('public/dokumen_surat/' . $surat->file_surat);
                }

                $file = $request->file('file_dokumen');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/dokumen_surat', $fileName);
                $surat->file_surat = $fileName;
            }

            $surat->update([
                'nomor_surat'   => $request->nomor_surat,
                'perihal'       => $request->perihal,
                'asal_instansi' => $request->asal_instansi,
                'tanggal_surat' => $request->tanggal_surat,
                'id_kategori'   => $request->id_kategori,
            ]);

            DB::commit();
            
            return redirect()->route('petugas.manajemen_surat.index')
                             ->with('success', 'Data Surat Berhasil Diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * UPDATE STATUS: TERUSKAN KE PIMPINAN
     */
    public function teruskanKePimpinan($id)
    {
        $surat = Surat::findOrFail($id);
        $surat->update(['status' => 'diteruskan']);
        
        return back()->with('success', 'Surat berhasil diteruskan ke Pimpinan.');
    }

    /**
     * MANAJEMEN ARSIP: INDEX
     */
    public function kelolaArsip()
    {
        $arsips = Arsip::with('surat')->latest()->paginate(10);
        return view('petugas.manajemen_arsip.index', compact('arsips'));
    }

    /**
     * FILTER STATUS SURAT
     */
    public function statusSurat()
    {
        $surats = Surat::with('kategori')
                      ->where('id_user', Auth::id())
                      ->latest()
                      ->paginate(10);
        return view('petugas.manajemen_surat.index', compact('surats'));
    }
}