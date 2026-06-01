@extends('layouts.app')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-10">
        <a href="{{ route('admin.manajemen_arsip.index') }}" class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Arsip
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight">Catat Arsip Fisik</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Input data penyimpanan dokumen baru untuk sistem AKSARA (Administrator)</p>
    </div>

    {{-- Alert untuk pesan error --}}
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 text-red-700 dark:text-red-400 rounded-r-xl font-bold">
        {{ session('error') }}
    </div>
    @endif

    {{-- Form Card --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2.5rem] p-10 shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 border border-emerald-50 dark:border-emerald-800/50">
        <form action="{{ route('admin.manajemen_arsip.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {{-- Pilih Surat --}}
                <div class="col-span-2">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3">Pilih Surat untuk Diarsipkan</label>
                    <select name="id_surat" class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold @error('id_surat') ring-2 ring-red-500 @enderror" required>
                        <option value="" disabled selected>-- Pilih Surat yang Belum Diarsipkan --</option>
                        @foreach($surats as $surat)
                            <option value="{{ $surat->id_surat }}" {{ old('id_surat') == $surat->id_surat ? 'selected' : '' }}>
                                {{ $surat->nomor_surat }} - {{ $surat->perihal }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lokasi Fisik --}}
                <div>
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3">Lokasi Penyimpanan Fisik</label>
                    <input type="text" name="lokasi_fisik" value="{{ old('lokasi_fisik') }}" placeholder="Contoh: Lemari A, Rak 02" class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold" required>
                </div>

                {{-- Tanggal Arsip --}}
                <div>
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3">Tanggal Pengarsipan</label>
                    <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', date('Y-m-d')) }}" class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold" required>
                </div>

                {{-- Masa Retensi Dinamis --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest mb-3">Durasi Masa Retensi</label>
                    <div class="flex gap-2">
                        <input type="number" name="retensi_nilai" value="{{ old('retensi_nilai') }}" placeholder="Contoh: 1" class="w-1/2 bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-6 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold" required>
                        <select name="retensi_satuan" class="w-1/2 bg-emerald-50/50 dark:bg-emerald-950/30 border-none rounded-2xl px-4 py-4 text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 font-bold">
                            <option value="days">Hari</option>
                            <option value="weeks">Minggu</option>
                            <option value="months">Bulan</option>
                            <option value="years" selected>Tahun</option>
                        </select>
                    </div>
                    <p class="mt-2 text-[10px] text-emerald-400 dark:text-emerald-500 italic">*Sistem akan menghitung otomatis tanggal kadaluarsa.</p>
                </div>
            </div>

            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-200 dark:shadow-none transition-all uppercase tracking-widest active:scale-95">
                Simpan Data Arsip
            </button>
        </form>
    </div>
</div>
@endsection