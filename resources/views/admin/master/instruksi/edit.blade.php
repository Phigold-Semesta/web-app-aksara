@extends('layouts.app')

@section('title', 'Edit Instruksi Pimpinan - AKSARA')

@section('content')
<div class="p-4 md:p-6 space-y-6 animate__animated animate__fadeIn max-w-3xl mx-auto">

    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-emerald-950 dark:to-emerald-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden transition-all duration-300">
        <div class="relative z-10">
            <h1 class="text-3xl font-black uppercase tracking-tight italic text-white">EDIT INSTRUKSI</h1>
            <p class="text-emerald-200 font-bold tracking-widest mt-1 uppercase text-xs">AKSARA - Sistem Informasi Digital LPSE Karawang</p>
        </div>
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
    </div>

    {{-- Form Section --}}
    <div class="bg-white dark:bg-emerald-900/40 p-6 md:p-8 rounded-[2rem] shadow-xl border border-emerald-50 dark:border-emerald-800/50 transition-all duration-300">
        <form action="{{ route('admin.master.instruksi.update', $instruksi->id_instruksi) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 gap-6">
                {{-- Nama Instruksi --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Nama Instruksi</label>
                    <input type="text" 
                           name="nama_instruksi" 
                           value="{{ old('nama_instruksi', $instruksi->nama_instruksi) }}" 
                           required 
                           placeholder="Contoh: Percepatan Proses Tender" 
                           class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm font-semibold text-emerald-900 dark:text-white outline-none transition-all @error('nama_instruksi') border-red-500 @enderror">
                    @error('nama_instruksi') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Deskripsi Instruksi --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Deskripsi Instruksi</label>
                    <textarea name="deskripsi" 
                              rows="4" 
                              placeholder="Detail instruksi pimpinan..." 
                              class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm font-semibold text-emerald-900 dark:text-white outline-none transition-all">{{ old('deskripsi', $instruksi->deskripsi) }}</textarea>
                    @error('deskripsi') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Submit Section --}}
            <div class="pt-6 border-t border-emerald-100 dark:border-emerald-800/50 flex items-center justify-end gap-3">
                <a href="{{ route('admin.master.instruksi.index') }}" class="px-6 py-3 text-xs font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-800 rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="bg-emerald-900 dark:bg-emerald-600 text-white px-8 py-3 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 dark:hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/20 flex items-center gap-2">
                    <i class="fas fa-save"></i> Perbarui Instruksi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection