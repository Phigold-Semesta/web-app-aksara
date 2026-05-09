@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header --}}
    <div class="mb-10 flex justify-between items-start">
        <div>
            <a href="{{ route('petugas.manajemen_surat.index') }}" class="group flex items-center text-emerald-600 dark:text-emerald-400 font-semibold mb-4 transition-all">
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Daftar Surat
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Detail Arsip Digital</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Informasi lengkap dokumen #{{ $surat->id_surat }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('petugas.manajemen_surat.edit', $surat->id_surat) }}" 
               class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg transition-all flex items-center gap-2" title="Edit Data">
                <i class="fas fa-edit"></i> EDIT
            </a>
            <form action="{{ route('petugas.teruskan_pimpinan', $surat->id_surat) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg transition-all flex items-center gap-2" onclick="return confirm('Teruskan ke Pimpinan?')">
                    <i class="fas fa-paper-plane"></i> TERUSKAN
                </button>
            </form>
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
                            <span class="text-emerald-950 dark:text-emerald-50 font-bold text-sm">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</span>
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
        </div>

        {{-- Preview Dokumen --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800 overflow-hidden h-full min-h-[600px] flex flex-col">
                <div class="p-6 border-b border-emerald-50 dark:border-slate-800 flex justify-between items-center bg-emerald-50/30 dark:bg-slate-800/30">
                    <span class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest">Preview Dokumen Digital</span>
                    
                    {{-- Tombol Buka Tab Baru --}}
                    <a href="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:underline">
                        <i class="fas fa-external-link-alt mr-1"></i> Buka Fullscreen
                    </a>
                </div>
                <div class="flex-grow bg-slate-100 dark:bg-slate-950 flex flex-col">
                    @php $extension = pathinfo($surat->file_surat, PATHINFO_EXTENSION); @endphp
                    
                    @if(strtolower($extension) == 'pdf')
                        {{-- PERBAIKAN: Menambahkan parameter #toolbar=0 dan styling height 100% agar tidak crash/inception --}}
                        <iframe 
                            src="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}#toolbar=0" 
                            class="w-full flex-grow border-none" 
                            style="min-height: 600px;"
                            loading="lazy">
                        </iframe>
                    @else
                        <div class="flex items-center justify-center flex-grow p-10">
                            <img src="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}" 
                                 alt="Preview Surat" 
                                 class="max-w-full max-h-[550px] rounded-xl shadow-2xl object-contain border-4 border-white dark:border-slate-800">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection