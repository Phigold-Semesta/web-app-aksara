@extends('layouts.app')

@section('title', 'Petugas Dashboard - Aksara')

@section('content')
<div class="p-2 md:p-4 space-y-8 animate__animated animate__fadeIn">
    
    <!-- HERO BANNER WORKFLOW -->
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

    <!-- STATS CARD GRID -->
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

    <!-- TABEL LAPORAN KOMPREHENSIF -->
    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl border border-slate-50 dark:border-slate-800 mt-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h2 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter italic">Laporan Data Komprehensif</h2>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Cetak & Unduh Dokumen Pelaporan Master Data</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Export -->
                <div class="relative group">
                    <button class="bg-[#008f5d] text-white px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-emerald-500/20">
                        <i class="fas fa-file-export"></i> Export Data <i class="fas fa-chevron-down text-[8px]"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-2xl py-3 z-50 hidden group-hover:block animate__animated animate__fadeIn">
                        <button onclick="exportAction('excel')" class="w-full text-left px-6 py-2 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 flex items-center gap-3"><i class="fas fa-file-excel text-emerald-500"></i> Excel (.xlsx)</button>
                        <button onclick="exportAction('pdf')" class="w-full text-left px-6 py-2 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 flex items-center gap-3"><i class="fas fa-file-pdf text-red-500"></i> PDF (.pdf)</button>
                        <button onclick="exportAction('csv')" class="w-full text-left px-6 py-2 text-[10px] font-black uppercase text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 flex items-center gap-3"><i class="fas fa-file-csv text-blue-500"></i> CSV (.csv)</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 bg-slate-50 dark:bg-slate-800/50 p-6 rounded-[1.5rem]">
            <div class="space-y-1">
                <label class="text-[9px] font-black uppercase text-slate-400 ml-2">Mulai Tanggal</label>
                <input type="date" id="minDate" class="w-full bg-white dark:bg-slate-800 border-none rounded-xl text-xs font-bold p-3 focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-black uppercase text-slate-400 ml-2">Sampai Tanggal</label>
                <input type="date" id="maxDate" class="w-full bg-white dark:bg-slate-800 border-none rounded-xl text-xs font-bold p-3 focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="space-y-1 relative">
                <label class="text-[9px] font-black uppercase text-slate-400 ml-2">Jumlah Baris</label>
                <div class="relative group/row w-full">
                    <button id="rowBtn" class="w-full bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 px-4 py-3 rounded-xl font-bold text-xs flex justify-between items-center border-none shadow-sm group-hover/row:ring-2 group-hover/row:ring-emerald-500 transition-all">
                        <span id="rowValue">5 Baris</span> <i class="fas fa-chevron-down text-[8px]"></i>
                    </button>
                    <div class="absolute w-full mt-2 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl shadow-xl py-2 z-50 hidden group-hover/row:block">
                        <button onclick="changeLength(5, '5 Baris')" class="w-full text-left px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600">5 Baris</button>
                        <button onclick="changeLength(10, '10 Baris')" class="w-full text-left px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600">10 Baris</button>
                        <button onclick="changeLength(-1, 'Semua Data')" class="w-full text-left px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600">Semua Data</button>
                    </div>
                </div>
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-black uppercase text-slate-400 ml-2">Cari Dokumen</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" id="customSearch" class="w-full bg-white dark:bg-slate-800 border-none rounded-xl text-xs font-bold p-3 pl-10 focus:ring-2 focus:ring-emerald-500" placeholder="Nomor, instansi, perihal...">
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
                            <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $surat->perihal }}</p>
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
        
        <div class="mt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div id="tableInfoContainer" class="text-[10px] font-black uppercase text-slate-400"></div>
            <div id="paginationContainer"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<style>
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, 
    .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { display: none; }
    
    table.dataTable.no-footer { border-bottom: none !important; }
    
    .paginate_button {
        padding: 8px 16px !important;
        margin: 0 4px !important;
        border-radius: 12px !important;
        border: none !important;
        font-size: 10px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        cursor: pointer;
    }
    .paginate_button.current {
        background: #008f5d !important;
        color: white !important;
    }
    .paginate_button:hover:not(.current) {
        background: #f1f5f9 !important;
        color: #008f5d !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
    var globalTable;

    function exportAction(type) {
        if(type === 'excel') $('.buttons-excel').click();
        if(type === 'pdf') $('.buttons-pdf').click();
        if(type === 'csv') $('.buttons-csv').click();
    }

    // Fungsi baru untuk mengganti panjang baris via tombol dropdown kustom
    function changeLength(val, label) {
        $('#rowValue').text(label);
        globalTable.page.len(val).draw();
    }

    $(document).ready(function() {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var date = data[3]; 

            if ((min === "" && max === "") ||
                (min === "" && date <= max) ||
                (min <= date && max === "") ||
                (min <= date && date <= max)) {
                return true;
            }
            return false;
        });

        globalTable = $('#masterReportTable').DataTable({
            paging: true,
            pageLength: 5,
            lengthMenu: [[5, 10, -1], [5, 10, "Semua"]],
            ordering: true,
            info: true,
            buttons: [
                { extend: 'excel', className: 'hidden' },
                { extend: 'pdf', className: 'hidden', orientation: 'landscape' },
                { extend: 'csv', className: 'hidden' }
            ],
            language: {
                zeroRecords: "<div class='py-10 text-center font-black uppercase text-slate-400'>Data tidak ditemukan</div>",
                paginate: {
                    previous: "Kembali",
                    next: "Lanjut"
                }
            },
            drawCallback: function() {
                $('#paginationContainer').html($('.dataTables_paginate').html());
                $('#tableInfoContainer').text($('.dataTables_info').text());
            }
        });

        // Event listener untuk searching kustom dengan icon
        $('#customSearch').on('keyup', function() {
            globalTable.search($(this).val()).draw();
        });

        $('#minDate, #maxDate').on('change', function() {
            globalTable.draw();
        });
    });
</script>
@endpush