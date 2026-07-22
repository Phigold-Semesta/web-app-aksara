@extends('layouts.app')

@section('title', 'Dashboard Eksekutif - Aksara')

@section('content')
<div class="p-2 md:p-4 space-y-8 animate__animated animate__fadeIn">
    
    {{-- Header Banner Eksekutif --}}
    <div class="relative overflow-hidden bg-[#006b43] rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl border border-emerald-400/20">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="space-y-4 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-black tracking-tighter leading-none uppercase italic">
                    Dashboard<br>Eksekutif
                </h1>
                <p class="text-emerald-200 text-xs md:text-sm font-bold uppercase tracking-[0.2em]">Monitoring Strategis & Data Laporan LPSE Karawang</p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-[2rem] w-full md:w-80 shadow-2xl">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-10 h-10 bg-emerald-400 rounded-xl flex items-center justify-center text-[#006b43]">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase text-emerald-300">Akses Pimpinan</p>
                        <p class="text-sm font-black uppercase tracking-tight">Executive Control</p>
                    </div>
                </div>
                <p class="text-[10px] text-emerald-100/80 font-bold uppercase tracking-wider">Pantau disposisi & sirkulasi dokumen realtime.</p>
            </div>
        </div>
    </div>
        
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $stats = [
                ['label' => 'Total Surat Masuk', 'val' => $totalSuratMasuk ?? 0, 'icon' => 'fa-envelope-open-text', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                ['label' => 'Total Surat Keluar', 'val' => $totalSuratKeluar ?? 0, 'icon' => 'fa-paper-plane', 'color' => 'text-orange-600', 'bg' => 'bg-orange-50'],
                ['label' => 'Total Disposisi', 'val' => $totalDisposisi ?? 0, 'icon' => 'fa-paste', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50']
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-xl border border-slate-50 dark:border-slate-800 text-center flex flex-col items-center group hover:translate-y-[-5px] transition-all">
            <div class="w-16 h-16 {{ $stat['bg'] }} dark:bg-slate-800 {{ $stat['color'] }} rounded-2xl flex items-center justify-center mb-6 shadow-inner group-hover:rotate-6 transition-transform">
                <i class="fas {{ $stat['icon'] }} text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">{{ $stat['label'] }}</p>
            <h3 class="text-5xl font-black text-slate-800 dark:text-white mb-4 tracking-tighter">{{ $stat['val'] }}</h3>
        </div>
        @endforeach
    </div>

    {{-- Panel Eksekutif Disposisi & Indikator Kinerja Pimpinan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Ringkasan Status Disposisi Pimpinan --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl border border-slate-50 dark:border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic flex items-center gap-3">
                            <i class="fas fa-file-signature text-[#006b43]"></i> Pengawasan Disposisi Strategis
                        </h2>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Metrik Kecepatan Tindak Lanjut & Instruksi Pimpinan</p>
                    </div>
                    <span class="inline-flex items-center text-[10px] font-black uppercase text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-4 py-2 rounded-xl border border-emerald-200 dark:border-emerald-800">
                        <i class="fas fa-check-circle text-[9px] mr-2"></i> Realtime Executive Mode
                    </span>
                </div>

                {{-- Status Grid Disposisi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                    <div class="bg-emerald-50/50 dark:bg-emerald-950/20 p-6 rounded-2xl border border-emerald-100 dark:border-emerald-900/40">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300">Tingkat Penyelesaian</span>
                            <i class="fas fa-percentage text-emerald-600"></i>
                        </div>
                        <h4 class="text-3xl font-black text-emerald-900 dark:text-emerald-100">92%</h4>
                        <div class="w-full bg-emerald-200/50 dark:bg-emerald-900/50 h-2 rounded-full mt-3 overflow-hidden">
                            <div class="bg-[#006b43] h-full w-[92%] rounded-full"></div>
                        </div>
                        <p class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase mt-2">Selesai Ditindaklanjuti Petugas</p>
                    </div>

                    <div class="bg-amber-50/50 dark:bg-amber-950/20 p-6 rounded-2xl border border-amber-100 dark:border-amber-900/40">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-800 dark:text-amber-300">Instruksi Pending</span>
                            <i class="fas fa-clock text-amber-600"></i>
                        </div>
                        <h4 class="text-3xl font-black text-amber-900 dark:text-amber-100">{{ $totalDisposisi ?? 0 }}</h4>
                        <div class="w-full bg-amber-200/50 dark:bg-amber-900/50 h-2 rounded-full mt-3 overflow-hidden">
                            <div class="bg-amber-500 h-full w-1/3 rounded-full"></div>
                        </div>
                        <p class="text-[9px] font-bold text-amber-600 dark:text-amber-400 uppercase mt-2">Membutuhkan Arahan Pimpinan</p>
                    </div>
                </div>
            </div>

            {{-- Informasi Keamanan & Catatan Eksekutif --}}
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                <div class="flex items-center gap-3 text-slate-500 dark:text-slate-400 font-bold uppercase text-[10px] tracking-wider">
                    <i class="fas fa-shield-alt text-emerald-600 text-sm"></i>
                    <span>Tervalidasi Sistem Otomasi Aksara LPSE</span>
                </div>
                <a href="{{ route('pimpinan.manajemen_surat.index') }}" class="text-[#006b43] dark:text-emerald-400 font-black uppercase text-[10px] hover:underline flex items-center gap-2">
                    Buka Lembar Disposisi <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        {{-- Widget Akses Cepat Pimpinan --}}
        <div class="bg-gradient-to-br from-[#006b43] to-emerald-950 p-8 rounded-[2.5rem] text-white shadow-2xl flex flex-col justify-between border border-emerald-400/20">
            <div>
                <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-emerald-300 text-2xl mb-6 shadow-inner">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="text-2xl font-black uppercase italic tracking-tight leading-snug">
                    Pusat Kendali<br>Disposisi Pimpinan
                </h3>
                <p class="text-emerald-200/80 text-xs font-medium mt-3 leading-relaxed">
                    Akses langsung penandatanganan disposisi digital, lembar arahan dinas, dan verifikasi dokumen masuk untuk keposting instansi.
                </p>
            </div>

            <div class="space-y-3 mt-8">
                <a href="{{ route('pimpinan.manajemen_surat.index') }}" class="w-full bg-white text-[#006b43] py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-center block hover:scale-[1.02] transition-transform shadow-xl">
                    <i class="fas fa-pen-fancy mr-2"></i> Kelola Disposisi
                </a>
                <a href="{{ route('pimpinan.monitoring_arsip.index') }}" class="w-full bg-emerald-900/60 hover:bg-emerald-900 border border-emerald-400/30 text-white py-3.5 rounded-2xl font-bold text-[10px] uppercase tracking-widest text-center block transition-colors">
                    <i class="fas fa-box-archive mr-2"></i> Monitoring Arsip
                </a>
            </div>
        </div>

    </div>

</div>
@endsection