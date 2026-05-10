{{-- petugas/manajemen_arsip/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-8 max-w-4xl">
    <div class="mb-10 flex justify-between items-center">
        <div>
            <a href="{{ route('petugas.manajemen_arsip.index') }}" class="text-emerald-600 font-bold flex items-center gap-2 mb-4 hover:gap-4 transition-all">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 tracking-tight">Edit Data Arsip</h1>
            <p class="text-emerald-500 font-medium mt-1">Perbarui informasi lokasi fisik dan status masa retensi dokumen</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-emerald-900/5 border border-emerald-50 relative overflow-hidden">
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute top-0 right-0 p-10 opacity-5">
            <i class="fas fa-file-signature text-9xl text-emerald-900"></i>
        </div>

        <form action="{{ route('petugas.manajemen_arsip.update', $arsip->id_arsip) }}" method="POST" class="relative z-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Informasi Surat (Read-Only) --}}
                <div class="col-span-2">
                    <label class="block text-emerald-900 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Dokumen Terkait (Read-Only)</label>
                    <div class="bg-emerald-50/50 rounded-2xl px-6 py-4 border border-emerald-100 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 font-bold">{{ $arsip->surat->perihal }}</span>
                            <span class="text-emerald-500 text-xs font-medium">{{ $arsip->surat->nomor_surat }}</span>
                        </div>
                        <i class="fas fa-lock text-emerald-200"></i>
                    </div>
                </div>

                {{-- Lokasi Fisik --}}
                <div>
                    <label class="block text-emerald-900 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Lokasi Penyimpanan Fisik</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input type="text" name="lokasi_fisik" value="{{ old('lokasi_fisik', $arsip->lokasi_fisik) }}" 
                            class="w-full bg-emerald-50/30 border border-emerald-50 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 focus:bg-white font-bold transition-all" required>
                    </div>
                </div>

                {{-- Tanggal Arsip --}}
                <div>
                    <label class="block text-emerald-900 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Tanggal Pengarsipan</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', $arsip->tanggal_arsip) }}" 
                            class="w-full bg-emerald-50/30 border border-emerald-50 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 focus:bg-white font-bold transition-all" required>
                    </div>
                </div>

                {{-- Status Retensi --}}
                <div>
                    <label class="block text-emerald-900 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Status Retensi</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <select name="status_retensi" class="w-full bg-emerald-50/30 border border-emerald-50 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 focus:ring-2 focus:ring-emerald-500 focus:bg-white font-bold transition-all appearance-none">
                            <option value="Aktif" {{ $arsip->status_retensi == 'Aktif' ? 'selected' : '' }}>Aktif (Terjaga)</option>
                            <option value="Inaktif" {{ $arsip->status_retensi == 'Inaktif' ? 'selected' : '' }}>Inaktif (Habis Masa Simpan)</option>
                        </select>
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-emerald-400 pointer-events-none">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </span>
                    </div>
                </div>

                {{-- Info Masa Retensi (Hanya Label) --}}
                <div class="flex items-center px-4">
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-100 flex gap-4 items-center">
                        <i class="fas fa-info-circle text-amber-500 text-xl"></i>
                        <p class="text-[11px] text-amber-700 font-medium leading-relaxed">
                            Masa retensi dokumen ini dijadwalkan berakhir pada tanggal <span class="font-black">{{ \Carbon\Carbon::parse($arsip->masa_retensi)->format('d M Y') }}</span>.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex gap-4">
                <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-5 rounded-2xl shadow-xl shadow-emerald-200 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-3">
                    <i class="fas fa-save"></i> Perbarui Data Arsip
                </button>
                <a href="{{ route('petugas.manajemen_arsip.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-500 font-black px-10 py-5 rounded-2xl transition-all uppercase tracking-widest text-xs">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection