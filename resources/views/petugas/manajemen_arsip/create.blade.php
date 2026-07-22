@extends('layouts.app')

@section('content')
<div class="p-8 max-w-4xl mx-auto transition-colors duration-300 min-h-screen">
    {{-- Header Section --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            {{-- Tombol Kembali: Sesuai gambar referensi --}}
            <a href="{{ route('petugas.manajemen_arsip.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Catat Arsip Fisik</h1>
            <p class="text-emerald-500 dark:text-emerald-400 font-medium mt-1">Registrasi lokasi penyimpanan fisik dan penentuan masa retensi dokumen digital</p>
        </div>
    </div>

    {{-- Alert untuk pesan error umum --}}
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 rounded-r-xl font-bold text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Container Card Modern --}}
    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl rounded-[2.5rem] p-6 md:p-10 shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-emerald-900/20 relative overflow-hidden transition-all">
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute top-0 right-0 p-10 opacity-5 dark:opacity-10 pointer-events-none">
            <i class="fas fa-file-signature text-9xl text-emerald-900 dark:text-emerald-400"></i>
        </div>

        <form action="{{ route('petugas.manajemen_arsip.store') }}" method="POST" class="relative z-10">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Pilih Surat untuk Diarsipkan --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Pilih Surat untuk Diarsipkan</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-envelope-open-text"></i>
                        </span>
                        <select name="id_surat" class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-10 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-950 font-bold transition-all appearance-none @error('id_surat') ring-2 ring-red-500 @enderror" required>
                            <option value="" disabled selected>-- Pilih Surat yang Belum Diarsipkan --</option>
                            @foreach($surats as $surat)
                                <option value="{{ $surat->id_surat }}" {{ old('id_surat') == $surat->id_surat ? 'selected' : '' }}>
                                    {{ $surat->nomor_surat }} - {{ $surat->perihal }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-emerald-400 pointer-events-none">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </span>
                    </div>
                    @error('id_surat')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi Fisik --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Lokasi Penyimpanan Fisik</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input type="text" name="lokasi_fisik" value="{{ old('lokasi_fisik') }}" placeholder="Contoh: Lemari A, Rak 02" 
                               class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-950 font-bold transition-all placeholder-emerald-300 @error('lokasi_fisik') ring-2 ring-red-500 @enderror" required>
                    </div>
                    @error('lokasi_fisik')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Pengarsipan --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Tanggal Pengarsipan</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', date('Y-m-d')) }}" 
                               class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-950 font-bold transition-all @error('tanggal_arsip') ring-2 ring-red-500 @enderror" required>
                    </div>
                    @error('tanggal_arsip')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Masa Retensi Dinamis --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Durasi Masa Retensi</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Input Nilai --}}
                        <input type="number" name="retensi_nilai" value="{{ old('retensi_nilai') }}" placeholder="Contoh: 1" 
                               class="w-full bg-amber-50/30 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl px-6 py-4 text-amber-900 dark:text-amber-200 font-bold focus:ring-2 focus:ring-amber-500 @error('retensi_nilai') ring-2 ring-red-500 @enderror" required>
                        
                        {{-- Dropdown Satuan --}}
                        <div class="relative">
                            <select name="retensi_satuan" class="w-full bg-amber-50/30 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl px-6 py-4 text-amber-900 dark:text-amber-200 font-bold focus:ring-2 focus:ring-amber-500 appearance-none pr-10">
                                <option value="days" {{ old('retensi_satuan') == 'days' ? 'selected' : '' }}>Hari</option>
                                <option value="weeks" {{ old('retensi_satuan') == 'weeks' ? 'selected' : '' }}>Minggu</option>
                                <option value="months" {{ old('retensi_satuan') == 'months' ? 'selected' : '' }}>Bulan</option>
                                <option value="years" {{ old('retensi_satuan') == 'years' || !old('retensi_satuan') ? 'selected' : '' }}>Tahun</option>
                            </select>
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-amber-500 pointer-events-none">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </span>
                        </div>
                    </div>
                    @error('retensi_nilai')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan Informasi System --}}
                <div class="col-span-1 md:col-span-2">
                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl p-5 border border-amber-100 dark:border-amber-900/30 flex gap-4 items-start">
                        <i class="fas fa-info-circle text-amber-500 text-xl mt-1"></i>
                        <div class="space-y-1">
                            <p class="text-[11px] text-amber-700 dark:text-amber-400 font-black uppercase tracking-widest">Catatan Sistem Aksara</p>
                            <p class="text-[12px] text-amber-600 dark:text-amber-500 font-medium leading-relaxed">
                                Sistem akan menghitung otomatis tanggal kadaluarsa retensi berdasarkan nilai dan satuan waktu yang Anda pilih di atas.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Tombol Submit di Pojok Kanan --}}
            <div class="mt-12 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black px-10 py-5 rounded-2xl shadow-xl shadow-emerald-200 dark:shadow-none transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-3 transform hover:-translate-y-1 active:scale-95 cursor-pointer">
                    <i class="fas fa-save"></i> Simpan Data Arsip
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
</style>
@endsection