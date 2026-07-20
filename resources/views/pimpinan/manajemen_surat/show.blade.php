@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300">
    {{-- Header & Tombol Kembali --}}
    <div class="mb-10">
        <a href="{{ route('pimpinan.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight uppercase italic">Detail Arsip (Pimpinan)</h1>
    </div>

    {{-- Layout Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KIRI: Informasi Penyimpanan & Metadata --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Informasi Penyimpanan --}}
            <div class="bg-emerald-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-emerald-900/20">
                <p class="text-emerald-400 font-black uppercase text-xs tracking-widest mb-6">Informasi Penyimpanan</p>
                <div class="space-y-6">
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Lokasi Rak/Lemari</p>
                        <div class="flex items-center gap-3 mt-1">
                            <i class="fas fa-archive text-emerald-400"></i>
                            <span class="text-lg font-bold">{{ $surat->arsip->lokasi_fisik ?? 'Tidak ditentukan' }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Diarsipkan Pada</p>
                        <p class="text-lg font-bold mt-1">
                            {{ $surat->arsip ? $surat->arsip->tanggal_arsip->translatedFormat('d F Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Habis Masa Retensi</p>
                        @if(isset($surat->arsip) && !empty($surat->arsip->masa_retensi))
                            <p class="text-lg font-bold text-emerald-300 mt-1">{{ $surat->arsip->masa_retensi->translatedFormat('d F Y') }}</p>
                            <p class="text-[10px] text-emerald-400 italic opacity-70 mt-1">
                                *{{ $surat->arsip->masa_retensi->isPast() ? 'Sudah Kadaluarsa' : $surat->arsip->masa_retensi->diffForHumans() }}
                            </p>
                        @else
                            <p class="text-lg font-bold text-gray-400 mt-1">N/A</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Metadata Surat --}}
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5">
                <p class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-6">Metadata Surat</p>
                <div class="space-y-6">
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Nomor Surat</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Asal Instansi</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $surat->asal_instansi }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Perihal</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $surat->perihal }}</p>
                    </div>
                </div>
            </div>

            {{-- Status TTD --}}
            @if($surat->tanggal_ttd)
                <div class="flex items-center gap-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-2xl text-xs font-bold">
                    <i class="fas fa-check-circle"></i>
                    Disahkan digital pada {{ $surat->tanggal_ttd->translatedFormat('d F Y, H:i') }}
                </div>
            @endif

            {{-- Form Disposisi + Tanda Tangan Digital --}}
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5">
                <p class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-6">Instruksi Disposisi</p>
                <form action="{{ route('pimpinan.manajemen_surat.simpan_disposisi') }}" method="POST" id="formDisposisi">
                    @csrf
                    <input type="hidden" name="id_surat" value="{{ $surat->id_surat }}">

                    <input type="hidden" name="signature_data" id="input_signature_data">
                    <input type="hidden" name="signature_x" id="input_signature_x">
                    <input type="hidden" name="signature_y" id="input_signature_y">
                    <input type="hidden" name="signature_width" id="input_signature_width">
                    <input type="hidden" name="signature_page" id="input_signature_page">
                    <input type="hidden" name="stempel_data" id="input_stempel_data">
                    <input type="hidden" name="stempel_x" id="input_stempel_x">
                    <input type="hidden" name="stempel_y" id="input_stempel_y">
                    <input type="hidden" name="stempel_width" id="input_stempel_width">

                    <div class="space-y-4">
                        <select name="id_instruksi" class="w-full p-4 rounded-2xl border border-emerald-100 bg-emerald-50/50 font-bold focus:ring-2 focus:ring-emerald-500" required>
                            <option value="">-- Pilih Instruksi --</option>
                            @foreach($instruksi as $item)
                                <option value="{{ $item->id_instruksi }}">{{ $item->nama_instruksi }}</option>
                            @endforeach
                        </select>
                        <textarea name="catatan" placeholder="Tambahkan catatan pimpinan..." class="w-full p-4 rounded-2xl border border-emerald-100 bg-emerald-50/50 font-medium focus:ring-2 focus:ring-emerald-500" rows="3"></textarea>

                        <button type="button" id="btnBukaTtd" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-2xl font-black uppercase text-sm shadow-lg transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-signature"></i> <span id="labelTombolTtd">Tulis Tanda Tangan</span>
                        </button>

                        <button type="button" id="btnBukaUpload" class="w-full bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-2xl font-black uppercase text-sm shadow-lg transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-upload"></i> Upload TTD & Stempel
                        </button>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white p-4 rounded-2xl font-black uppercase text-sm shadow-lg shadow-emerald-600/30 transition-all">
                            Kirim Disposisi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- KANAN: Preview Dokumen --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5">

            @php
                $fileExists = !empty($surat->file_surat)
                    && \Illuminate\Support\Facades\Storage::disk('public')->exists('dokumen_surat/' . $surat->file_surat);
                $urlPdf = $fileExists ? asset('storage/dokumen_surat/' . $surat->file_surat) : null;
            @endphp

            <div class="flex justify-between items-center mb-4 px-2">
                <p class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest">Preview Dokumen Digital</p>
                @if($fileExists)
                    <div class="flex items-center gap-3">
                        <span id="navHalaman" class="text-xs font-bold text-emerald-600"></span>
                        <a href="{{ $urlPdf }}" target="_blank" class="text-emerald-600 font-bold text-xs hover:underline">
                            BUKA LAYAR PENUH <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>

            @if($fileExists)
                <div id="pdfViewerWrapper" class="relative w-full overflow-auto border border-emerald-50 dark:border-slate-800 rounded-3xl bg-gray-100 dark:bg-slate-950" style="height: 800px;">
                    <div id="pdfPageContainer" class="relative mx-auto" style="width: fit-content;">
                        <canvas id="pdfCanvas"></canvas>

                        {{-- Elemen TTD: ditambah tombol hapus (×) di pojok kiri atas --}}
                        <div id="dragSignature" class="hidden absolute cursor-move border-2 border-dashed border-blue-500 bg-blue-50/30" style="width: 140px; height: 60px; top: 20px; left: 20px;">
                            <button type="button" id="hapusSignature" title="Hapus tanda tangan dari dokumen" class="absolute -top-3 -left-3 w-6 h-6 bg-red-600 hover:bg-red-700 text-white rounded-full text-xs font-black flex items-center justify-center z-10 shadow-md">×</button>
                            <img id="imgSignaturePreview" src="" class="w-full h-full object-contain pointer-events-none" alt="Tanda tangan">
                            <div class="absolute -bottom-2 -right-2 w-4 h-4 bg-blue-600 rounded-full cursor-se-resize" id="resizeSignature"></div>
                        </div>

                        {{-- Elemen Stempel: ditambah tombol hapus (×) di pojok kiri atas --}}
                        <div id="dragStempel" class="hidden absolute cursor-move border-2 border-dashed border-amber-500 bg-amber-50/30" style="width: 120px; height: 120px; top: 20px; left: 200px;">
                            <button type="button" id="hapusStempel" title="Hapus stempel dari dokumen" class="absolute -top-3 -left-3 w-6 h-6 bg-red-600 hover:bg-red-700 text-white rounded-full text-xs font-black flex items-center justify-center z-10 shadow-md">×</button>
                            <img id="imgStempelPreview" src="{{ asset('storage/stempel/stempel_lpse_karawang.png') }}" class="w-full h-full object-contain pointer-events-none" alt="Stempel">
                            <div class="absolute -bottom-2 -right-2 w-4 h-4 bg-amber-600 rounded-full cursor-se-resize" id="resizeStempel"></div>
                        </div>
                    </div>
                </div>

                {{-- Modal 1: Gambar Tanda Tangan Manual --}}
                <div id="modalTtd" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl w-full max-w-lg">
                        <h3 class="font-black text-lg mb-4 text-emerald-950 dark:text-white">Gambar Tanda Tangan</h3>
                        <canvas id="signatureCanvas" width="440" height="180" class="border-2 border-emerald-200 dark:border-slate-700 rounded-xl bg-white w-full"></canvas>
                        <div class="flex gap-3 mt-4">
                            <button type="button" onclick="clearSignaturePad()" class="px-4 py-2 bg-gray-200 dark:bg-slate-700 rounded-xl font-bold text-sm">Hapus</button>
                            <button type="button" onclick="terapkanTandaTangan()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm flex-1">Terapkan ke Dokumen</button>
                            <button type="button" onclick="tutupModalTtd()" class="px-4 py-2 bg-red-100 text-red-600 rounded-xl font-bold text-sm">Batal</button>
                        </div>
                    </div>
                </div>

                {{-- Modal 2: Upload Gambar TTD & Stempel --}}
                <div id="modalUpload" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl w-full max-w-lg">
                        <h3 class="font-black text-lg mb-6 text-emerald-950 dark:text-white">Upload Gambar TTD & Stempel</h3>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-emerald-900 dark:text-emerald-100 mb-2">Gambar Tanda Tangan</label>
                                <input type="file" id="fileSignatureUpload" accept="image/png,image/jpeg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                                <div id="previewSignatureUpload" class="hidden mt-3 p-3 border border-emerald-100 dark:border-slate-700 rounded-xl bg-gray-50 dark:bg-slate-800 relative">
                                    {{-- BARU: Tombol hapus preview upload TTD --}}
                                    <button type="button" id="hapusPreviewSignatureUpload" title="Hapus gambar ini" class="absolute top-2 right-2 w-6 h-6 bg-red-600 hover:bg-red-700 text-white rounded-full text-xs font-black flex items-center justify-center">×</button>
                                    <img id="imgPreviewSignatureUpload" src="" class="max-h-24 mx-auto object-contain" alt="Preview tanda tangan">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-emerald-900 dark:text-emerald-100 mb-2">Gambar Stempel Resmi</label>
                                <input type="file" id="fileStempelUpload" accept="image/png,image/jpeg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer">
                                <div id="previewStempelUpload" class="hidden mt-3 p-3 border border-emerald-100 dark:border-slate-700 rounded-xl bg-gray-50 dark:bg-slate-800 relative">
                                    {{-- BARU: Tombol hapus preview upload Stempel --}}
                                    <button type="button" id="hapusPreviewStempelUpload" title="Hapus gambar ini" class="absolute top-2 right-2 w-6 h-6 bg-red-600 hover:bg-red-700 text-white rounded-full text-xs font-black flex items-center justify-center">×</button>
                                    <img id="imgPreviewStempelUpload" src="" class="max-h-24 mx-auto object-contain" alt="Preview stempel">
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6">
                            <button type="button" onclick="terapkanUpload()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm flex-1">Terapkan ke Dokumen</button>
                            <button type="button" onclick="tutupModalUpload()" class="px-4 py-2 bg-red-100 text-red-600 rounded-xl font-bold text-sm">Batal</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="w-full h-[700px] flex flex-col items-center justify-center text-emerald-300 dark:text-slate-600 gap-3">
                    <i class="fas fa-file-circle-exclamation text-6xl"></i>
                    <p class="font-bold text-emerald-800 dark:text-emerald-200">Dokumen digital belum tersedia untuk surat ini.</p>
                    <p class="text-xs text-emerald-500 dark:text-slate-500">Silakan hubungi petugas/admin untuk mengunggah berkas fisik.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if($fileExists)
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.6/signature_pad.umd.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const urlPdf = @json($urlPdf);
const canvas = document.getElementById('pdfCanvas');
const ctx = canvas.getContext('2d');
let pdfDoc = null;
let halamanSekarang = 1;
let ttdAktif = false;

