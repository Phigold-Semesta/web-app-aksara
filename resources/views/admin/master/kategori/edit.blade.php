@extends('layouts.app')

@section('title', 'Edit Kategori Surat - AKSARA')

@section('content')
<div class="p-4 md:p-8 max-w-4xl mx-auto animate__animated animate__fadeIn">
    
    {{-- Header Section --}}
    <div class="mb-10">
        <a href="{{ route('admin.master.kategori.index') }}" class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kategori
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight uppercase italic">Edit Kategori Surat</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Perbarui informasi klasifikasi: <span class="font-black italic text-emerald-800 dark:text-emerald-200">{{ $kategori->nama_kategori }}</span></p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2.5rem] p-10 shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 border border-emerald-50 dark:border-emerald-800/50">
        <form action="{{ route('admin.master.kategori.update', $kategori->id_kategori) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                {{-- Grid Input --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Kode Kategori --}}
                    <div>
                        <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3 ml-2">Kode Kategori</label>
                        <input type="text" 
                               name="kode_kategori" 
                               value="{{ old('kode_kategori', $kategori->kode_kategori) }}" 
                               placeholder="Contoh: KTG-006" 
                               class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold @error('kode_kategori') ring-2 ring-red-500 @enderror" 
                               required>
                        @error('kode_kategori')
                            <p class="text-red-500 text-xs mt-2 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Kategori --}}
                    <div>
                        <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3 ml-2">Nama Kategori</label>
                        <input type="text" 
                               name="nama_kategori" 
                               value="{{ old('nama_kategori', $kategori->nama_kategori) }}" 
                               placeholder="Contoh: Surat Masuk" 
                               class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold @error('nama_kategori') ring-2 ring-red-500 @enderror" 
                               required>
                        @error('nama_kategori')
                            <p class="text-red-500 text-xs mt-2 ml-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3 ml-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" 
                              rows="3" 
                              placeholder="Deskripsi singkat mengenai kategori surat ini..." 
                              class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                </div>
            </div>

            {{-- Tombol Simpan (Emerald Green) --}}
            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-200 dark:shadow-none transition-all uppercase tracking-widest active:scale-95 flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Perbarui Data
            </button>
        </form>
    </div>
</div>
@endsection