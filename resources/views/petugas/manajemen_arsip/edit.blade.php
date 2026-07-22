{{-- petugas/manajemen_arsip/edit.blade.php --}}
@extends('layouts.app')

@section('content')
{{-- Menambahkan mx-auto agar posisi konten berada di tengah secara proporsional --}}
<div class="p-8 max-w-4xl mx-auto transition-colors duration-300 min-h-screen">
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            {{-- PERBAIKAN: Tombol Kembali Sesuai Gambar Referensi --}}
            <a href="{{ route('petugas.manajemen_arsip.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Edit Data Arsip</h1>
            <p class="text-emerald-500 dark:text-emerald-400 font-medium mt-1">Perbarui informasi lokasi fisik dan masa retensi dokumen Aksara</p>
        </div>
    </div>

    {{-- Container Card: Disesuaikan agar adaptif dan proporsional --}}
    <div class="bg-white dark:bg-slate-900/50 backdrop-blur-xl rounded-[2.5rem] p-6 md:p-10 shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-emerald-900/20 relative overflow-hidden transition-all">
        {{-- Dekorasi Latar Belakang --}}
        <div class="absolute top-0 right-0 p-10 opacity-5 dark:opacity-10">
            <i class="fas fa-file-signature text-9xl text-emerald-900 dark:text-emerald-400"></i>
        </div>

        <form action="{{ route('petugas.manajemen_arsip.update', $arsip->id_arsip) }}" method="POST" class="relative z-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Informasi Surat (Read-Only) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Dokumen Terkait (Read-Only)</label>
                    <div class="bg-emerald-50/50 dark:bg-slate-950/50 rounded-2xl px-6 py-4 border border-emerald-100 dark:border-emerald-900/20 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 dark:text-emerald-50 font-bold">{{ $arsip->surat->perihal ?? 'Surat Tidak Ditemukan' }}</span>
                            <span class="text-emerald-500 dark:text-emerald-400 text-xs font-medium">{{ $arsip->surat->nomor_surat ?? 'N/A' }}</span>
                        </div>
                        <i class="fas fa-lock text-emerald-200 dark:text-emerald-800"></i>
                    </div>
                </div>

                {{-- Lokasi Fisik --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Lokasi Penyimpanan Fisik</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input type="text" name="lokasi_fisik" value="{{ old('lokasi_fisik', $arsip->lokasi_fisik) }}" 
                            class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-950 font-bold transition-all placeholder-emerald-300" required>
                    </div>
                </div>

                {{-- Tanggal Arsip --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Tanggal Pengarsipan</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <input type="date" name="tanggal_arsip" value="{{ old('tanggal_arsip', $arsip->tanggal_arsip) }}" 
                            class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-950 font-bold transition-all" required>
                    </div>
                </div>

                {{-- Status Retensi --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Status Retensi</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-emerald-400">
                            <i class="fas fa-shield-alt"></i>
                        </span>
                        <select name="status_retensi" class="w-full bg-emerald-50/30 dark:bg-slate-950/30 border border-emerald-50 dark:border-emerald-900/20 rounded-2xl pl-14 pr-6 py-4 text-emerald-900 dark:text-emerald-50 focus:ring-2 focus:ring-emerald-500 focus:bg-white dark:focus:bg-slate-950 font-bold transition-all appearance-none">
                            <option value="Aktif" {{ old('status_retensi', $arsip->status_retensi) == 'Aktif' ? 'selected' : '' }}>Aktif (Terjaga)</option>
                            <option value="Inaktif" {{ old('status_retensi', $arsip->status_retensi) == 'Inaktif' ? 'selected' : '' }}>Inaktif (Habis Masa Simpan)</option>
                        </select>
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-emerald-400 pointer-events-none">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </span>
                    </div>
                </div>

                {{-- EDIT DURASI RETENSI --}}
                <div class="col-span-1">
                    <label class="block text-emerald-900 dark:text-emerald-100 font-black uppercase text-[10px] tracking-[0.2em] mb-3">Durasi Retensi</label>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" name="retensi_nilai" placeholder="Contoh: 5" class="w-full bg-amber-50/30 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl px-6 py-4 text-amber-900 dark:text-amber-200 font-bold focus:ring-2 focus:ring-amber-500" required>
                        <select name="retensi_satuan" class="w-full bg-amber-50/30 dark:bg-amber-950/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl px-6 py-4 text-amber-900 dark:text-amber-200 font-bold focus:ring-2 focus:ring-amber-500">
                            <option value="days">Hari</option>
                            <option value="weeks">Minggu</option>
                            <option value="months">Bulan</option>
                            <option value="years">Tahun</option>
                        </select>
                    </div>
                </div>

                {{-- Info Note --}}
                <div class="col-span-1 md:col-span-2">
                    <div class="bg-amber-50 dark:bg-amber-900/20 rounded-2xl p-5 border border-amber-100 dark:border-amber-900/30 flex gap-4 items-start">
                        <i class="fas fa-info-circle text-amber-500 text-xl mt-1"></i>
                        <div class="space-y-1">
                            <p class="text-[11px] text-amber-700 dark:text-amber-400 font-black uppercase tracking-widest">Catatan Sistem Aksara</p>
                            <p class="text-[12px] text-amber-600 dark:text-amber-500 font-medium leading-relaxed">
                                @php $isValid = strtotime($arsip->masa_retensi) !== false; @endphp
                                @if($isValid)
                                    Masa retensi saat ini berakhir pada <span class="font-bold underline">{{ \Carbon\Carbon::parse($arsip->masa_retensi)->translatedFormat('d F Y') }}</span>.
                                @else
                                    Masa retensi saat ini: <span class="font-bold">N/A</span>. Harap isi durasi di atas untuk memperbaruinya.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PERBAIKAN: Hanya Tombol Simpan Perubahan di Pojok Kanan (Tombol Batal Dihapus) --}}
            <div class="mt-12 flex justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-black px-10 py-5 rounded-2xl shadow-xl shadow-emerald-200 dark:shadow-none transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-3 transform hover:-translate-y-1">
                    <i class="fas fa-save"></i> Perbarui Data Arsip
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