pdfjsLib.getDocument(urlPdf).promise.then(function (pdf) {
    pdfDoc = pdf;
    document.getElementById('navHalaman').innerText = `1 / ${pdf.numPages}`;
    renderHalaman(1);
});

function renderHalaman(nomor) {
    pdfDoc.getPage(nomor).then(function (page) {
        const viewport = page.getViewport({ scale: 1.3 });
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        page.render({ canvasContext: ctx, viewport: viewport });
        halamanSekarang = nomor;
    });
}

// ===== Modal 1: Gambar TTD manual =====
const btnBukaTtd = document.getElementById('btnBukaTtd');
const modalTtd = document.getElementById('modalTtd');
const sigCanvas = document.getElementById('signatureCanvas');
const signaturePad = new SignaturePad(sigCanvas);

btnBukaTtd.addEventListener('click', function () {
    modalTtd.classList.remove('hidden');
});

function tutupModalTtd() {
    modalTtd.classList.add('hidden');
}

function clearSignaturePad() {
    signaturePad.clear();
}

// ===== DIPERBAIKI: Tulis Tanda Tangan (manual, pakai mouse/cursor) =====
// Sebelumnya fungsi ini otomatis menampilkan dragStempel juga, padahal
// tombol "Tulis Tanda Tangan" seharusnya HANYA menerapkan gambar tanda
// tangan hasil coretan manual. Stempel tidak boleh muncul otomatis di sini.
// Stempel tetap bisa ditambahkan lewat menu terpisah "Upload TTD & Stempel".
function terapkanTandaTangan() {
    if (signaturePad.isEmpty()) {
        alert('Silakan gambar tanda tangan terlebih dahulu!');
        return;
    }
    const dataUrl = signaturePad.toDataURL('image/png');
    document.getElementById('imgSignaturePreview').src = dataUrl;
    document.getElementById('input_signature_data').value = dataUrl;

    document.getElementById('dragSignature').classList.remove('hidden');
    // Catatan: dragStempel SENGAJA tidak ditampilkan di sini.
    // Stempel hanya muncul jika diunggah lewat modal "Upload TTD & Stempel".

    ttdAktif = true;
    document.getElementById('labelTombolTtd').innerText = 'Tanda Tangan Aktif — Geser Posisinya';
    tutupModalTtd();
}

