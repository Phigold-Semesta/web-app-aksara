@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300">
    {{-- Header & Tombol Kembali --}}
    <div class="mb-10">
        <a href="{{ route('pimpinan.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight uppercase italic">Detail Arsip (Pimpinan)</h1>
    </div>

    {{-- Layout Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KIRI: Informasi Penyimpanan & Metadata --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Informasi Penyimpanan (Luxury Card) --}}
            <div class="bg-emerald-900 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-emerald-900/20">
                <p class="text-emerald-400 font-black uppercase text-xs tracking-widest mb-6">Informasi Penyimpanan</p>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Lokasi Rak/Lemari</p>
                        <div class="flex items-center gap-3 mt-1">
                            <i class="fas fa-archive text-emerald-400"></i>
                            <span class="text-lg font-bold">{{ $surat->arsip->lokasi_fisik ?? 'Tidak ditentukan' }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Diarsipkan Pada</p>
                        <p class="text-lg font-bold mt-1">
                            {{ $surat->arsip ? $surat->arsip->tanggal_arsip->translatedFormat('d F Y') : 'N/A' }}
                        </p>
                    </div>
                    
                    {{-- PERBAIKAN: Mengakses data dari relasi arsip --}}
                    <div>
                        <p class="text-emerald-500 font-bold text-[10px] uppercase">Habis Masa Retensi</p>
                        @if(isset($surat->arsip) && !empty($surat->arsip->masa_retensi))
                            <p class="text-lg font-bold text-emerald-300 mt-1">
                                {{ $surat->arsip->masa_retensi->translatedFormat('d F Y') }}
                            </p>
                            <p class="text-[10px] text-emerald-400 italic opacity-70 mt-1">
                                *{{ $surat->arsip->masa_retensi->isPast() ? 'Sudah Kadaluarsa' : $surat->arsip->masa_retensi->diffForHumans() }}
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
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $surat->nomor_surat }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Asal Instansi</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $surat->asal_instansi }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-400 font-bold text-[10px] uppercase">Perihal</p>
                        <p class="text-lg font-bold text-emerald-950 dark:text-white mt-1">{{ $surat->perihal }}</p>
                    </div>
                </div>
            </div>

            {{-- Form Disposisi --}}
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5">
                <p class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-6">Instruksi Disposisi</p>
                <form action="{{ route('pimpinan.manajemen_surat.simpan_disposisi') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_surat" value="{{ $surat->id_surat }}">
                    <div class="space-y-4">
                        <select name="id_instruksi" class="w-full p-4 rounded-2xl border border-emerald-100 bg-emerald-50/50 font-bold focus:ring-2 focus:ring-emerald-500" required>
                            <option value="">-- Pilih Instruksi --</option>
                            @foreach($instruksi as $item)
                                <option value="{{ $item->id_instruksi }}">{{ $item->nama_instruksi }}</option>
                            @endforeach
                        </select>
                        <textarea name="catatan" placeholder="Tambahkan catatan pimpinan..." class="w-full p-4 rounded-2xl border border-emerald-100 bg-emerald-50/50 font-medium focus:ring-2 focus:ring-emerald-500" rows="3"></textarea>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white p-4 rounded-2xl font-black uppercase text-sm shadow-lg shadow-emerald-600/30 transition-all">
                            Kirim Disposisi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- KANAN: Preview Dokumen --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-xl shadow-emerald-900/5 h-[800px]">
            <div class="flex justify-between items-center mb-4 px-2">
                <p class="text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest">Preview Dokumen Digital</p>
                <a href="{{ route('pimpinan.manajemen_surat.tampilkan_dokumen', $surat->id_surat) }}" target="_blank" class="text-emerald-600 font-bold text-xs hover:underline">
                    BUKA LAYAR PENUH <i class="fas fa-external-link-alt ml-1"></i>
                </a>
            </div>
            
            <iframe 
                src="{{ route('pimpinan.manajemen_surat.tampilkan_dokumen', $surat->id_surat) }}" 
                class="w-full h-full rounded-3xl" 
                frameborder="0" 
                type="application/pdf"
                title="Preview Dokumen">
            </iframe>
        </div>
    </div>
</div>
@endsection