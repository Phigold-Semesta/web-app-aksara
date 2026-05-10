@extends('layouts.app')

@section('content')
<div class="p-8 max-w-4xl">
    <div class="mb-10">
        <a href="{{ route('petugas.manajemen_arsip.index') }}" class="text-emerald-600 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 tracking-tight">Catat Arsip Fisik</h1>
    </div>

    {{-- Alert untuk pesan error umum --}}
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl font-bold">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-emerald-900/5 border border-emerald-50">
        <form action="{{ route('petugas.manajemen_arsip.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Pilih Surat --}}
                <div class="col-span-2">
                    <label class="block text-emerald-900 font-black uppercase text-xs tracking-widest mb-3">Pilih Surat untuk Diarsipkan</label>
                    <select name="id_surat" class="w-full bg-emerald-50/50 border-none rounded-2xl px-6 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 font-bold @error('id_surat') ring-2 ring-red-500 @enderror">
                        <option value="" disabled selected>-- Pilih Surat yang Belum Diarsipkan --</option>
                        @foreach($surats as $surat)
                            <option value="{{ $surat->id_surat }}" {{ old('id_surat') == $surat->id_surat ? 'selected' : '' }}>
                                {{ $surat->nomor_surat }} - {{ $surat->perihal }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_surat')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Lokasi Fisik --}}
                <div>
                    <label class="block text-emerald-900 font-black uppercase text-xs tracking-widest mb-3">Lokasi Penyimpanan Fisik</label>
                    <input type="text" name="lokasi_fisik" value="{{ old('lokasi_fisik') }}" placeholder="Contoh: Lemari A, Rak 02" class="w-full bg-emerald-50/50 border-none rounded-2xl px-6 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 font-bold @error('lokasi_fisik') ring-2 ring-red-500 @enderror" required>
                    @error('lokasi_fisik')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Arsip --}}
                <div>
                    <label class="block text-emerald-900 font-black uppercase text-xs tracking-widest mb-3">Tanggal Pengarsipan</label>
                    <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', date('Y-m-d')) }}" class="w-full bg-emerald-50/50 border-none rounded-2xl px-6 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 font-bold @error('tanggal_arsip') ring-2 ring-red-500 @enderror" required>
                    @error('tanggal_arsip')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Masa Retensi Dinamis (DISEMPURNAKAN) --}}
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-emerald-900 font-black uppercase text-xs tracking-widest mb-3">Durasi Masa Retensi</label>
                    <div class="flex gap-2">
                        {{-- Input Nilai --}}
                        <input type="number" name="retensi_nilai" value="{{ old('retensi_nilai') }}" placeholder="Contoh: 1" class="w-1/2 bg-emerald-50/50 border-none rounded-2xl px-6 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 font-bold @error('retensi_nilai') ring-2 ring-red-500 @enderror" required>
                        
                        {{-- Dropdown Satuan --}}
                        <select name="retensi_satuan" class="w-1/2 bg-emerald-50/50 border-none rounded-2xl px-4 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 font-bold">
                            <option value="days" {{ old('retensi_satuan') == 'days' ? 'selected' : '' }}>Hari</option>
                            <option value="weeks" {{ old('retensi_satuan') == 'weeks' ? 'selected' : '' }}>Minggu</option>
                            <option value="months" {{ old('retensi_satuan') == 'months' ? 'selected' : '' }}>Bulan</option>
                            <option value="years" {{ old('retensi_satuan') == 'years' ? 'selected' : '' }} selected>Tahun</option>
                        </select>
                    </div>
                    @error('retensi_nilai')
                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-[10px] text-emerald-400 italic">*Sistem akan menghitung otomatis tanggal kadaluarsa berdasarkan nilai dan satuan yang dipilih.</p>
                </div>
            </div>

            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-200 transition-all uppercase tracking-widest active:scale-95">
                Simpan Data Arsip
            </button>
        </form>
    </div>
</div>
@endsection