// ===== Modal 2: Upload gambar TTD & Stempel =====
const btnBukaUpload = document.getElementById('btnBukaUpload');
const modalUpload = document.getElementById('modalUpload');
let base64SignatureUpload = null;
let base64StempelUpload = null;

btnBukaUpload.addEventListener('click', function () {
    modalUpload.classList.remove('hidden');
});

function tutupModalUpload() {
    modalUpload.classList.add('hidden');
}

function bacaFileGambar(inputElement, previewWrapperId, previewImgId, callback) {
    inputElement.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.match('image/png') && !file.type.match('image/jpeg')) {
            alert('Hanya file PNG atau JPG yang diperbolehkan!');
            inputElement.value = '';
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB!');
            inputElement.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) {
            const dataUrl = event.target.result;
            document.getElementById(previewImgId).src = dataUrl;
            document.getElementById(previewWrapperId).classList.remove('hidden');
            callback(dataUrl);
        };
        reader.readAsDataURL(file);
    });
}

bacaFileGambar(
    document.getElementById('fileSignatureUpload'),
    'previewSignatureUpload',
    'imgPreviewSignatureUpload',
    function (dataUrl) { base64SignatureUpload = dataUrl; }
);

bacaFileGambar(
    document.getElementById('fileStempelUpload'),
    'previewStempelUpload',
    'imgPreviewStempelUpload',
    function (dataUrl) { base64StempelUpload = dataUrl; }
);

