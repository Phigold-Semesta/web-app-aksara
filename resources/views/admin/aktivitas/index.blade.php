{{-- resources/views/admin/aktivitas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Audit Log Sistem - Aksara')

@section('content')
<div class="p-2 md:p-4 space-y-6 animate__animated animate__fadeIn">
    
    <div class="relative overflow-hidden bg-[#006b43] rounded-[2rem] p-6 md:p-10 text-white shadow-2xl border border-emerald-400/20">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-2 text-center md:text-left">
                <span class="px-3 py-1 bg-white/20 text-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-widest italic">
                    Keamanan & Integritas Data
                </span>
                <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none uppercase italic mt-1">
                    MONITORING AUDIT LOG
                </h1>
                <p class="text-emerald-200 text-xs md:text-sm font-bold uppercase tracking-[0.15em]">
                    Rekam Jejak Digital dan Pengawasan Aktivitas Sistem AKSARA
                </p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center shadow-xl w-full md:w-auto">
                <p class="text-[9px] font-black uppercase text-emerald-300">Status Pengawasan</p>
                <p class="text-xs font-black uppercase tracking-tight flex items-center justify-center gap-2 mt-0.5">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span> Sistem Terproteksi
                </p>
            </div>
        </div>
        <div class="absolute top-0 right-0 p-4 opacity-5 text-[15rem] pointer-events-none text-white font-black">
            <i class="fas fa-fingerprint"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-xl border border-slate-50 dark:border-slate-800">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl">
            <div class="space-y-1">
                <label class="text-[9px] font-black uppercase text-slate-400 ml-2">Mulai Tanggal</label>
                <input type="date" id="logMinDate" class="w-full bg-white dark:bg-slate-800 border-none rounded-xl text-xs font-bold p-3 focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-black uppercase text-slate-400 ml-2">Sampai Tanggal</label>
                <input type="date" id="logMaxDate" class="w-full bg-white dark:bg-slate-800 border-none rounded-xl text-xs font-bold p-3 focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-black uppercase text-slate-400 ml-2">Cari Aktivitas / User</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" id="logSearch" class="w-full bg-white dark:bg-slate-800 border-none rounded-xl text-xs font-bold p-3 pl-10 focus:ring-2 focus:ring-emerald-500" placeholder="Ketik nama, aksi, atau IP address...">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="auditLogTable" class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3">Aktivitas / Tindakan</th>
                        <th class="px-6 py-3 text-center">IP Address</th>
                        <th class="px-6 py-3 text-right">Waktu Kejadian</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @forelse($logs as $log)
                    <tr class="bg-white dark:bg-slate-800/40 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 transition-all rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
                        
                        <td class="px-6 py-4 rounded-l-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-[#006b43] font-black text-[10px] uppercase">
                                    {{ substr($log->user->nama_lengkap ?? 'SY', 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 dark:text-white uppercase tracking-tight">
                                        {{ $log->user->nama_lengkap ?? 'Sistem Otomatis' }}
                                    </p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
                                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-md font-black">
                                            {{ $log->user->role ?? 'SYSTEM' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Perbaikan: Menggunakan kolom 'aktivitas' dan 'deskripsi' dari database --}}
                        <td class="px-6 py-4 font-bold text-slate-600 dark:text-slate-300">
                            <div class="flex flex-col">
                                <span class="text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">{{ $log->aktivitas }}</span>
                                <span class="text-[10px] text-slate-500 font-normal italic mt-0.5">{{ $log->deskripsi }}</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-md font-mono text-[10px] font-bold">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 text-right rounded-r-xl font-bold text-slate-400 italic">
                            {{ $log->created_at ? $log->created_at->diffForHumans() : '-' }}
                            <span class="block text-[9px] font-medium not-italic text-slate-300 dark:text-slate-500 mt-0.5 font-mono">
                                {{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white dark:bg-slate-800/40 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">
                        <td colspan="4" class="px-6 py-10 text-center font-black uppercase text-slate-400 rounded-xl">
                            Belum ada rekaman aktivitas log di database
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div id="auditTableInfo" class="text-[10px] font-black uppercase text-slate-400 tracking-wider"></div>
            <div id="auditTablePagination"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper .dataTables_filter, 
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_wrapper .dataTables_length { display: none; }
    
    table.dataTable.no-footer { border-bottom: none !important; }
    
    .paginate_button {
        padding: 6px 14px !important;
        margin: 0 4px !important;
        border-radius: 12px !important;
        border: none !important;
        font-size: 10px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        cursor: pointer;
        display: inline-block;
    }
    .paginate_button.current {
        background: #006b43 !important;
        color: white !important;
    }
    .paginate_button:hover:not(.current) {
        background: #f1f5f9 !important;
        color: #006b43 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var min = $('#logMinDate').val();
            var max = $('#logMaxDate').val();
            var dateStr = data[3].trim().split(" ")[0]; 

            if ((min === "" && max === "") ||
                (min === "" && dateStr <= max) ||
                (min <= dateStr && max === "") ||
                (min <= dateStr && dateStr <= max)) {
                return true;
            }
            return false;
        });

        var table = $('#auditLogTable').DataTable({
            paging: true,
            pageLength: 10,
            ordering: true,
            info: true,
            language: {
                zeroRecords: "<div class='py-10 text-center font-black uppercase text-slate-400'>Log data tidak ditemukan</div>",
                paginate: { previous: "Kembali", next: "Lanjut" }
            },
            drawCallback: function() {
                $('#auditTablePagination').html($('.dataTables_paginate').html());
                $('#auditTableInfo').text($('.dataTables_info').text());
            }
        });

        $('#logSearch').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#logMinDate, #logMaxDate').on('change', function() {
            table.draw();
        });
    });
</script>
@endpush