@extends('layouts.app')

@section('title', 'Edit Instruksi - AKSARA')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto animate__animated animate__fadeIn">
    
    {{-- Header Section --}}
    <div class="mb-10">
        <a href="{{ route('admin.master.instruksi.index') }}" class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Instruksi
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight uppercase italic">Edit Instruksi Pimpinan</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Perbarui data instruksi baku untuk sistem disposisi AKSARA</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2.5rem] p-10 shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 border border-emerald-50 dark:border-emerald-800/50">
        {{-- Menggunakan route update dengan parameter id_instruksi --}}
        <form action="{{ route('admin.master.instruksi.update', $instruksi->id_instruksi) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                {{-- Nama Instruksi --}}
                <div>
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3 ml-2">Nama Instruksi</label>
                    <input type="text" 
                           name="nama_instruksi" 
                           {{-- Mengisi nilai awal dengan data dari database --}}
                           value="{{ old('nama_instruksi', $instruksi->nama_instruksi) }}" 
                           placeholder="Contoh: Tindak Lanjuti, Koordinasikan, dll." 
                           class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold @error('nama_instruksi') ring-2 ring-red-500 @enderror" 
                           required>
                    @error('nama_instruksi')
                        <p class="text-red-500 text-xs mt-2 ml-2 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-200 dark:shadow-none transition-all uppercase tracking-widest active:scale-95 flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Perbarui Instruksi
            </button>
        </form>
    </div>
</div>
@endsection