// ===== BARU: Hapus preview upload sebelum diterapkan =====
document.getElementById('hapusPreviewSignatureUpload').addEventListener('click', function () {
    base64SignatureUpload = null;
    document.getElementById('fileSignatureUpload').value = '';
    document.getElementById('imgPreviewSignatureUpload').src = '';
    document.getElementById('previewSignatureUpload').classList.add('hidden');
});

document.getElementById('hapusPreviewStempelUpload').addEventListener('click', function () {
    base64StempelUpload = null;
    document.getElementById('fileStempelUpload').value = '';
    document.getElementById('imgPreviewStempelUpload').src = '';
    document.getElementById('previewStempelUpload').classList.add('hidden');
});

// Fungsi ini TIDAK diubah: upload memang boleh menampilkan
// signature dan/atau stempel sesuai gambar yang diunggah user.
function terapkanUpload() {
    if (!base64SignatureUpload && !base64StempelUpload) {
        alert('Silakan upload minimal satu gambar (tanda tangan atau stempel)!');
        return;
    }

    if (base64SignatureUpload) {
        document.getElementById('imgSignaturePreview').src = base64SignatureUpload;
        document.getElementById('input_signature_data').value = base64SignatureUpload;
        document.getElementById('dragSignature').classList.remove('hidden');
    }

    if (base64StempelUpload) {
        document.getElementById('imgStempelPreview').src = base64StempelUpload;
        document.getElementById('input_stempel_data').value = base64StempelUpload;
        document.getElementById('dragStempel').classList.remove('hidden');
    }

    ttdAktif = true;
    document.getElementById('labelTombolTtd').innerText = 'Tanda Tangan & Stempel Aktif — Geser Posisinya';
    tutupModalUpload();
}

