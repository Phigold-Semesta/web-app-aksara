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
     * DASHBOARD PIMPINAN (EKSEKUTIF)
     * Menyiapkan Data Statistik Utama dan Seluruh Riwayat Surat untuk DataTables Frontend
     */
    public function dashboard()
    {
        // 1. Ambil data statistik ringkasan pimpinan
        $totalSuratMasuk = Surat::whereHas('kategori', function($q) {
                                $q->where('nama_kategori', 'like', '%Surat Masuk%');
                            })->count();
                            
        $totalSuratKeluar = Surat::whereHas('kategori', function($q) {
                                 $q->where('nama_kategori', 'like', '%Surat Keluar%');
                             })->count();
                            
        $totalDisposisi = Surat::where('status', 'disposisi')->count();

        // 2. Ambil SELURUH data surat beserta relasinya untuk DataTables Frontend
        $surats = Surat::with(['kategori', 'user'])
                        ->latest()
                        ->get();

        // 3. Kirim data ke view dashboard pimpinan
        return view('pimpinan.dashboard', compact(
            'totalSuratMasuk', 
            'totalSuratKeluar', 
            'totalDisposisi', 
            'surats'
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
     * Menampilkan detail riwayat tindakan (Read-Only)
     */
    public function showRiwayat($id)
    {
        // Solusi Jenius: Gunakan Eager Loading (with) untuk menarik semua relasi sekaligus.
        // Ini mencegah database dipanggil berulang-ulang di dalam looping view (N+1 Problem).
        $surat = Surat::with(['kategori', 'disposisi.instruksi_disposisi', 'arsip'])->findOrFail($id);
        
        return view('pimpinan.manajemen_surat.riwayat_show', compact('surat'));
    }

    /**
     * Menampilkan dokumen dengan aman
     */
   public function tampilkanDokumen($id)
{
    $surat = \App\Models\Surat::findOrFail($id);
    // Pastikan kita mendapatkan string yang bersih
    $filename = trim($surat->file_surat);
    
    // Path fisik absolut
    $path = storage_path('app/public/dokumen_surat/' . $filename);

    if (!file_exists($path)) {
        // Coba path alternatif jika file tersimpan langsung di public
        $path = storage_path('app/public/' . $filename);
    }

    if (!file_exists($path)) {
        abort(404, "File tidak ditemukan di server: " . $path);
    }

    // Solusi Jenius: Gunakan fungsi file() dengan header yang aman
    // Ini mengalirkan isi file langsung ke browser tanpa via path URL publik
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . basename($filename) . '"'
    ]);
}
   /**
 * Menyimpan disposisi
 */

public function simpanDisposisi(Request $request)
{
    $request->validate([
        'id_surat'        => 'required|exists:surat,id_surat',
        'id_instruksi'    => 'required|exists:instruksi_disposisi,id_instruksi',
        'catatan'         => 'nullable|string',
        'signature_data'  => 'nullable|string',
        'signature_x'     => 'nullable|numeric',
        'signature_y'     => 'nullable|numeric',
        'signature_width' => 'nullable|numeric',
        'signature_page'  => 'nullable|integer',
        'stempel_data'    => 'nullable|string',
        'stempel_x'       => 'nullable|numeric',
        'stempel_y'       => 'nullable|numeric',
        'stempel_width'   => 'nullable|numeric',
    ]);

    $surat = Surat::findOrFail($request->id_surat);

    Disposisi::create([
        'id_surat'          => $request->id_surat,
        'id_instruksi'      => $request->id_instruksi,
        'catatan_pimpinan'  => $request->catatan,
        'id_user'           => Auth::id(),
        'tanggal_disposisi' => now(),
    ]);

    // ==========================================================
    // PROSES TANDA TANGAN DIGITAL + STEMPEL DINAMIS
    // ==========================================================
    $adaTtd     = $request->filled('signature_data');
    $adaStempel = $request->filled('stempel_data');

    if (($adaTtd || $adaStempel) && !empty($surat->file_surat)) {

        $pathFileLama = storage_path('app/public/dokumen_surat/' . $surat->file_surat);

        if (file_exists($pathFileLama)) {
            $tempSignaturePath = null;
            $tempStempelPath   = null;

            // 1. Decode gambar TTD dari Base64 (baik dari canvas mouse maupun upload)
            if ($adaTtd) {
                $signatureBase64   = preg_replace('#^data:image/\w+;base64,#i', '', $request->signature_data);
                $tempSignaturePath = storage_path('app/temp_ttd_' . $surat->id_surat . '_' . time() . '.png');
                file_put_contents($tempSignaturePath, base64_decode($signatureBase64));
            }

            // 2. Decode gambar STEMPEL Dinamis dari Base64 (atau fallback ke default)
            if ($adaStempel) {
                $stempelBase64   = preg_replace('#^data:image/\w+;base64,#i', '', $request->stempel_data);
                $tempStempelPath = storage_path('app/temp_stempel_' . $surat->id_surat . '_' . time() . '.png');
                file_put_contents($tempStempelPath, base64_decode($stempelBase64));
            } else {
                $defaultStempel = storage_path('app/public/stempel/stempel_lpse_karawang.png');
                if (file_exists($defaultStempel)) {
                    $tempStempelPath = $defaultStempel;
                }
            }

            // 3. Proses tempel menggunakan FPDI
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($pathFileLama);
            $halamanTtd = (int) ($request->signature_page ?? $pageCount);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplId = $pdf->importPage($i);
                $size  = $pdf->getTemplateSize($tplId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);

                if ($i == $halamanTtd) {
                    // Tempel TTD jika tersedia
                    if ($tempSignaturePath && file_exists($tempSignaturePath) && $request->filled('signature_x')) {
                        $sigW = ($request->signature_width / 100) * $size['width'];
                        $sigX = ($request->signature_x / 100) * $size['width'];
                        $sigY = ($request->signature_y / 100) * $size['height'];
                        $pdf->Image($tempSignaturePath, $sigX, $sigY, $sigW);
                    }

                    // Tempel STEMPEL jika tersedia
                    if ($tempStempelPath && file_exists($tempStempelPath) && $request->filled('stempel_x')) {
                        $stW = ($request->stempel_width / 100) * $size['width'];
                        $stX = ($request->stempel_x / 100) * $size['width'];
                        $stY = ($request->stempel_y / 100) * $size['height'];
                        $pdf->Image($tempStempelPath, $stX, $stY, $stW);
                    }
                }
            }

            // 4. Simpan sebagai file BARU
            $namaFileBaru = 'surat_' . $surat->id_surat . '_ttd_' . time() . '.pdf';
            $pathFileBaru = storage_path('app/public/dokumen_surat/' . $namaFileBaru);
            $pdf->Output($pathFileBaru, 'F');

            // 5. Hapus file LAMA (supaya tetap satu file saja)
            \Illuminate\Support\Facades\Storage::disk('public')->delete('dokumen_surat/' . $surat->file_surat);

            // 6. Update referensi file di objek surat
            $surat->file_surat          = $namaFileBaru;
            $surat->tanggal_ttd         = now();
            $surat->ditandatangani_oleh = Auth::id();

            // 7. Bersihkan file sementara
            if ($tempSignaturePath && file_exists($tempSignaturePath)) {
                unlink($tempSignaturePath);
            }
            if ($adaStempel && $tempStempelPath && file_exists($tempStempelPath)) {
                unlink($tempStempelPath);
            }
        }
    }

    // ==========================================================
    // Logika status & arsip
    // ==========================================================
    $instruksi  = InstruksiDisposisi::find($request->id_instruksi);
    $statusBaru = 'DISPOSISI';

    if ($instruksi && stripos($instruksi->nama_instruksi, 'Arsip') !== false) {
        $statusBaru = 'DIARSIPKAN';

        $arsipExists = Arsip::where('id_surat', $request->id_surat)->exists();
        if (!$arsipExists) {
            Arsip::create([
                'id_surat'       => $request->id_surat,
                'lokasi_fisik'   => 'Belum ditentukan',
                'tanggal_arsip'  => now(),
                'masa_retensi'   => now()->addYears(5),
                'status_retensi' => 'Aktif'
            ]);
        }
    }

    $surat->status = $statusBaru;
    $surat->save();

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Disposisi berhasil dikirim dengan status: ' . $statusBaru
        ]);
    }

    return redirect()->route('pimpinan.manajemen_surat.index')->with('success', 'Disposisi berhasil dikirim dengan status: ' . $statusBaru);
}

    public function hapusRiwayat($id)
    {
        $riwayat = Disposisi::findOrFail($id);
        $riwayat->delete();
        
        return redirect()->back()->with('success', 'Riwayat berhasil dihapus!');
    }

    /**
     * Monitoring Arsip Surat Pimpinan
     * Dilengkapi Filter Search & Dinamis Pagination (Per Page)
     */
    public function monitoringArsip(Request $request)
    {
        // 1. Ambil query pencarian & limit per baris
        $search  = $request->input('search');
        $perPage = $request->input('per_page', 5); // Default 5 baris

        // 2. Query data Arsip dengan eager loading relasi Surat
        $query = Arsip::with('surat')->latest();

        // 3. Filter Pencarian (Cari berdasarkan Perihal atau Nomor Surat)
        if (!empty($search)) {
            $query->whereHas('surat', function ($q) use ($search) {
                $q->where('perihal', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%");
            })->orWhere('lokasi_fisik', 'like', "%{$search}%");
        }

        // 4. Handle Opsi 'Semua Data' (-1)
        if ($perPage == -1) {
            $arsipSurat = $query->paginate($query->count() ?: 1)->appends($request->all());
        } else {
            $arsipSurat = $query->paginate((int)$perPage)->appends($request->all());
        }

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