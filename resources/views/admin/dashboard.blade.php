@extends('layouts.app')

@section('title', 'Admin Dashboard - Aksara')

@section('content')
<div class="p-2 md:p-4 space-y-6 animate__animated animate__fadeIn">
    
    <div class="relative overflow-hidden bg-[#006b43] rounded-[2rem] p-6 md:p-10 text-white shadow-2xl border border-emerald-400/20">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-3 text-center md:text-left w-full md:w-2/3">
                <span class="px-3 py-1 bg-white/20 text-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-widest italic">
                    Panel Kendali Utama
                </span>
                <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none uppercase italic mt-1">
                    KENDALI SISTEM<br>AKSARA LPSE
                </h1>
                <p class="text-emerald-200 text-xs md:text-sm font-bold uppercase tracking-[0.15em]">
                    Selamat Datang Kembali, <span>{{ Auth::user()->nama_lengkap ?? 'Administrator' }}</span>
                </p>
                
                <div class="flex flex-wrap gap-3 pt-2 justify-center md:justify-start">
                    <a href="{{ route('admin.master.user.index') }}" class="bg-white text-[#006b43] px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl flex items-center">
                        <i class="fas fa-user-gear mr-2"></i> Manajemen User
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="bg-emerald-800/50 hover:bg-emerald-800/80 text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:scale-105 transition-all shadow-xl flex items-center border border-white/10">
                        <i class="fas fa-chart-pie mr-2"></i> Analisis Statistik
                    </a>
                </div>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-[1.5rem] w-full md:w-80 shadow-2xl">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-emerald-400 rounded-lg flex items-center justify-center text-[#006b43] text-sm">
                        <i class="fas fa-server"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase text-emerald-300">Penyimpanan Server</p>
                        <p class="text-xs font-black uppercase tracking-tight">Kapasitas Sisa: 74%</p>
                    </div>
                </div>
                <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                    <div class="bg-emerald-400 h-full w-[74%] rounded-full"></div>
                </div>
            </div>
        </div>
        <div class="absolute top-0 right-0 p-4 opacity-5 text-[15rem] pointer-events-none text-white font-black">
            <i class="fas fa-shield-halved"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Total Pengguna --}}
        <div class="bg-white dark:bg-slate-900 p-5 rounded-[1.5rem] shadow-md border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:translate-y-[-3px] transition-all relative overflow-hidden">
            <div class="space-y-1 relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">{{ $totalPengguna }}</h3>
                <p class="text-[9px] text-blue-600 dark:text-blue-400 font-bold uppercase italic">Akses Terdaftar</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform relative z-10">
                <i class="fas fa-users text-lg"></i>
            </div>
        </div>

        {{-- Total Arsip --}}
        <div class="bg-white dark:bg-slate-900 p-5 rounded-[1.5rem] shadow-md border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:translate-y-[-3px] transition-all relative overflow-hidden">
            <div class="space-y-1 relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Total Arsip</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">{{ $totalArsip }}</h3>
                <p class="text-[9px] text-emerald-600 dark:text-emerald-400 font-bold uppercase italic">Tersimpan Aman</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 rounded-xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform relative z-10">
                <i class="fas fa-box-archive text-lg"></i>
            </div>
        </div>

        {{-- Total Kategori --}}
        <div class="bg-white dark:bg-slate-900 p-5 rounded-[1.5rem] shadow-md border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:translate-y-[-3px] transition-all relative overflow-hidden">
            <div class="space-y-1 relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Kategori Data</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">{{ $totalKategori }}</h3>
                <p class="text-[9px] text-purple-600 dark:text-purple-400 font-bold uppercase italic">Master Klasifikasi</p>
            </div>
            <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 text-purple-600 rounded-xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform relative z-10">
                <i class="fas fa-tags text-lg"></i>
            </div>
        </div>

        {{-- Audit Log --}}
        <div class="bg-white dark:bg-slate-900 p-5 rounded-[1.5rem] shadow-md border border-slate-100 dark:border-slate-800 flex items-center justify-between group hover:translate-y-[-3px] transition-all relative overflow-hidden">
            <div class="space-y-1 relative z-10">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Audit Log</p>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">ACTIVE</h3>
                <p class="text-[9px] text-orange-600 dark:text-orange-400 font-bold uppercase italic">Keamanan Dipantau</p>
            </div>
            <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 text-orange-600 rounded-xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform relative z-10">
                <i class="fas fa-chart-line text-lg"></i>
            </div>
        </div>
    </div>

    {{-- Activity Table --}}
    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-lg border border-slate-50 dark:border-slate-800">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight italic">Log Aktivitas Terbaru</h2>
            <a href="{{ route('admin.aktivitas.index') }}" class="text-emerald-600 hover:text-emerald-700 text-[10px] font-black uppercase tracking-wider">Lihat Semua</a>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-slate-400 text-[9px] font-black uppercase tracking-wider border-b border-slate-100 dark:border-slate-800">
                    <th class="py-3 px-4">Nama User</th>
                    <th class="py-3 px-4">Role</th>
                    <th class="py-3 px-4">Aksi</th>
                    <th class="py-3 px-4 text-right">Waktu</th>
                </tr>
            </thead>
            <tbody class="text-xs font-bold text-slate-600 dark:text-slate-300">
                @foreach($logs as $log)
                <tr class="border-b border-slate-50 dark:border-slate-800/50 hover:bg-slate-50 dark:hover:bg-slate-800/30">
                    <td class="py-3 px-4">{{ $log->user->nama_lengkap ?? 'Sistem' }}</td>
                    <td class="py-3 px-4">{{ $log->user->role ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $log->aksi }}</td>
                    <td class="py-3 px-4 text-right">{{ $log->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection