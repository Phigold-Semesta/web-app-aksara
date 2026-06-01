@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    {{-- Header --}}
    <div class="mb-10">
        <a href="{{ route('admin.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Perbarui Data Surat</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Mengedit arsip digital #{{ $surat->id_surat }}</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('admin.manajemen_surat.update', $surat->id_surat) }}" method="POST" enctype="multipart/form-data" class="p-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kolom Kiri --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Perihal</label>
                        <input type="text" name="perihal" value="{{ old('perihal', $surat->perihal) }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Asal Instansi</label>
                        <input type="text" name="asal_instansi" value="{{ old('asal_instansi', $surat->asal_instansi) }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat) }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Status</label>
                        <select name="status" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                            <option value="PENDING" {{ $surat->status == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                            <option value="DITERUSKAN" {{ $surat->status == 'DITERUSKAN' ? 'selected' : '' }}>DITERUSKAN</option>
                            <option value="SELESAI" {{ $surat->status == 'SELESAI' ? 'selected' : '' }}>SELESAI</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Edit File Section --}}
            <div class="mt-10 p-8 border-2 border-dashed border-emerald-200 dark:border-slate-700 rounded-[2rem] bg-emerald-50/30 dark:bg-slate-800/20">
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="w-full md:w-1/3">
                        <p class="text-xs font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3 text-center">File Saat Ini</p>
                        <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-emerald-100 dark:border-slate-700 text-center">
                            <i class="fas fa-file-alt text-3xl text-emerald-600 mb-2"></i>
                            <p class="text-[10px] text-emerald-600 truncate">{{ $surat->file_surat ?? 'Tidak ada file' }}</p>
                        </div>
                    </div>
                    <div class="w-full md:w-2/3">
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Ganti Dokumen (Opsional)</label>
                        <input type="file" name="file_dokumen"
                               class="block w-full text-sm text-emerald-500 file:mr-4 file:py-3 file:px-8 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition-all cursor-pointer">
                        <p class="mt-2 text-[10px] text-emerald-400 italic">*Biarkan kosong jika tidak ingin mengganti file.</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <a href="{{ route('admin.manajemen_surat.index') }}" class="px-8 py-4 text-emerald-600 dark:text-emerald-400 font-bold hover:bg-emerald-50 dark:hover:bg-slate-800 rounded-2xl transition-all">
                    Batalkan
                </a>
                <button type="submit" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black shadow-xl shadow-emerald-200 dark:shadow-none transition-all transform hover:-translate-y-1">
                    SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </div>
</div>
@endsection