@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header & Action Buttons --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('petugas.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Detail Arsip Digital</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Informasi lengkap dokumen #{{ $surat->id_surat }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('petugas.manajemen_surat.edit', $surat->id_surat) }}" 
               class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg transition-all flex items-center gap-2" title="Edit Data">
                <i class="fas fa-edit"></i> EDIT
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Kartu Informasi --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800">
                <h3 class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-6 pb-4 border-b border-emerald-50 dark:border-slate-800">Metadata Surat</h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Nomor Surat</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold text-lg leading-tight">{{ $surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Asal Instansi</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold text-lg leading-tight">{{ $surat->asal_instansi }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Kategori & Tanggal</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg text-[10px] font-black uppercase">
                                {{ $surat->kategori->nama_kategori }}
                            </span>
                            <span class="text-emerald-950 dark:text-emerald-50 font-bold text-sm">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Perihal</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-medium text-base leading-relaxed">{{ $surat->perihal }}</p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-emerald-50 dark:border-slate-800">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider mb-2">Status Alur</p>
                        @if($surat->status == 'pending')
                            <span class="inline-flex items-center gap-2 text-orange-500 font-black text-xs uppercase">
                                <span class="w-2 h-2 bg-orange-500 rounded-full animate-ping"></span> Menunggu Verifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 text-blue-500 font-black text-xs uppercase">
                                <i class="fas fa-check-circle"></i> {{ strtoupper($surat->status) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- [TAMBAHAN FITUR JENIUS] Tombol Buka Modal Stempel Nomor Surat --}}
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5">
                <p class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-4">Pengaturan Stempel</p>
                <button type="button" onclick="bukaModalNomor()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white p-4 rounded-2xl font-black uppercase text-sm shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-stamp"></i> Atur & Tempel Nomor Surat
                </button>
            </div>
        </div>

        {{-- Preview Dokumen --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800 overflow-hidden h-full flex flex-col">
                {{-- Toolbar Header --}}
                <div class="px-6 py-4 border-b border-emerald-50 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <div class="flex items-center gap-3">
                        <span class="text-emerald-800 dark:text-emerald-400 font-black uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                            <i class="fas fa-file-pdf text-lg"></i> Preview Dokumen Digital
                        </span>
                        <span id="navHalaman" class="text-xs font-bold text-emerald-600"></span>
                    </div>
                    <a href="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:text-emerald-700 flex items-center gap-2 transition-colors">
                        Buka Layar Penuh <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>

                {{-- Area Preview (Disesuaikan menggunakan PDF.js agar bisa mendukung drag & drop stempel teks secara visual) --}}
                <div class="flex-grow bg-slate-200 dark:bg-slate-950 p-4 md:p-8 flex flex-col items-center overflow-y-auto custom-scrollbar" style="min-height: 700px;">
                    @php 
                        $filePath = 'storage/dokumen_surat/' . $surat->file_surat;
                        $extension = pathinfo($filePath, PATHINFO_EXTENSION); 
                        $urlPdf = asset($filePath);
                    @endphp
                    
                    @if(strtolower($extension) == 'pdf')
                        <div id="pdfViewerWrapper" class="relative w-full overflow-auto rounded-3xl bg-gray-100 dark:bg-slate-950 flex justify-center">
                            <div id="pdfPageContainer" class="relative mx-auto shadow-2xl" style="width: fit-content;">
                                <canvas id="pdfCanvas"></canvas>

                                {{-- Kotak Teks Nomor Surat Eksklusif (Draggable) --}}
                                <div id="dragNomorSurat" class="hidden absolute cursor-move border-2 border-dashed border-emerald-500 bg-white/95 dark:bg-slate-900/95 px-4 py-3 rounded-xl shadow-2xl z-30" style="top: 20px; left: 20px; min-width: 180px;">
                                    <button type="button" onclick="sembunyikanKotakNomor()" class="absolute -top-3 -left-3 w-6 h-6 bg-red-600 hover:bg-red-700 text-white rounded-full text-xs font-black flex items-center justify-center z-40 shadow-md">×</button>
                                    <div class="pointer-events-none select-none">
                                        <p class="text-[8px] font-black text-emerald-600 uppercase tracking-widest">PREVIEW STEMPEL NOMOR</p>
                                        <p id="previewTeksNomor" class="text-xs font-extrabold text-emerald-950 dark:text-emerald-100 mt-0.5">{{ $surat->nomor_surat }}</p>
                                    </div>
                                    <div class="mt-3 text-right">
                                        <button type="button" onclick="simpanKeServer()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-black uppercase shadow-md transition">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-full overflow-auto p-4 flex items-center justify-center">
                            <img src="{{ asset($filePath) }}" class="max-w-full shadow-2xl rounded-sm object-contain" alt="Dokumen">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KELOLA NOMOR SURAT --}}
<div id="modalNomorSurat" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl w-full max-w-md shadow-2xl border border-emerald-100 dark:border-slate-800">
        <h3 class="font-black text-lg mb-2 text-emerald-950 dark:text-white uppercase italic">Kelola Nomor Surat</h3>
        <p class="text-xs text-slate-500 mb-6">Ubah atau edit teks nomor surat di bawah ini sebelum ditempelkan secara permanen ke dokumen PDF.</p>
        
        <form id="formStempelModal" action="{{ route('petugas.manajemen_surat.stempel_nomor', $surat->id_surat) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-black text-emerald-900 dark:text-emerald-100 uppercase mb-2">Teks Nomor Surat</label>
                    <input type="text" id="inputNomorSuratModal" name="nomor_surat_baru" value="{{ $surat->nomor_surat }}" required class="w-full px-4 py-3 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-xl font-bold dark:text-white outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                
                {{-- Hidden Koordinat untuk dikirim ke Backend --}}
                <input type="hidden" name="stempel_x" id="modal_stempel_x" value="10">
                <input type="hidden" name="stempel_y" id="modal_stempel_y" value="10">
                <input type="hidden" name="stempel_page" id="modal_stempel_page" value="1">

                <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-2xl border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 font-medium">
                    <i class="fas fa-info-circle mr-1"></i> Klik "Atur Posisi di PDF" untuk menggeser letak nomor surat pada lembar dokumen di sebelah kanan.
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="aktifkanModeGeser()" class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs flex-1 transition shadow-lg">
                        Atur Posisi di PDF
                    </button>
                    <button type="button" onclick="tutupModalNomor()" class="px-4 py-3 bg-gray-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold text-xs transition">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if(strtolower($extension) == 'pdf')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const urlPdf = @json($urlPdf);
const canvas = document.getElementById('pdfCanvas');
const ctx = canvas.getContext('2d');
let pdfDoc = null;
let halamanSekarang = 1;

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

// Kontrol Modal & Preview Draggable
const modalNomor = document.getElementById('modalNomorSurat');
const dragNomorSurat = document.getElementById('dragNomorSurat');

function bukaModalNomor() {
    modalNomor.classList.remove('hidden');
}

function tutupModalNomor() {
    modalNomor.classList.add('hidden');
}

function sembunyikanKotakNomor() {
    dragNomorSurat.classList.add('hidden');
}

function aktifkanModeGeser() {
    const teksInput = document.getElementById('inputNomorSuratModal').value;
    if(!teksInput) {
        alert('Nomor surat tidak boleh kosong!');
        return;
    }
    document.getElementById('previewTeksNomor').innerText = teksInput;
    tutupModalNomor();
    dragNomorSurat.classList.remove('hidden');
}

// Logika Drag & Drop Kotak Nomor Surat
const container = document.getElementById('pdfPageContainer');
let sedangDrag = false;
let offsetX = 0, offsetY = 0;

dragNomorSurat.addEventListener('mousedown', function (e) {
    if (e.target.tagName === 'BUTTON') return;
    sedangDrag = true;
    offsetX = e.clientX - dragNomorSurat.offsetLeft;
    offsetY = e.clientY - dragNomorSurat.offsetTop;
});

document.addEventListener('mousemove', function (e) {
    if (!sedangDrag) return;
    let newX = e.clientX - offsetX;
    let newY = e.clientY - offsetY;
    newX = Math.max(0, Math.min(newX, container.offsetWidth - dragNomorSurat.offsetWidth));
    newY = Math.max(0, Math.min(newY, container.offsetHeight - dragNomorSurat.offsetHeight));
    dragNomorSurat.style.left = newX + 'px';
    dragNomorSurat.style.top = newY + 'px';
});

document.addEventListener('mouseup', function () {
    sedangDrag = false;
});

// Eksekusi Simpan ke Backend
function simpanKeServer() {
    const cW = container.offsetWidth;
    const cH = container.offsetHeight;

    const posX = (dragNomorSurat.offsetLeft / cW) * 100;
    const posY = (dragNomorSurat.offsetTop / cH) * 100;

    document.getElementById('modal_stempel_x').value = posX;
    document.getElementById('modal_stempel_y').value = posY;
    document.getElementById('modal_stempel_page').value = halamanSekarang;

    document.getElementById('formStempelModal').submit();
}
</script>
@endif

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { @apply bg-slate-100 dark:bg-slate-900; }
    .custom-scrollbar::-webkit-scrollbar-thumb { @apply bg-emerald-200 dark:bg-emerald-900 rounded-full border-2 border-transparent; }
</style>
@endsection