@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header & Action Buttons --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('pimpinan.monitoring_arsip.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Detail Arsip Digital</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Informasi lengkap dokumen #{{ $arsip->surat->id_surat ?? 'N/A' }}</p>
        </div>

        {{-- Action Buttons & Status Badge --}}
        <div class="flex flex-wrap items-center gap-3">
           
            
            {{-- Status Badge (Aktif/Inaktif) --}}
            @if(isset($arsip->status_retensi) && $arsip->status_retensi == 'Aktif')
                <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest border border-emerald-200 dark:border-emerald-800 shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Aktif
                </span>
            @else
                <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest border border-red-200 dark:border-red-800 shadow-sm">
                    <i class="fas fa-times-circle mr-1"></i> Inaktif
                </span>
            @endif
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
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold text-lg leading-tight">{{ $arsip->surat->nomor_surat ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Asal Instansi</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-bold text-lg leading-tight">{{ $arsip->surat->asal_instansi ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Kategori & Tanggal</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg text-[10px] font-black uppercase">
                                {{ $arsip->surat->kategori->nama_kategori ?? 'N/A' }}
                            </span>
                            <span class="text-emerald-950 dark:text-emerald-50 font-bold text-sm">
                                {{ isset($arsip->surat->tanggal_surat) ? \Carbon\Carbon::parse($arsip->surat->tanggal_surat)->translatedFormat('d F Y') : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Perihal</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-medium text-base leading-relaxed">{{ $arsip->surat->perihal ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Preview Dokumen --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800 overflow-hidden h-full flex flex-col">
                {{-- Toolbar Header --}}
                <div class="px-6 py-4 border-b border-emerald-50 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <span class="text-emerald-800 dark:text-emerald-400 font-black uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                        <i class="fas fa-file-pdf text-lg"></i> Preview Dokumen Digital
                    </span>
                    @if(isset($arsip->surat->file_surat))
                    <a href="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:text-emerald-700 flex items-center gap-2 transition-colors uppercase">
                        Buka Layar Penuh <i class="fas fa-external-link-alt"></i>
                    </a>
                    @endif
                </div>

                {{-- Area Preview --}}
                <div class="flex-grow bg-slate-200 dark:bg-slate-950 flex flex-col items-center overflow-hidden" style="min-height: 700px;">
                    @if(isset($arsip->surat->file_surat))
                        @php 
                            $filePath = 'storage/dokumen_surat/' . $arsip->surat->file_surat;
                            $extension = pathinfo($filePath, PATHINFO_EXTENSION); 
                        @endphp
                        
                        @if(strtolower($extension) == 'pdf')
                            <iframe src="{{ asset($filePath) }}" class="w-full h-full" frameborder="0"></iframe>
                        @else
                            <div class="w-full h-full overflow-auto p-4 flex items-center justify-center">
                                <img src="{{ asset($filePath) }}" class="max-w-full shadow-2xl rounded-sm object-contain" alt="Dokumen">
                            </div>
                        @endif
                    @else
                        <div class="flex items-center justify-center h-full text-emerald-400 font-bold">
                            File Tidak Tersedia
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection