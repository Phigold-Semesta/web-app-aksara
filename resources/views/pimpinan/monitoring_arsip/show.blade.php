@extends('layouts.app')

@section('title', 'Detail Surat')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header & Tombol Kembali --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <a href="{{ route('pimpinan.monitoring_arsip.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight uppercase italic">Detail Arsip (Pimpinan)</h1>
        </div>
        
        {{-- Status Badge --}}
        <div class="flex gap-3">
            @if(isset($arsip->status_retensi) && $arsip->status_retensi == 'Aktif')
                <span class="bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest border border-emerald-200 dark:border-emerald-800 shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Status: Aktif
                </span>
            @else
                <span class="bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-6 py-3 rounded-full font-black text-xs uppercase tracking-widest border border-red-200 dark:border-red-800 shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Status: Inaktif
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
                            <span class="text-lg font-bold">{{ $arsip->lokasi_fisik ?? 'Tidak ditentukan' }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Diarsipkan Pada</p>
                        <p class="text-lg font-bold mt-1">
                            {{ $arsip->tanggal_arsip ? $arsip->tanggal_arsip->translatedFormat('d F Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="pt-4 border-t border-emerald-800">
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Habis Masa Retensi</p>
                        @if(!empty($arsip->masa_retensi))
                            <p class="text-2xl font-black text-amber-400 mt-1">
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
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Perihal</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $arsip->surat->perihal ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: Preview Dokumen --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5 h-[800px] flex flex-col">
            <div class="px-6 py-4 border-b border-emerald-50 dark:border-slate-800 flex justify-between items-center mb-4">
                <span class="text-emerald-800 dark:text-emerald-400 font-black uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                    <i class="fas fa-file-pdf text-lg"></i> Preview Dokumen Digital
                </span>
                <a href="{{ route('pimpinan.manajemen_surat.tampilkan_dokumen', $arsip->surat->id_surat ?? 0) }}" target="_blank" class="text-emerald-600 font-bold text-xs hover:underline uppercase">
                    BUKA LAYAR PENUH <i class="fas fa-external-link-alt ml-1"></i>
                </a>
            </div>
            
            <div class="flex-grow overflow-hidden rounded-3xl bg-slate-200 dark:bg-slate-950">
                <iframe 
                    src="{{ route('pimpinan.manajemen_surat.tampilkan_dokumen', $arsip->surat->id_surat ?? 0) }}" 
                    class="w-full h-full" 
                    frameborder="0">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection