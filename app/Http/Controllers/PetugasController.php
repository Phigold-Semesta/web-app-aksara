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
     */
    public function dashboard()
    {
        $stats = [
            'surat_masuk'  => Surat::where('id_kategori', 1)->count(),
            'surat_keluar' => Surat::where('id_kategori', 2)->count(),
            'total_arsip'  => Arsip::count(),
            'update_time'  => now()->diffForHumans(), 
        ];

        $surats = Surat::with('kategori')->latest()->take(5)->get();
        $riwayat_surats = Surat::with(['kategori', 'user'])->latest()->get();

        return view('petugas.dashboard', compact('stats', 'surats', 'riwayat_surats'));
    }

    /**
     * MANAJEMEN SURAT: INDEX (DENGAN SEARCH & FILTER PER PAGE)
     */
    public function index(Request $request)
    {
        $query = Surat::with(['kategori', 'user'])->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('perihal', 'like', "%$search%")
                  ->orWhere('nomor_surat', 'like', "%$search%")
                  ->orWhere('asal_instansi', 'like', "%$search%");
            });
        }

        $perPage = $request->get('per_page', 10);
        
        if ($perPage == 'all') {
            $surats = $query->paginate($query->count())->withQueryString();
        } else {
            $surats = $query->paginate($perPage)->withQueryString();
        }
                          
        return view('petugas.manajemen_surat.index', compact('surats'));
    }

    /**
     * MANAJEMEN SURAT: CREATE
     */
    public function create()
    {
        $kategoris = KategoriSurat::all(); 
        return view('petugas.manajemen_surat.create', compact('kategoris'));
    }

    /**
     * MANAJEMEN SURAT: STORE
     * DISEMPURNAKAN: Menangani output cerdas dari OpenCV (PDF Base64)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat'   => 'required|unique:surat,nomor_surat',
            'tanggal_surat' => 'required|date',
            'asal_instansi' => 'required|string|max:255',
            'perihal'        => 'required|string',
            'id_kategori'   => 'required|exists:kategori_surat,id_kategori',
            'file_dokumen'  => 'nullable|mimes:pdf,jpg,png|max:4096', 
            'pdf_base64'    => 'nullable|string', 
        ]);

        try {
            DB::beginTransaction();

            $fileName = null;

            // --- LOGIKA 1: Menangkap Hasil Auto-Crop OpenCV (PDF Base64) ---
            if ($request->filled('pdf_base64')) {
                // Data Base64 dari jsPDF yang berisi hasil crop OpenCV
                $base64Data = $request->pdf_base64;
                if (strpos($base64Data, ',') !== false) {
                    $format = explode(',', $base64Data);
                    $decodedFile = base64_decode($format[1]);
                } else {
                    $decodedFile = base64_decode($base64Data);
                }
                
                // Penamaan file khusus hasil Smart Scan
                $fileName = 'SMART_SCAN_' . time() . '_' . uniqid() . '.pdf';
                Storage::disk('public')->put('dokumen_surat/' . $fileName, $decodedFile);
            } 
            // --- LOGIKA 2: Jika User Pilih Upload Manual ---
            elseif ($request->hasFile('file_dokumen')) {
                $file = $request->file('file_dokumen');
                
                // Tetap menggunakan sanitasi nama file asli agar URL preview aman
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeName = preg_replace('/[^A-Za-z0-9]/', '_', $originalName); 
                $fileName = time() . '_' . $safeName . '.' . $file->getClientOriginalExtension();
                
                $file->storeAs('dokumen_surat', $fileName, 'public');
            }

            // Validasi akhir jika keduanya kosong
            if (!$fileName) {
                throw new \Exception("Gagal mendigitalisasi dokumen. Gunakan Smart Scan atau Upload File.");
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
                             ->with('success', 'Surat Berhasil Didigitalisasi dengan Smart Scanner!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * MANAJEMEN SURAT: SHOW
     */
    public function show($id)
    {
        $surat = Surat::with(['kategori', 'user'])->findOrFail($id);
        return view('petugas.manajemen_surat.show', compact('surat'));
    }

    /**
     * MANAJEMEN SURAT: EDIT
     */
    public function edit($id)
    {
        $surat = Surat::findOrFail($id);
        $kategoris = KategoriSurat::all();
        return view('petugas.manajemen_surat.edit', compact('surat', 'kategoris'));
    }

    /**
     * MANAJEMEN SURAT: UPDATE
     * DISEMPURNAKAN: Sinkronisasi pembersihan file lama saat update scan/upload
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
            'file_dokumen'  => 'nullable|mimes:pdf,jpg,png|max:4096', 
            'pdf_base64'    => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            if ($request->filled('pdf_base64') || $request->hasFile('file_dokumen')) {
                
                // Hapus file fisik lama agar storage tidak penuh
                if ($surat->file_surat) {
                    Storage::disk('public')->delete('dokumen_surat/' . $surat->file_surat);
                }

                if ($request->filled('pdf_base64')) {
                    $base64Data = $request->pdf_base64;
                    $format = explode(',', $base64Data);
                    $decodedFile = base64_decode($format[1] ?? $format[0]);
                    $newFileName = 'SMART_SCAN_UPDATED_' . time() . '_' . uniqid() . '.pdf';
                    Storage::disk('public')->put('dokumen_surat/' . $newFileName, $decodedFile);
                    $surat->file_surat = $newFileName;
                } 
                else {
                    $file = $request->file('file_dokumen');
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeName = preg_replace('/[^A-Za-z0-9]/', '_', $originalName);
                    $newFileName = time() . '_' . $safeName . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('dokumen_surat', $newFileName, 'public');
                    $surat->file_surat = $newFileName;
                }
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
     * MANAJEMEN SURAT: DESTROY
     */
    public function destroy($id)
    {
        try {
            $surat = Surat::findOrFail($id);

            if ($surat->file_surat) {
                Storage::disk('public')->delete('dokumen_surat/' . $surat->file_surat);
            }

            $surat->delete();

            return redirect()->route('petugas.manajemen_surat.index')
                             ->with('success', 'Data Surat dan Dokumen Berhasil Dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
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