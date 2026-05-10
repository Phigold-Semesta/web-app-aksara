{{-- petugas/manajemen_arsip/show.blade.php --}}
@extends('layouts.app')

@section('content')
{{-- Menghilangkan bg-slate-950 yang kaku dan menggantinya dengan transparan/emerald yang sangat halus --}}
<div class="p-8 transition-colors duration-300 min-h-screen bg-transparent">
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('petugas.manajemen_arsip.index') }}" class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Detail Arsip Dokumen</h1>
        </div>
        <div class="flex gap-3">
            @if($arsip->status_retensi == 'Aktif')
                <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest border border-emerald-200 dark:border-emerald-800 shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Status: {{ $arsip->status_retensi }}
                </span>
            @else
                <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest border border-red-200 dark:border-red-800 shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Status: {{ $arsip->status_retensi }}
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Sisi Kiri: Info Arsip --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Kartu Lokasi Fisik --}}
            <div class="bg-emerald-900 dark:bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 text-white shadow-2xl shadow-emerald-900/20 relative overflow-hidden border border-transparent dark:border-emerald-900/30 transition-all duration-300">
                <i class="fas fa-box-archive absolute -right-10 -bottom-10 text-9xl opacity-10 rotate-12"></i>
                <h3 class="text-emerald-400 font-black uppercase text-xs tracking-[0.2em] mb-6">Informasi Penyimpanan</h3>
                
                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1">Lokasi Rak/Lemari</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-white/5">
                                <i class="fas fa-map-location-dot"></i>
                            </div>
                            <p class="text-xl font-bold">{{ $arsip->lokasi_fisik }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1">Diarsipkan Pada</p>
                        <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="pt-6 border-t border-emerald-800 dark:border-emerald-800/50">
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1 text-amber-400">Habis Masa Retensi</p>
                        <p class="text-2xl font-black text-amber-400">{{ \Carbon\Carbon::parse($arsip->masa_retensi)->translatedFormat('d F Y') }}</p>
                        <p class="text-[10px] text-emerald-400 mt-1 italic opacity-70">
                            *{{ \Carbon\Carbon::parse($arsip->masa_retensi)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Metadata Surat --}}
            <div class="bg-white dark:bg-slate-900/50 backdrop-blur-md rounded-[2.5rem] p-8 border border-emerald-50 dark:border-emerald-900/20 shadow-sm transition-all duration-300">
                <h3 class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-[0.2em] mb-6 flex items-center gap-3">
                    <span class="w-8 h-1 bg-emerald-500 rounded-full"></span> Metadata Surat
                </h3>
                <div class="space-y-5">
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Nomor Surat</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold break-words">{{ $arsip->surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Asal Instansi</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold">{{ $arsip->surat->asal_instansi }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Perihal</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold text-lg leading-tight">{{ $arsip->surat->perihal }}</p>
                    </div>
                    <div class="pt-4 border-t border-emerald-50 dark:border-emerald-900/20">
                        <p class="text-emerald-400 text-xs italic font-medium">Digitalisasi oleh: <span class="text-emerald-950 dark:text-emerald-200">{{ $arsip->surat->user->nama_lengkap ?? 'Sistem' }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Preview Dokumen --}}
        <div class="lg:col-span-2 h-full">
            <div class="bg-white dark:bg-slate-900/50 backdrop-blur-md rounded-[2.5rem] p-4 border border-emerald-50 dark:border-emerald-900/20 shadow-2xl shadow-emerald-900/5 h-full flex flex-col transition-all duration-300 relative">
                
                {{-- Header Preview Tetap Ada --}}
                <div class="px-6 py-3 flex justify-between items-center border-b border-emerald-50 dark:border-emerald-900/20 mb-4">
                    <h3 class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-widest flex items-center gap-2">
                        <i class="fas fa-file-pdf text-emerald-500 text-base"></i> Preview Dokumen Digital
                    </h3>
                    <a href="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 font-bold text-[10px] flex items-center gap-2 transition-colors uppercase">
                        Buka Layar Penuh <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>

                {{-- Area Preview Utama dengan Toolbar Adobe yang Melayang --}}
                <div class="flex-grow rounded-[1.5rem] overflow-hidden bg-slate-100 dark:bg-slate-950/40 border border-emerald-100 dark:border-emerald-900/20 min-h-[650px] relative transition-all group">
                    
                    {{-- FLOATING ADOBE TOOLBAR: Posisi Persis Sesuai Gambar Unggahan --}}
                    <div class="absolute top-4 left-1/2 -translate-x-1/2 z-20 flex items-center bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-xl px-4 py-2 shadow-2xl border border-slate-200 dark:border-emerald-900/40 gap-6 opacity-90 group-hover:opacity-100 transition-opacity duration-500">
                        {{-- Group 1: Sidebar & Menu --}}
                        <div class="flex items-center gap-4 border-r dark:border-slate-700 pr-4">
                            <button class="text-slate-500 dark:text-emerald-400 hover:text-emerald-600 transition-colors"><i class="fas fa-list-ul"></i></button>
                            <button class="text-slate-300 dark:text-slate-600 cursor-not-allowed"><i class="fas fa-ellipsis-h"></i></button>
                        </div>
                        
                        {{-- Group 2: Zoom & Navigasi Halaman --}}
                        <div class="flex items-center gap-4 border-r dark:border-slate-700 pr-4">
                            <button class="text-slate-500 dark:text-emerald-400 hover:text-emerald-600 transition-colors"><i class="fas fa-minus text-xs"></i></button>
                            <button class="text-slate-500 dark:text-emerald-400 hover:text-emerald-600 transition-colors"><i class="fas fa-plus text-xs"></i></button>
                            <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-700"></div>
                            <div class="flex items-center gap-2">
                                <div class="bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded border border-slate-200 dark:border-slate-700 text-xs font-black text-emerald-700 dark:text-emerald-400">1</div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter whitespace-nowrap">of 2</span>
                            </div>
                            <div class="h-6 w-[1px] bg-slate-200 dark:bg-slate-700"></div>
                            <button class="text-slate-300 dark:text-slate-600 cursor-not-allowed"><i class="fas fa-ellipsis-h"></i></button>
                        </div>

                        {{-- Group 3: Search & Options --}}
                        <div class="flex items-center gap-4">
                            <button class="text-slate-500 dark:text-emerald-400 hover:text-emerald-600 transition-colors"><i class="fas fa-search text-xs"></i></button>
                            <button class="text-slate-500 dark:text-emerald-400 hover:text-emerald-600 transition-colors"><i class="fas fa-ellipsis-v text-xs"></i></button>
                        </div>
                    </div>

                    {{-- Isi Dokumen --}}
                    @if($arsip->surat->file_surat)
                        @php $extension = pathinfo($arsip->surat->file_surat, PATHINFO_EXTENSION); @endphp
                        
                        @if(strtolower($extension) == 'pdf')
                            <iframe src="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}#toolbar=0&navpanes=0&scrollbar=0" 
                                    class="w-full h-full border-none" 
                                    style="min-height: 650px;">
                            </iframe>
                        @else
                            <div class="flex items-center justify-center h-full p-12 bg-transparent overflow-auto">
                                <img src="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}" 
                                     alt="Preview Surat" 
                                     class="max-w-[90%] shadow-2xl rounded-sm object-contain border border-slate-200 dark:border-slate-800 transition-all bg-white">
                            </div>
                        @endif
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-emerald-300 dark:text-emerald-800/50">
                            <i class="fas fa-file-circle-xmark text-6xl mb-4"></i>
                            <p class="font-bold uppercase tracking-widest text-xs">File tidak ditemukan</p>
                        </div>
                    @endif
                </div>
                
                {{-- Footer --}}
                <div class="mt-4 px-6 py-2">
                    <p class="text-[10px] text-emerald-500/60 dark:text-emerald-600/70 italic text-center uppercase tracking-widest leading-relaxed">
                        Pastikan isi dokumen fisik sesuai dengan pratinjau digital di atas sebelum melakukan pemindahan lokasi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Transisi Halus untuk Dark Mode */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }

    /* Efek Glassmorphism halus untuk Dark Mode */
    .dark .backdrop-blur-md {
        backdrop-filter: blur(12px);
    }
</style>
@endsection