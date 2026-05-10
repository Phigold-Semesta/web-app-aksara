@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            {{-- Tombol Kembali Abu-abu Muda --}}
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
        </div>

        {{-- Preview Dokumen --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800 overflow-hidden h-full flex flex-col">
                {{-- Toolbar Header Sesuai Gambar --}}
                <div class="px-6 py-4 border-b border-emerald-50 dark:border-slate-800 flex justify-between items-center bg-white dark:bg-slate-900">
                    <div class="flex items-center gap-4">
                        <span class="text-emerald-800 dark:text-emerald-400 font-black uppercase text-[10px] tracking-[0.2em] flex items-center gap-2">
                            <i class="fas fa-file-pdf text-lg"></i> Preview Dokumen Digital
                        </span>
                    </div>
                    
                    <a href="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 text-xs font-bold hover:text-emerald-700 flex items-center gap-2 transition-colors">
                        Buka Layar Penuh <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>

                {{-- Toolbar Kontrol PDF (Hanya Visual Dekoratif agar mirip gambar UI) --}}
                <div class="bg-slate-50/50 dark:bg-slate-800/50 px-6 py-2 border-b border-emerald-50 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-6 text-slate-500 dark:text-slate-400">
                        <button class="hover:text-emerald-600 transition-colors"><i class="fas fa-list-ul"></i></button>
                        <button class="hover:text-emerald-600 transition-colors"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-white dark:bg-slate-900 border dark:border-slate-700 rounded-lg px-3 py-1 gap-4">
                            <button class="text-slate-400 hover:text-emerald-600"><i class="fas fa-minus text-xs"></i></button>
                            <span class="border-x px-4 py-0.5 text-sm font-bold text-slate-700 dark:text-slate-200">100%</span>
                            <button class="text-slate-400 hover:text-emerald-600"><i class="fas fa-plus text-xs"></i></button>
                        </div>
                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
                        <div class="flex items-center gap-2">
                            <div class="bg-white dark:bg-slate-900 border dark:border-slate-700 rounded-lg px-4 py-1 text-sm font-bold text-slate-700 dark:text-slate-200">1</div>
                            <span class="text-xs font-bold text-slate-400 italic">dari 2</span>
                        </div>
                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
                        <button class="text-slate-400 hover:text-emerald-600 transition-colors"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="flex items-center gap-6 text-slate-500 dark:text-slate-400">
                        <button class="hover:text-emerald-600 transition-colors"><i class="fas fa-search"></i></button>
                        <button class="hover:text-emerald-600 transition-colors"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>

                {{-- Area Preview --}}
                <div class="flex-grow bg-slate-200 dark:bg-slate-950 p-4 md:p-8 flex flex-col items-center overflow-y-auto custom-scrollbar" style="min-height: 700px;">
                    @php $extension = pathinfo($surat->file_surat, PATHINFO_EXTENSION); @endphp
                    
                    @if(strtolower($extension) == 'pdf')
                        {{-- Menggunakan Object untuk embedding yang lebih bersih --}}
                        <object data="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" class="w-full max-w-4xl shadow-2xl rounded-sm" style="height: 1000px;">
                            <iframe src="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}#toolbar=0" class="w-full h-full border-none"></iframe>
                        </object>
                    @else
                        <div class="flex items-center justify-center flex-grow p-4">
                            <img src="{{ asset('storage/dokumen_surat/' . $surat->file_surat) }}" 
                                 alt="Preview Surat" 
                                 class="max-w-full shadow-2xl rounded-sm object-contain border-[12px] border-white dark:border-slate-800">
                        </div>
                    @endif
                    
                    <p class="mt-8 text-[10px] italic text-emerald-600 dark:text-emerald-500 font-medium text-center">
                        Pastikan isi dokumen fisik sesuai dengan pratinjau digital di atas sebelum melakukan pemindahan lokasi
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling scrollbar area preview agar lebih mewah */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        @apply bg-slate-100 dark:bg-slate-900;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        @apply bg-emerald-200 dark:bg-emerald-900 rounded-full border-2 border-transparent;
    }
</style>
@endsection