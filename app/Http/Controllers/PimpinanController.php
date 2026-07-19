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
    // PROSES TANDA TANGAN DIGITAL + STEMPEL (kalau disertakan)
    // ==========================================================
    if ($request->filled('signature_data') && !empty($surat->file_surat)) {

        $pathFileLama = storage_path('app/public/dokumen_surat/' . $surat->file_surat);

        if (file_exists($pathFileLama)) {
            // 1. Decode gambar TTD dari base64 jadi file sementara
            $signatureBase64 = str_replace('data:image/png;base64,', '', $request->signature_data);
            $tempSignaturePath = storage_path('app/temp_ttd_' . $surat->id_surat . '_' . time() . '.png');
            file_put_contents($tempSignaturePath, base64_decode($signatureBase64));

            $pathStempel = storage_path('app/public/stempel/stempel_lpse_karawang.png');

            // 2. Proses tempel pakai FPDI
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($pathFileLama);
            $halamanTtd = (int) ($request->signature_page ?? $pageCount);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tplId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);

                if ($i == $halamanTtd) {
                    // Konversi posisi persentase (dari drag di layar) ke satuan PDF asli
                    if ($request->filled('signature_x')) {
                        $sigW = ($request->signature_width / 100) * $size['width'];
                        $sigX = ($request->signature_x / 100) * $size['width'];
                        $sigY = ($request->signature_y / 100) * $size['height'];
                        $pdf->Image($tempSignaturePath, $sigX, $sigY, $sigW);
                    }

                    if ($request->filled('stempel_x') && file_exists($pathStempel)) {
                        $stW = ($request->stempel_width / 100) * $size['width'];
                        $stX = ($request->stempel_x / 100) * $size['width'];
                        $stY = ($request->stempel_y / 100) * $size['height'];
                        $pdf->Image($pathStempel, $stX, $stY, $stW);
                    }
                }
            }

            // 3. Simpan sebagai file BARU
            $namaFileBaru = 'surat_' . $surat->id_surat . '_ttd_' . time() . '.pdf';
            $pathFileBaru = storage_path('app/public/dokumen_surat/' . $namaFileBaru);
            $pdf->Output($pathFileBaru, 'F');

            // 4. Hapus file LAMA (supaya tetap satu file saja)
            \Illuminate\Support\Facades\Storage::disk('public')->delete('dokumen_surat/' . $surat->file_surat);

            // 5. Update referensi file di objek surat (belum disimpan ke DB dulu)
            $surat->file_surat          = $namaFileBaru;
            $surat->tanggal_ttd         = now();
            $surat->ditandatangani_oleh = Auth::id();

            // 6. Bersihkan file sementara
            unlink($tempSignaturePath);
        }
    }

    // ==========================================================
    // Logika status & arsip (SUDAH ADA, tidak diubah)
    // ==========================================================
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
                'masa_retensi'   => now()->addYears(5),
                'status_retensi' => 'Aktif'
            ]);
        }
    }

    $surat->status = $statusBaru;
    $surat->save();

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