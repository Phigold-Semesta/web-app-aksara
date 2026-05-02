@extends('layouts.app')

@section('title', 'Petugas Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-700 to-emerald-900 p-10 rounded-[3rem] text-white shadow-2xl">
        <div class="relative z-10">
            <h2 class="text-3xl font-black tracking-tighter uppercase italic leading-none">Workflow Kerja<br>Hari Ini</h2>
            <p class="text-emerald-100/70 text-xs font-bold uppercase tracking-widest mt-4">Kelola Dokumen LPSE Karawang</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="#" class="bg-white text-emerald-900 px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-50 transition">Entry Surat Masuk</a>
                <a href="#" class="bg-emerald-600/50 backdrop-blur-md border border-white/20 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition">Cetak Resi</a>
            </div>
        </div>
        <i class="fas fa-envelope-open-text absolute -right-10 -bottom-10 text-[18rem] text-white opacity-5"></i>
    </div>

    <!-- Quick Count -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white dark:bg-emerald-900 p-8 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-800 shadow-xl group">
            <div class="w-12 h-12 bg-blue-500/10 text-blue-600 flex items-center justify-center rounded-2xl mb-6 group-hover:scale-110 transition">
                <i class="fas fa-inbox text-xl"></i>
            </div>
            <p class="text-xs font-black text-slate-400 dark:text-emerald-400 uppercase tracking-widest">Surat Masuk</p>
            <h4 class="text-4xl font-black text-slate-800 dark:text-white mt-1">42</h4>
            <p class="text-[9px] font-bold text-slate-400 uppercase mt-4 italic">Update: 5 Menit Lalu</p>
        </div>

        <div class="bg-white dark:bg-emerald-900 p-8 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-800 shadow-xl group">
            <div class="w-12 h-12 bg-amber-500/10 text-amber-600 flex items-center justify-center rounded-2xl mb-6 group-hover:scale-110 transition">
                <i class="fas fa-paper-plane text-xl"></i>
            </div>
            <p class="text-xs font-black text-slate-400 dark:text-emerald-400 uppercase tracking-widest">Surat Keluar</p>
            <h4 class="text-4xl font-black text-slate-800 dark:text-white mt-1">18</h4>
            <p class="text-[9px] font-bold text-slate-400 uppercase mt-4 italic">Menunggu Verifikasi</p>
        </div>

        <div class="bg-white dark:bg-emerald-900 p-8 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-800 shadow-xl group">
            <div class="w-12 h-12 bg-emerald-500/10 text-emerald-600 flex items-center justify-center rounded-2xl mb-6 group-hover:scale-110 transition">
                <i class="fas fa-box-archive text-xl"></i>
            </div>
            <p class="text-xs font-black text-slate-400 dark:text-emerald-400 uppercase tracking-widest">Total Arsip</p>
            <h4 class="text-4xl font-black text-slate-800 dark:text-white mt-1">1,204</h4>
            <p class="text-[9px] font-bold text-slate-400 uppercase mt-4 italic">Digitalized</p>
        </div>
    </div>
</div>
@endsection