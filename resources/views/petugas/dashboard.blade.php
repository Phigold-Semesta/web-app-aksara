@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="p-2 md:p-4 space-y-8 animate__animated animate__fadeIn">
    
    {{-- Banner Utama Workflow --}}
    <div class="relative overflow-hidden bg-[#006b43] rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl border border-emerald-400/20">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="space-y-4 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-black tracking-tighter leading-none uppercase italic">
                    Workflow Kerja<br>Hari Ini
                </h1>
                <p class="text-emerald-200 text-xs md:text-sm font-bold uppercase tracking-[0.2em]">Sistem Digitalisasi & Manajemen Dokumen Terpadu</p>
                <div class="flex flex-wrap gap-4 pt-4 justify-center md:justify-start">
                    <a href="{{ route('petugas.manajemen_surat.create') }}" class="bg-white text-[#006b43] px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> Input Surat Baru
                    </a>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-[2rem] w-full md:w-80 shadow-2xl">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-emerald-400 rounded-xl flex items-center justify-center text-[#006b43]">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-emerald-300">Saran Petugas</p>
                        <p class="text-sm font-black uppercase tracking-tight">Periksa Antrean Surat</p>
                    </div>
                </div>
                <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                    <div class="bg-emerald-400 h-full w-2/3 rounded-full"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl border border-slate-50 dark:border-slate-800 text-center flex flex-col items-center group hover:translate-y-[-5px] transition-all">
            <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner group-hover:rotate-6 transition-transform">
                <i class="fas fa-envelope-open-text text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Surat Masuk</p>
            <h3 class="text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tighter">{{ $stats['surat_masuk'] }}</h3>
            <span class="bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest italic">Update: {{ $stats['update_time'] }}</span>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl border border-slate-50 dark:border-slate-800 text-center flex flex-col items-center group hover:translate-y-[-5px] transition-all">
            <div class="w-16 h-16 bg-orange-50 dark:bg-orange-900/30 text-orange-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner group-hover:rotate-6 transition-transform">
                <i class="fas fa-paper-plane text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Surat Keluar</p>
            <h3 class="text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tighter">{{ $stats['surat_keluar'] }}</h3>
            <span class="bg-orange-50 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400 px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest italic">Digitalized</span>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl border border-slate-50 dark:border-slate-800 text-center flex flex-col items-center group hover:translate-y-[-5px] transition-all">
            <div class="w-16 h-16 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner group-hover:rotate-6 transition-transform">
                <i class="fas fa-archive text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Arsip</p>
            <h3 class="text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tighter">{{ $stats['total_arsip'] }}</h3>
            <span class="bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest italic">Tersimpan Aman</span>
        </div>
    </div>

    {{-- Diagram Progress & Statistik Operasional Petugas (Dinamis dari Database) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-slate-50 dark:border-slate-800 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight italic">Diagram Progress Pengolahan</h2>
                <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-[#006b43] dark:text-emerald-400 rounded-full text-[9px] font-black uppercase tracking-widest">Real-time DB</span>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center text-xs font-black uppercase text-slate-600 dark:text-slate-300 mb-1.5">
                        <span>Arsip Masuk & Terverifikasi</span>
                        <span class="text-[#006b43] dark:text-emerald-400">{{ $persenArsip ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden p-0.5">
                        <div class="bg-gradient-to-r from-emerald-600 to-[#006b43] h-full rounded-full transition-all duration-500" style="width: {{ $persenArsip ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center text-xs font-black uppercase text-slate-600 dark:text-slate-300 mb-1.5">
                        <span>Surat Diproses / Disposisi</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ $persenDisposisi ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden p-0.5">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-full rounded-full transition-all duration-500" style="width: {{ $persenDisposisi ?? 0 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center text-xs font-black uppercase text-slate-600 dark:text-slate-300 mb-1.5">
                        <span>Kepatuhan Klasifikasi Dokumen</span>
                        <span class="text-purple-600 dark:text-purple-400">{{ $persenKlasifikasi ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden p-0.5">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-700 h-full rounded-full transition-all duration-500" style="width: {{ $persenKlasifikasi ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[2.5rem] shadow-xl border border-slate-50 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight italic mb-2">Ringkasan Kinerja Harian</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium leading-relaxed">Indikator operasional tugas petugas dalam mengelola input surat, penomoran, penataan arsip fisik, serta kesinambungan alur disposisi sistem Aksara LPSE.</p>
            </div>
            
            <div class="grid grid-cols-2 gap-4 my-4">
                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800 text-center">
                    <p class="text-[9px] font-black uppercase text-slate-400 mb-1">Status Antrean</p>
                    <p class="text-xl font-black text-slate-800 dark:text-white">OPTIMAL</p>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl border border-slate-100 dark:border-slate-800 text-center">
                    <p class="text-[9px] font-black uppercase text-slate-400 mb-1">Validasi Data</p>
                    <p class="text-xl font-black text-[#006b43] dark:text-emerald-400">100%</p>
                </div>
            </div>

            <div class="flex items-center gap-2 text-[10px] font-black uppercase text-emerald-600 dark:text-emerald-400 tracking-wider">
                <i class="fas fa-circle-check text-xs"></i> Tugas Operasional Berjalan Lancar
            </div>
        </div>
    </div>

    {{-- Widget Akses Pintas & Navigasi Operasional --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div class="bg-gradient-to-r from-[#006b43] to-emerald-800 p-8 rounded-[2.5rem] text-white shadow-xl flex items-center justify-between">
            <div class="space-y-2">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-emerald-200">Manajemen Surat</p>
                <h3 class="text-2xl font-black uppercase tracking-tight">Kelola Seluruh Arsip Dokumen</h3>
                <p class="text-xs text-emerald-100/80 font-medium">Akses cepat pencarian, filtering, dan pengorganisasian data surat.</p>
            </div>
            <a href="{{ route('petugas.manajemen_surat.index') }}" class="w-14 h-14 bg-white text-[#006b43] rounded-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform shadow-lg shrink-0">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="bg-gradient-to-r from-slate-800 to-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl flex items-center justify-between">
            <div class="space-y-2">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Pusat Arsip Fisik</p>
                <h3 class="text-2xl font-black uppercase tracking-tight">Kelola Tempat & Retensi Arsip</h3>
                <p class="text-xs text-slate-300/80 font-medium">Monitoring lokasi fisik, tanggal retensi, dan keamanan arsip.</p>
            </div>
            <a href="{{ route('petugas.manajemen_arsip.index') }}" class="w-14 h-14 bg-emerald-400 text-slate-900 rounded-2xl flex items-center justify-center text-xl hover:scale-110 transition-transform shadow-lg shrink-0">
                <i class="fas fa-box-archive"></i>
            </a>
        </div>
    </div>

</div>
@endsection