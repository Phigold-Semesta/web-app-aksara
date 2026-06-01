{{-- admin/manajemen_arsip/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-8 max-w-4xl mx-auto transition-colors duration-300 min-h-screen">
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('admin.manajemen_arsip.index') }}" class="text-slate-500 dark:text-slate-400 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Edit Data Arsip</h1>
            <p class="text-emerald-500 dark:text-emerald-400 font-medium mt-1">Perbarui informasi lokasi fisik dan masa retensi dokumen Aksara (Administrator)</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl rounded-[2.5rem] p-6 md:p-10 shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-emerald-900/20 relative overflow-hidden transition-all">
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute top-0 right-0 p-10 opacity-5 dark:opacity-10 pointer-events-none">
            <i class="fas fa-file-signature text-9xl text-emerald-900 dark:text-emerald-400"></i>
        </div>

        <form action="{{ route('admin.manajemen_arsip.update', $arsip->id_arsip) }}" method="POST" class="relative z-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Informasi Surat (Read-Only) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Dokumen Terkait (Read-Only)</label>
                    <div class="bg-emerald-50/50 dark:bg-slate-950/50 rounded-2xl px-6 py-4 border border-emerald-100 dark:border-emerald-900/20 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 dark:text-emerald-50 font-bold">{{ $arsip->surat->perihal ?? 'Surat tidak ditemukan' }}</span>
                            <span class="text-emerald-500 dark:text-emerald-400 text-xs font-medium">{{ $arsip->surat->nomor_surat ?? '-' }}</span>
                        </div>
                        <i class="fas fa-lock text-emerald-200 dark:text-emerald-800"></i>
                    </div>
                </div>

                {{-- Lokasi Fisik --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Lokasi Penyimpanan Fisik</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400"><i class="fas fa-map-marker-alt"></i></span>
                        <input type="text" name="lokasi_fisik" value="{{ old('lokasi_fisik', $arsip->lokasi_fisik) }}" 
                            class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 font-bold transition-all" required>
                    </div>
                </div>

                {{-- Tanggal Arsip --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Tanggal Pengarsipan</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400"><i class="fas fa-calendar-alt"></i></span>
                        <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', $arsip->tanggal_arsip) }}" 
                            class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 font-bold transition-all" required>
                    </div>
                </div>

                {{-- Status Retensi --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Status Retensi</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400"><i class="fas fa-shield-alt"></i></span>
                        <select name="status_retensi" class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 font-bold transition-all appearance-none">
                            <option value="Aktif" {{ old('status_retensi', $arsip->status_retensi) == 'Aktif' ? 'selected' : '' }}>Aktif (Terjaga)</option>
                            <option value="Inaktif" {{ old('status_retensi', $arsip->status_retensi) == 'Inaktif' ? 'selected' : '' }}>Inaktif (Habis Masa Simpan)</option>
                        </select>
                    </div>
                </div>

                {{-- Masa Retensi --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Masa Retensi (Hingga)</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-amber-500"><i class="fas fa-hourglass-half"></i></span>
                        <input type="date" name="masa_retensi" value="{{ old('masa_retensi', \Carbon\Carbon::parse($arsip->masa_retensi)->format('Y-m-d')) }}" 
                            class="w-full bg-amber-50/30 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl pl-14 pr-6 py-4 text-amber-900 dark:text-amber-200 focus:ring-2 focus:ring-amber-500 font-bold transition-all">
                    </div>
                </div>
            </div>

            {{-- Button Group --}}
            <div class="mt-12 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-200 dark:shadow-none transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> Perbarui Data Arsip
                </button>
                <a href="{{ route('admin.manajemen_arsip.index') }}" class="bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 font-black px-10 py-5 rounded-2xl transition-all uppercase tracking-widest text-xs text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection