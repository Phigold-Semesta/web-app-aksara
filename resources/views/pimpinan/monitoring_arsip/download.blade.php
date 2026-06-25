@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300">
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <a href="{{ route('pimpinan.monitoring_arsip.show', $arsip->id_arsip) }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-8 gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Detail
        </a>

        {{-- Card Konfirmasi Download --}}
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 shadow-2xl border border-emerald-50 dark:border-slate-800 text-center">
            <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-file-download text-4xl"></i>
            </div>
            
            <h1 class="text-2xl font-black text-emerald-950 dark:text-white mb-2">Siap Mengunduh?</h1>
            <p class="text-gray-500 mb-8">Anda akan mengunduh dokumen: <br> 
               <span class="font-bold text-emerald-700">{{ $arsip->surat->nomor_surat }}</span>
            </p>

            <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl mb-8 text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase">Nama File</span>
                    <span class="text-sm font-bold text-emerald-950 dark:text-emerald-50">{{ $arsip->surat->file_surat }}</span>
                </div>
            </div>

            <a href="{{ route('pimpinan.monitoring_arsip.proses_download', $arsip->id_arsip) }}" 
               class="w-full block bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-lg shadow-emerald-600/30 transition-all">
                <i class="fas fa-download mr-2"></i> Unduh Dokumen Sekarang
            </a>
        </div>
    </div>
</div>
@endsection