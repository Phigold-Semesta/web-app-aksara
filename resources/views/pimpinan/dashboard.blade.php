@extends('layouts.app')

@section('title', 'Executive Dashboard')

@section('content')
<div class="space-y-10">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-emerald-600 dark:text-emerald-400 font-black text-[10px] uppercase tracking-[0.4em] mb-2">Management Overview</p>
            <h1 class="text-5xl font-black text-slate-800 dark:text-white tracking-tighter uppercase italic">Ringkasan<br>Eksekutif</h1>
        </div>
        <div class="bg-white dark:bg-emerald-900 px-6 py-4 rounded-3xl shadow-lg border border-emerald-50 dark:border-emerald-800">
            <p class="text-slate-400 text-[10px] font-bold uppercase italic leading-none">Status Dokumen</p>
            <p class="text-emerald-600 font-black text-lg mt-1 leading-none italic">94.8% Terproses</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Approval Waiting List -->
        <div class="lg:col-span-2 bg-white dark:bg-emerald-900/40 p-10 rounded-[3rem] border border-emerald-50 dark:border-emerald-800 shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Butuh Instruksi</h3>
                <span class="w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
            </div>
            
            <div class="space-y-4">
                @for ($i = 1; $i <= 2; $i++)
                <div class="group flex items-center justify-between p-6 bg-slate-50 dark:bg-emerald-800/20 rounded-[2rem] hover:bg-emerald-50 transition border border-transparent hover:border-emerald-100">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-white dark:bg-emerald-800 shadow-sm flex items-center justify-center rounded-2xl text-emerald-600 font-black italic italic">D{{$i}}</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">Surat Perintah Kerja (SPK) #202{{$i}}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Masuk: 10:45 WIB • Prioritas Tinggi</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 group-hover:text-emerald-500 transition"></i>
                </div>
                @endfor
            </div>
        </div>

        <!-- Performance Graph Placeholder -->
        <div class="bg-[#008f5d] p-10 rounded-[3rem] shadow-2xl text-white relative overflow-hidden flex flex-col justify-between">
            <div class="relative z-10">
                <p class="text-emerald-200 text-[10px] font-black uppercase tracking-widest mb-2">Performance</p>
                <h4 class="text-3xl font-black italic tracking-tighter">LPSE Metrics</h4>
            </div>
            
            <div class="mt-12 relative z-10">
                <div class="flex items-end gap-2 h-32">
                    <div class="flex-1 bg-white/20 rounded-t-lg h-24"></div>
                    <div class="flex-1 bg-white/40 rounded-t-lg h-32"></div>
                    <div class="flex-1 bg-white/20 rounded-t-lg h-20"></div>
                    <div class="flex-1 bg-white/60 rounded-t-lg h-28"></div>
                    <div class="flex-1 bg-white rounded-t-lg h-32"></div>
                </div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-center mt-4 opacity-60">Statistik Surat Terproses</p>
            </div>
            <i class="fas fa-chart-simple absolute -left-10 -bottom-10 text-[15rem] text-white opacity-5"></i>
        </div>
    </div>
</div>
@endsection