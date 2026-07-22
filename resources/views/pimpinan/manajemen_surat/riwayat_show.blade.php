@extends('layouts.app')

@section('title', 'Detail Riwayat Surat')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('pimpinan.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight italic uppercase">Riwayat Disposisi Pimpinan</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Informasi lengkap & hasil disposisi dokumen #{{ $surat->nomor_surat }}</p>
        </div>
        <div class="flex gap-3">
            {{-- Status Surat Dinamis --}}
            <span class="bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 px-6 py-3 rounded-2xl font-black text-sm uppercase tracking-widest border border-emerald-200 dark:border-emerald-800 shadow-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ strtoupper($surat->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- KIRI: Panel Informasi (Digabung antara Surat, Disposisi, dan Arsip Fisik) --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Kartu Riwayat Disposisi (Tampil jika sudah didisposisi) --}}
            @if($surat->disposisi->count() > 0)
            <div class="bg-emerald-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-emerald-900/20 border border-emerald-800">
                <h3 class="text-emerald-400 font-black uppercase text-xs tracking-widest mb-6 pb-4 border-b border-emerald-800">Tindakan Pimpinan</h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-wider">Instruksi Diberikan</p>
                        <p class="text-white font-bold text-xl mt-1 leading-tight">
                            {{ $surat->disposisi->last()->instruksi_disposisi->nama_instruksi ?? 'Tidak Ada Instruksi Khusus' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-wider">Catatan Tambahan</p>
                        <div class="bg-emerald-800/50 p-4 rounded-xl mt-2 italic text-emerald-100 text-sm border border-emerald-700/50">
                            "{{ $surat->disposisi->last()->catatan_pimpinan ?? 'Tidak ada catatan yang dilampirkan.' }}"
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-500 uppercase tracking-wider">Tanggal Disposisi</p>
                        <p class="text-emerald-50 font-bold text-sm mt-1">
                            <i class="fas fa-calendar-check mr-1"></i> {{ \Carbon\Carbon::parse($surat->disposisi->last()->tanggal_disposisi)->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Kartu Metadata Surat --}}
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
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg text-[10px] font-black uppercase">
                                {{ $surat->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                            <span class="text-emerald-950 dark:text-emerald-50 font-bold text-sm">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-wider">Perihal</p>
                        <p class="text-emerald-950 dark:text-emerald-50 font-medium text-base leading-relaxed">{{ $surat->perihal }}</p>
                    </div>
                </div>
            </div>

            {{-- Kartu Penyimpanan Fisik (Tampil Jika Diarsipkan) --}}
            @if(strtolower($surat->status) === 'diarsipkan' && $surat->arsip)
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-[2.5rem] p-8 border border-blue-100 dark:border-blue-800/50">
                <h3 class="text-blue-900 dark:text-blue-100 font-black uppercase text-xs tracking-widest mb-4">Informasi Arsip Fisik</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-black text-blue-500 uppercase">Lokasi Rak/Lemari</p>
                        <p class="text-blue-950 dark:text-white font-bold text-base mt-1">
                            <i class="fas fa-archive text-blue-400 mr-2"></i> {{ $surat->arsip->lokasi_fisik }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-blue-500 uppercase">Masa Retensi</p>
                        <p class="text-blue-950 dark:text-white font-bold text-sm mt-1">
                            Sampai {{ \Carbon\Carbon::parse($surat->arsip->masa_retensi)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- KANAN: Preview Dokumen Digital (Aman dari 403 Forbidden) --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800 overflow-hidden h-full flex flex-col">
                <div class="px-6 py-4 border-b border-emerald-50 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <span class="text-emerald-800 dark:text-emerald-400 font-black uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                        <i class="fas fa-file-pdf text-lg"></i> Preview Dokumen Tersimpan
                    </span>

                    {{-- Solusi Jenius: Gunakan Rute Controller agar bebas blokir 403 Forbidden --}}
                    @if(!empty($surat->file_surat))
                        <a href="{{ route('pimpinan.manajemen_surat.tampilkan_dokumen', $surat->id_surat) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:text-emerald-700 flex items-center gap-2 transition-colors">
                            Buka Layar Penuh <i class="fas fa-external-link-alt"></i>
                        </a>
                    @endif
                </div>

                <div class="flex-grow bg-slate-200 dark:bg-slate-950 p-4 md:p-8 flex flex-col items-center overflow-y-auto custom-scrollbar" style="min-height: 800px;">
                    @if(!empty($surat->file_surat))
                        @php
                            $extension = pathinfo($surat->file_surat, PATHINFO_EXTENSION);
                        @endphp
                        
                        @if(strtolower($extension) == 'pdf')
                            <iframe src="{{ route('pimpinan.manajemen_surat.tampilkan_dokumen', $surat->id_surat) }}" class="w-full h-[1000px] shadow-2xl rounded-xl border-none"></iframe>
                        @else
                            <img src="{{ route('pimpinan.manajemen_surat.tampilkan_dokumen', $surat->id_surat) }}" class="max-w-full shadow-2xl rounded-xl object-contain border-[8px] border-white dark:border-slate-800">
                        @endif
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-emerald-300 dark:text-slate-600 gap-3 mt-32">
                            <i class="fas fa-file-circle-exclamation text-6xl"></i>
                            <p class="font-bold text-emerald-800 dark:text-emerald-200 text-lg">Dokumen digital tidak tersedia.</p>
                            <p class="text-sm text-emerald-600 dark:text-slate-400">File belum diunggah atau tidak ditemukan di server.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { @apply bg-slate-100 dark:bg-slate-900; }
    .custom-scrollbar::-webkit-scrollbar-thumb { @apply bg-emerald-200 dark:bg-emerald-900 rounded-full border-2 border-transparent; }
</style>
@endsection