// ===== BARU: Hapus TTD/Stempel yang SUDAH ditempel di atas dokumen =====
function perbaruiStatusTtdAktif() {
    const sigTersembunyi = document.getElementById('dragSignature').classList.contains('hidden');
    const stTersembunyi = document.getElementById('dragStempel').classList.contains('hidden');

    if (sigTersembunyi && stTersembunyi) {
        // Kalau keduanya sudah dihapus, matikan status TTD aktif & kosongkan data terkirim
        ttdAktif = false;
        document.getElementById('labelTombolTtd').innerText = 'Gambar Tanda Tangan & Stempel';
        document.getElementById('input_signature_data').value = '';
        document.getElementById('input_stempel_data').value = '';
    }
}

document.getElementById('hapusSignature').addEventListener('click', function (e) {
    e.stopPropagation(); // supaya tidak memicu drag saat tombol × diklik
    document.getElementById('dragSignature').classList.add('hidden');
    document.getElementById('input_signature_data').value = '';
    perbaruiStatusTtdAktif();
});

document.getElementById('hapusStempel').addEventListener('click', function (e) {
    e.stopPropagation();
    document.getElementById('dragStempel').classList.add('hidden');
    document.getElementById('input_stempel_data').value = '';
    perbaruiStatusTtdAktif();
});

// ===== Drag & Resize (dipakai oleh KEDUA sumber: gambar manual maupun upload) =====
function jadikanBisaDigeser(elemenId) {
    const el = document.getElementById(elemenId);
    const container = document.getElementById('pdfPageContainer');
    let sedangDrag = false;
    let offsetX = 0, offsetY = 0;

    el.addEventListener('mousedown', function (e) {
        if (e.target.id.startsWith('resize') || e.target.id.startsWith('hapus')) return;
        sedangDrag = true;
        offsetX = e.clientX - el.offsetLeft;
        offsetY = e.clientY - el.offsetTop;
    });

    document.addEventListener('mousemove', function (e) {
        if (!sedangDrag) return;
        let newX = e.clientX - offsetX;
        let newY = e.clientY - offsetY;
        newX = Math.max(0, Math.min(newX, container.offsetWidth - el.offsetWidth));
        newY = Math.max(0, Math.min(newY, container.offsetHeight - el.offsetHeight));
        el.style.left = newX + 'px';
        el.style.top = newY + 'px';
    });

    document.addEventListener('mouseup', function () {
        sedangDrag = false;
    });
}

jadikanBisaDigeser('dragSignature');
jadikanBisaDigeser('dragStempel');

function jadikanBisaResize(handleId, elemenId) {
    const handle = document.getElementById(handleId);
    const el = document.getElementById(elemenId);
    let sedangResize = false;

    handle.addEventListener('mousedown', function (e) {
        e.stopPropagation();
        sedangResize = true;
    });

    document.addEventListener('mousemove', function (e) {
        if (!sedangResize) return;
        const rect = el.getBoundingClientRect();
        const newWidth = Math.max(50, e.clientX - rect.left);
        const newHeight = Math.max(30, e.clientY - rect.top);
        el.style.width = newWidth + 'px';
        el.style.height = newHeight + 'px';
    });

    document.addEventListener('mouseup', function () {
        sedangResize = false;
    });
}

jadikanBisaResize('resizeSignature', 'dragSignature');
jadikanBisaResize('resizeStempel', 'dragStempel');

// ===== Hitung posisi persentase sebelum submit =====
document.getElementById('formDisposisi').addEventListener('submit', function (e) {
    if (!ttdAktif) return;

    const container = document.getElementById('pdfPageContainer');
    const cW = container.offsetWidth;
    const cH = container.offsetHeight;

    // BARU: hanya kirim posisi kalau elemen memang masih terlihat (belum dihapus)
    const sig = document.getElementById('dragSignature');
    if (!sig.classList.contains('hidden')) {
        document.getElementById('input_signature_x').value = (sig.offsetLeft / cW) * 100;
        document.getElementById('input_signature_y').value = (sig.offsetTop / cH) * 100;
        document.getElementById('input_signature_width').value = (sig.offsetWidth / cW) * 100;
        document.getElementById('input_signature_page').value = halamanSekarang;
    }

    const st = document.getElementById('dragStempel');
    if (!st.classList.contains('hidden')) {
        document.getElementById('input_stempel_x').value = (st.offsetLeft / cW) * 100;
        document.getElementById('input_stempel_y').value = (st.offsetTop / cH) * 100;
        document.getElementById('input_stempel_width').value = (st.offsetWidth / cW) * 100;
    }
});
</script>
@endif
@endsection