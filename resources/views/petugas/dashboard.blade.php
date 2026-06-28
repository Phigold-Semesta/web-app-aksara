@extends('layouts.app')

@section('title', 'Petugas Dashboard - Aksara')

@section('content')
<div class="p-2 md:p-4 space-y-8 animate__animated animate__fadeIn">
    
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

    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl border border-slate-50 dark:border-slate-800 mt-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">Laporan Data Komprehensif</h2>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Cetak & Unduh Dokumen Pelaporan Master Data</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative group">
                    <button class="bg-[#008f5d] text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                        <i class="fas fa-file-export"></i> Export Data <i class="fas fa-chevron-down text-[8px]"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-2xl py-3 z-50 hidden group-hover:block animate__animated animate__fadeIn">
                        <a href="{{ route('petugas.export.excel') }}" class="w-full text-left px-6 py-2 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 flex items-center gap-3">
                            <i class="fas fa-file-excel text-emerald-500"></i> Excel (.xlsx)
                        </a>
                        <a href="{{ route('petugas.export.pdf') }}" class="w-full text-left px-6 py-2 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 flex items-center gap-3">
                            <i class="fas fa-file-pdf text-red-500"></i> PDF (.pdf)
                        </a>
                        <a href="{{ route('petugas.export.csv') }}" class="w-full text-left px-6 py-2 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 flex items-center gap-3">
                            <i class="fas fa-file-csv text-blue-500"></i> CSV (.csv)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="masterReportTable" class="w-full text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-6 py-4">Nomor Dokumen</th>
                        <th class="px-6 py-4">Asal Instansi</th>
                        <th class="px-6 py-4 text-center">Kategori</th>
                        <th class="px-6 py-4 text-center">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach($riwayat_surats as $surat)
                    <tr class="bg-white dark:bg-slate-800/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                        <td class="px-6 py-5 rounded-l-[1.5rem]">
                            <p class="font-black text-slate-800 dark:text-white uppercase">{{ $surat->nomor_surat }}</p>
                        </td>
                        <td class="px-6 py-5 font-bold text-slate-600 dark:text-slate-300 uppercase italic">{{ $surat->asal_instansi }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-xl font-black uppercase text-[9px]">
                                {{ $surat->kategori->nama_kategori ?? 'UMUM' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-center font-bold text-slate-500 italic">
                            {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-5 text-center rounded-r-[1.5rem]">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black uppercase italic {{ $surat->status == 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $surat->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { display: none; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // DataTables hanya untuk fitur pencarian (search) dan pagination saja
        $('#masterReportTable').DataTable({
            paging: true,
            pageLength: 5,
            ordering: true
        });
    });
</script>
@endpush