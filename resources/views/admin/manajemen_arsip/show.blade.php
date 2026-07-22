@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300 dark:bg-emerald-950/20">
    {{-- Header & Action Buttons --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <a href="{{ route('admin.manajemen_arsip.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight">Detail Arsip Digital</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Informasi lengkap arsip dokumen #{{ $arsip->id_arsip }}</p>
        </div>
        
        <div class="flex gap-3">
            @if($arsip->status_retensi == 'Aktif')
                <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest border border-emerald-200 dark:border-emerald-800 shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> {{ $arsip->status_retensi }}
                </span>
            @else
                <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest border border-red-200 dark:border-red-800 shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ $arsip->status_retensi }}
                </span>
            @endif
        </div>
    </div>

    {{-- Layout Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KIRI: Informasi Penyimpanan & Metadata --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Informasi Penyimpanan (Luxury Card) --}}
            <div class="bg-emerald-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-emerald-900/20 relative overflow-hidden">
                <i class="fas fa-box-archive absolute -right-10 -bottom-10 text-9xl opacity-10"></i>
                <p class="text-emerald-400 font-black uppercase text-xs tracking-widest mb-6">Informasi Penyimpanan</p>
                
                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Lokasi Rak/Lemari</p>
                        <div class="flex items-center gap-3 mt-1">
                            <i class="fas fa-archive text-emerald-400"></i>
                            <span class="text-lg font-bold">{{ $arsip->lokasi_fisik }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Diarsipkan Pada</p>
                        <p class="text-lg font-bold mt-1">
                            {{ $arsip->tanggal_arsip ? $arsip->tanggal_arsip->translatedFormat('d F Y') : 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Habis Masa Retensi</p>
                        @if(!empty($arsip->masa_retensi))
                            <p class="text-lg font-bold text-emerald-300 mt-1">
                                {{ $arsip->masa_retensi->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[10px] text-emerald-400 italic opacity-70 mt-1">
                                *{{ $arsip->masa_retensi->isPast() ? 'Sudah Kadaluarsa' : $arsip->masa_retensi->diffForHumans() }}
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
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $arsip->surat->nomor_surat ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Asal Instansi</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $arsip->surat->asal_instansi ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Kategori Surat</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $arsip->surat->kategori->nama_kategori ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Perihal</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $arsip->surat->perihal ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Preview Dokumen --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5 h-[800px] flex flex-col">
            <div class="flex justify-between items-center mb-4 px-2">
                <p class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest">Preview Dokumen Digital</p>
                @if($arsip->surat && $arsip->surat->file_surat)
                    <a href="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}" target="_blank" class="text-emerald-600 font-bold text-xs hover:underline uppercase">
                        BUKA LAYAR PENUH <i class="fas fa-external-link-alt ml-1"></i>
                    </a>
                @endif
            </div>
            
            @if($arsip->surat && $arsip->surat->file_surat)
                @php 
                    $filePath = 'storage/dokumen_surat/' . $arsip->surat->file_surat;
                    $extension = pathinfo($filePath, PATHINFO_EXTENSION); 
                @endphp
                
                @if(strtolower($extension) == 'pdf')
                    <iframe src="{{ asset($filePath) }}" class="w-full h-full rounded-3xl" frameborder="0"></iframe>
                @else
                    <div class="w-full h-full rounded-3xl bg-gray-50 flex items-center justify-center overflow-auto p-4">
                        <img src="{{ asset($filePath) }}" class="max-h-full object-contain rounded-xl shadow-lg" alt="Dokumen">
                    </div>
                @endif
            @else
                <div class="w-full h-full rounded-3xl bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                    <i class="fas fa-file-excel text-5xl mb-4"></i>
                    <p class="font-bold">Dokumen tidak ditemukan</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection