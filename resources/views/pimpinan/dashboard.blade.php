@extends('layouts.app')

@section('title', 'Laporan & Statistik Sistem')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-emerald-900/50 p-8 rounded-[2rem] shadow-sm border border-emerald-100 dark:border-emerald-800 transition-colors">
        <div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter flex items-center gap-3">
                <i class="fas fa-chart-pie text-[#008f5d]"></i> Analisis Statistik
            </h1>
            <p class="text-xs text-slate-500 dark:text-emerald-300/70 mt-1 font-bold tracking-widest uppercase">Pusat Data Eksekutif AKSARA - LPSE Karawang</p>
        </div>
        
        <div class="relative inline-block text-left" id="dropdownEksporContainer">
            <button onclick="toggleDropdownEkspor()" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-emerald-600 hover:bg-[#007a50] text-white font-black text-sm transition-all shadow-lg shadow-emerald-600/20">
                <i class="fas fa-file-export text-xs"></i> <span>Ekspor Data</span>
            </button>
            <div id="menuDropdownEkspor" class="hidden absolute right-0 mt-2 w-48 rounded-2xl bg-white dark:bg-emerald-950 border border-slate-100 dark:border-emerald-800 shadow-xl z-50 overflow-hidden">
                <div class="py-1">
                    <button onclick="eksporKeCSV()" class="w-full text-left px-4 py-3 text-sm font-bold text-slate-700 dark:text-emerald-200 hover:bg-emerald-50 dark:hover:bg-emerald-900 transition-colors">CSV</button>
                    <button onclick="eksporKeExcel()" class="w-full text-left px-4 py-3 text-sm font-bold text-slate-700 dark:text-emerald-200 hover:bg-emerald-50 dark:hover:bg-emerald-900 transition-colors">Excel</button>
                    <button onclick="eksporKePDF()" class="w-full text-left px-4 py-3 text-sm font-bold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-emerald-900 transition-colors">PDF Report</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @php
            $stats = [
                ['label' => 'Volume Surat Masuk', 'val' => $totalSuratMasuk ?? 0, 'icon' => 'fa-envelope-open-text', 'color' => 'text-emerald-600'],
                ['label' => 'Volume Surat Keluar', 'val' => $totalSuratKeluar ?? 0, 'icon' => 'fa-paper-plane', 'color' => 'text-emerald-500'],
                ['label' => 'Total Disposisi', 'val' => $totalDisposisi ?? 0, 'icon' => 'fa-paste', 'color' => 'text-emerald-700']
            ];
        @endphp
        @foreach($stats as $stat)
        <div class="bg-white dark:bg-emerald-900 p-8 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $stat['label'] }}</p>
                <h2 class="text-4xl font-black text-slate-800 dark:text-white mt-2">{{ $stat['val'] }}</h2>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950 flex items-center justify-center {{ $stat['color'] }} text-xl">
                <i class="fas {{ $stat['icon'] }}"></i>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-emerald-900 p-8 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-white mb-6">Tren Sirkulasi Dokumen</h3>
            <div class="relative w-full h-80">
                <canvas id="chartSirkulasi"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-emerald-900 p-8 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-white mb-6">Proporsi Kategori</h3>
            <div class="relative w-full h-80">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white dark:bg-emerald-900 rounded-[2rem] border border-emerald-50 dark:border-emerald-800 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-emerald-50 dark:border-emerald-800">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-white">Daftar Master Kategori Sistem</h3>
        </div>
        <table id="tabelLaporanSistem" class="w-full text-left">
            <thead class="bg-slate-50 dark:bg-emerald-950">
                <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                    <th class="p-6">ID</th>
                    <th class="p-6">Nama Kategori</th>
                    <th class="p-6">Keterangan</th>
                    <th class="p-6 text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-50 dark:divide-emerald-800">
                @foreach($kategoriList as $k)
                <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-800/20 transition">
                    <td class="p-6 font-mono text-xs text-emerald-600">#KS-{{ $k->id }}</td>
                    <td class="p-6 font-bold text-slate-800 dark:text-white">{{ $k->nama_kategori }}</td>
                    <td class="p-6 text-sm text-slate-500">{{ $k->keterangan ?? '-' }}</td>
                    <td class="p-6 text-right"><span class="px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700">ACTIVE</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Scripts (Chart.js & Export Tools) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
    function toggleDropdownEkspor() { document.getElementById('menuDropdownEkspor').classList.toggle('hidden'); }

    // Chart Configuration
    document.addEventListener("DOMContentLoaded", function() {
        const ctx1 = document.getElementById('chartSirkulasi').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
                datasets: [{
                    label: 'Masuk', data: [180, 240, 210, 310, 342],
                    borderColor: '#008f5d', backgroundColor: 'rgba(0, 143, 93, 0.1)', fill: true, tension: 0.4
                }, {
                    label: 'Keluar', data: [110, 150, 130, 190, 189],
                    borderColor: '#059669', backgroundColor: 'rgba(5, 150, 105, 0.1)', fill: true, tension: 0.4
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });

    // ... (Fungsi ekspor tetap sama, hanya sesuaikan nama file)
</script>
@endsection