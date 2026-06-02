@extends('layouts.app')

@section('title', 'Detail Arsip - ' . $arsip->surat->nomor_surat)

@section('content')
<div class="p-8 min-h-screen bg-transparent transition-colors duration-300">
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('pimpinan.monitoring_arsip.index') }}" class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Monitoring
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Detail Arsip Surat</h1>
        </div>
        
        {{-- Status Retensi (Tanpa Tombol Download) --}}
        <div>
            <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest border border-emerald-200 dark:border-emerald-800 shadow-sm">
                <i class="fas fa-check-circle mr-1"></i> Status: {{ $arsip->status_retensi }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Sisi Kiri: Info Arsip & Metadata --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Kartu Lokasi Fisik --}}
            <div class="bg-emerald-900 dark:bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] p-8 text-white shadow-2xl shadow-emerald-900/20 relative overflow-hidden border border-transparent dark:border-emerald-900/30">
                <i class="fas fa-box-archive absolute -right-10 -bottom-10 text-9xl opacity-10 rotate-12"></i>
                <h3 class="text-emerald-400 font-black uppercase text-xs tracking-[0.2em] mb-6">Informasi Penyimpanan</h3>
                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1">Lokasi Rak/Lemari</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400 border border-white/5">
                                <i class="fas fa-map-location-dot"></i>
                            </div>
                            <p class="text-xl font-bold">{{ $arsip->lokasi_fisik }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1">Diarsipkan Pada</p>
                        <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="pt-6 border-t border-emerald-800">
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1 text-amber-400">Habis Masa Retensi</p>
                        <p class="text-2xl font-black text-amber-400">{{ \Carbon\Carbon::parse($arsip->masa_retensi)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Metadata Surat --}}
            <div class="bg-white dark:bg-slate-900/50 backdrop-blur-md rounded-[2.5rem] p-8 border border-emerald-50 dark:border-emerald-900/20 shadow-sm">
                <h3 class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-[0.2em] mb-6 flex items-center gap-3">
                    <span class="w-8 h-1 bg-emerald-500 rounded-full"></span> Metadata Surat
                </h3>
                <div class="space-y-5 text-sm">
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Nomor Surat</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold">{{ $arsip->surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Perihal</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold leading-tight">{{ $arsip->surat->perihal }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Preview Dokumen --}}
        <div class="lg:col-span-2 h-full">
            <div class="bg-white dark:bg-slate-900/50 backdrop-blur-md rounded-[2.5rem] p-4 border border-emerald-50 dark:border-emerald-900/20 shadow-2xl shadow-emerald-900/5 h-full flex flex-col transition-all">
                <div class="px-6 py-3 flex justify-between items-center border-b border-emerald-50 dark:border-emerald-900/20 mb-4">
                    <h3 class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-widest flex items-center gap-2">
                        <i class="fas fa-file-pdf text-emerald-500 text-base"></i> Pratinjau Dokumen
                    </h3>
                </div>
                
                {{-- Area Preview --}}
                <div class="flex-grow rounded-[1.5rem] overflow-hidden bg-slate-100 dark:bg-slate-950/40 border border-emerald-100 dark:border-emerald-900/20 min-h-[600px] relative">
                    @if($arsip->surat->file_surat)
                        <iframe src="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}#toolbar=0&navpanes=0&scrollbar=0" 
                                class="w-full h-full border-none" style="min-height: 600px;"></iframe>
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-emerald-300">
                            <p class="font-bold uppercase tracking-widest text-xs">File Tidak Tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection