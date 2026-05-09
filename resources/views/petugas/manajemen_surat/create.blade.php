@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header --}}
    <div class="mb-10">
        <a href="{{ route('petugas.manajemen_surat.index') }}" class="group flex items-center text-emerald-600 dark:text-emerald-400 font-semibold mb-4 transition-all">
            <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Daftar Surat
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Digitalisasi Surat Baru</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Input data secara teliti untuk arsip digital LPSE Karawang</p>
    </div>

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded-xl shadow-sm">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 overflow-hidden border border-emerald-50 dark:border-slate-800">
        <form action="{{ route('petugas.manajemen_surat.store') }}" method="POST" enctype="multipart/form-data" class="p-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kolom Kiri --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Nomor Surat</label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium"
                               placeholder="Contoh: 001/LPSE/2026">
                        @error('nomor_surat') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Asal Instansi</label>
                        <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium"
                               placeholder="Nama Instansi Pengirim/Tujuan">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Kategori Surat</label>
                        <select name="id_kategori" required
                                class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium appearance-none">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Perihal / Ringkasan</label>
                        <textarea name="perihal" rows="4" required
                                  class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium"
                                  placeholder="Isi singkat perihal surat..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Upload File Section --}}
            <div class="mt-10 p-8 border-2 border-dashed border-emerald-200 dark:border-slate-700 rounded-[2rem] bg-emerald-50/30 dark:bg-slate-800/20 text-center">
                <i class="fas fa-cloud-upload-alt text-4xl text-emerald-500 mb-4"></i>
                <h3 class="text-emerald-900 dark:text-emerald-100 font-bold text-lg">Unggah Dokumen Digital</h3>
                <p class="text-emerald-500 dark:text-emerald-400 text-sm mb-6 uppercase tracking-wider font-black">Format: PDF, JPG, PNG (Maks. 2MB)</p>
                <input type="file" name="file_dokumen" required
                       class="block w-full text-sm text-emerald-500 file:mr-4 file:py-3 file:px-8 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition-all cursor-pointer">
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="reset" class="px-8 py-4 text-emerald-600 dark:text-emerald-400 font-bold hover:bg-emerald-50 dark:hover:bg-slate-800 rounded-2xl transition-all">
                    Reset Form
                </button>
                <button type="submit" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black shadow-xl shadow-emerald-200 dark:shadow-none transition-all transform hover:-translate-y-1">
                    SIMPAN & DIGITALISASI
                </button>
            </div>
        </form>
    </div>
</div>
@endsection