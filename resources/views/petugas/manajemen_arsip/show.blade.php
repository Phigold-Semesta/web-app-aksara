@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="mb-10 flex justify-between items-center">
        <div>
            <a href="{{ route('petugas.manajemen_arsip.index') }}" class="text-emerald-600 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 tracking-tight">Detail Arsip Dokumen</h1>
        </div>
        <div class="flex gap-3">
            @if($arsip->status_retensi == 'Aktif')
                <span class="bg-emerald-100 text-emerald-700 px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest border border-emerald-200 shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Status: {{ $arsip->status_retensi }}
                </span>
            @else
                <span class="bg-red-100 text-red-700 px-6 py-2 rounded-full font-black text-xs uppercase tracking-widest border border-red-200 shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Status: {{ $arsip->status_retensi }}
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Sisi Kiri: Info Arsip --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-emerald-900 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-emerald-900/20 relative overflow-hidden">
                <i class="fas fa-box-archive absolute -right-10 -bottom-10 text-9xl opacity-10 rotate-12"></i>
                <h3 class="text-emerald-400 font-black uppercase text-xs tracking-[0.2em] mb-6">Informasi Penyimpanan</h3>
                
                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1">Lokasi Rak/Lemari</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400">
                                <i class="fas fa-map-location-dot"></i>
                            </div>
                            <p class="text-xl font-bold">{{ $arsip->lokasi_fisik }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1">Diarsipkan Pada</p>
                        <p class="text-lg font-bold">{{ \Carbon\Carbon::parse($arsip->tanggal_arsip)->format('d F Y') }}</p>
                    </div>
                    <div class="pt-6 border-t border-emerald-800">
                        <p class="text-emerald-500 text-[10px] font-bold uppercase mb-1 text-amber-400">Habis Masa Retensi</p>
                        <p class="text-2xl font-black text-amber-400">{{ \Carbon\Carbon::parse($arsip->masa_retensi)->format('d F Y') }}</p>
                        <p class="text-[10px] text-emerald-400 mt-1 italic opacity-70">
                            *{{ \Carbon\Carbon::parse($arsip->masa_retensi)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info Tambahan: Detail Surat Digital --}}
            <div class="bg-white rounded-[2.5rem] p-8 border border-emerald-50 shadow-sm">
                <h3 class="text-emerald-900 font-black uppercase text-xs tracking-[0.2em] mb-6 flex items-center gap-3">
                    <span class="w-8 h-1 bg-emerald-500 rounded-full"></span> Metadata Surat
                </h3>
                <div class="space-y-5">
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Nomor Surat</p>
                        <p class="text-emerald-950 font-bold break-words">{{ $arsip->surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Asal Instansi</p>
                        <p class="text-emerald-950 font-bold">{{ $arsip->surat->asal_instansi }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 text-[10px] font-bold uppercase mb-1">Perihal</p>
                        <p class="text-emerald-950 font-bold text-lg leading-tight">{{ $arsip->surat->perihal }}</p>
                    </div>
                    <div class="pt-4 border-t border-emerald-50">
                        <p class="text-emerald-400 text-xs italic font-medium">Digitalisasi oleh: <span class="text-emerald-950">{{ $arsip->surat->user->nama_lengkap ?? 'Sistem' }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Live Preview Dokumen (DISEMPURNAKAN) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] p-4 border border-emerald-50 shadow-2xl shadow-emerald-900/5 h-full flex flex-col">
                <div class="px-6 py-4 flex justify-between items-center border-b border-emerald-50 mb-4">
                    <h3 class="text-emerald-900 font-black uppercase text-xs tracking-[0.2em] flex items-center gap-3">
                        <i class="fas fa-file-pdf text-emerald-500 text-lg"></i> Preview Dokumen Digital
                    </h3>
                    <a href="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 font-bold text-xs flex items-center gap-2">
                        Buka Layar Penuh <i class="fas fa-external-link-alt text-[10px]"></i>
                    </a>
                </div>

                {{-- Kontainer Iframe Preview --}}
                <div class="flex-grow rounded-[1.5rem] overflow-hidden bg-emerald-50/50 border border-emerald-100 min-h-[600px] relative">
                    @if($arsip->surat->file_surat)
                        <iframe src="{{ asset('storage/dokumen_surat/' . $arsip->surat->file_surat) }}" class="w-full h-full border-none" style="min-height: 600px;"></iframe>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-emerald-300">
                            <i class="fas fa-file-circle-xmark text-6xl mb-4"></i>
                            <p class="font-bold uppercase tracking-widest text-xs">File tidak ditemukan</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-4 px-6 py-2">
                    <p class="text-[10px] text-emerald-400 italic text-center">
                        Pastikan isi dokumen fisik sesuai dengan pratinjau digital di atas sebelum melakukan pemindahan lokasi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection