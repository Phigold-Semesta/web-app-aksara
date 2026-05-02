@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="bg-white dark:bg-emerald-900 p-8 rounded-[2.5rem] shadow-xl border border-emerald-50 dark:border-emerald-800 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">Console Administrator</h1>
            <p class="text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-1">Aksara System Control Center</p>
        </div>
        <div class="hidden md:block">
            <span class="bg-emerald-500 text-white px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20">System Online</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-emerald-900/40 p-6 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-lg">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Total Pengguna</p>
            <div class="flex items-center justify-between">
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">12</h3>
                <i class="fas fa-users-gear text-2xl text-blue-500 opacity-20"></i>
            </div>
        </div>
        
        <div class="bg-white dark:bg-emerald-900/40 p-6 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-lg">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Database Size</p>
            <div class="flex items-center justify-between">
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">256<span class="text-lg uppercase">mb</span></h3>
                <i class="fas fa-database text-2xl text-emerald-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-emerald-900/40 p-6 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-lg">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Security Logs</p>
            <div class="flex items-center justify-between">
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">89</h3>
                <i class="fas fa-shield-halved text-2xl text-purple-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-emerald-900/40 p-6 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-lg">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Server Uptime</p>
            <div class="flex items-center justify-between">
                <h3 class="text-4xl font-black text-slate-800 dark:text-white">99<span class="text-lg">%</span></h3>
                <i class="fas fa-server text-2xl text-amber-500 opacity-20"></i>
            </div>
        </div>
    </div>
</div>
@endsection