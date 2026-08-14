@extends('layouts.app')

@section('title', 'Laporan & Statistik')

@section('content')
<div class="space-y-6 animate__animated animate__fadeIn">

    <!-- Header & Filter Eksekutif Laporan -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white dark:bg-emerald-900 p-6 rounded-[2rem] shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight flex items-center gap-2">
                <i class="fas fa-chart-pie text-[#008f5d]"></i> Laporan & Analisis Statistik
            </h1>
            <p class="text-xs md:text-sm text-slate-500 dark:text-emerald-300/70 mt-1">Dinamika sirkulasi surat, kuantitas dokumen arsip, dan visualisasi data terintegrasi AKSARA LPSE.</p>
        </div>
        
        <!-- Panel Filter & Tombol Ekspor Dropdown -->
        <div class="flex flex-wrap items-center gap-3 shrink-0">
            
            <!-- Filter Form -->
            <form method="GET" action="" class="flex flex-wrap items-center gap-2">
                <select name="tahun" onchange="this.form.submit()" class="px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-emerald-950 text-slate-700 dark:text-emerald-200 font-bold text-xs border border-slate-200 dark:border-emerald-800 focus:outline-none">
                    <option value="">Semua Tahun</option>
                    @foreach($filterTahunList ?? [2024, 2025, 2026] as $th)
                        <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>{{ $th }}</option>
                    @endforeach
                </select>

                <select name="bulan" onchange="this.form.submit()" class="px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-emerald-950 text-slate-700 dark:text-emerald-200 font-bold text-xs border border-slate-200 dark:border-emerald-800 focus:outline-none">
                    <option value="">Semua Bulan</option>
                    @php 
                        $bulanList = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                    @endphp
                    @foreach($bulanList as $key => $namaBulan)
                        <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $namaBulan }}</option>
                    @endforeach
                </select>

                <select name="kategori" onchange="this.form.submit()" class="px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-emerald-950 text-slate-700 dark:text-emerald-200 font-bold text-xs border border-slate-200 dark:border-emerald-800 focus:outline-none">
                    <option value="">Semua Kategori / Penyedia</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id_kategori ?? $kat->id }}" {{ request('kategori') == ($kat->id_kategori ?? $kat->id) ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
            </form>

            <!-- Dropdown Ekspor Data yang Terhubung ke Backend dengan Query Parameter Filter -->
            <div class="relative inline-block text-left" id="dropdownEksporContainer">
                <button onclick="toggleDropdownEkspor()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#008f5d] hover:bg-[#00724a] text-white font-bold text-xs transition-all shadow-md">
                    <i class="fas fa-download text-xs"></i>
                    <span>Ekspor Data</span>
                    <i class="fas fa-chevron-down text-[9px] ml-1"></i>
                </button>
                
                <div id="menuDropdownEkspor" class="hidden absolute right-0 mt-2 w-48 rounded-2xl bg-white dark:bg-emerald-950 border border-slate-100 dark:border-emerald-800 shadow-xl z-50 overflow-hidden transition-all">
                    <div class="py-1">
                        <a href="{{ route('admin.laporan.export.csv') }}?{{ http_build_query(request()->all()) }}" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-emerald-200 hover:bg-slate-50 dark:hover:bg-emerald-900 transition-colors">
                            <i class="fas fa-file-csv text-blue-500 text-base w-5"></i> Ekspor ke CSV
                        </a>
                        <a href="{{ route('admin.laporan.export.excel') }}?{{ http_build_query(request()->all()) }}" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-emerald-200 hover:bg-slate-50 dark:hover:bg-emerald-900 transition-colors">
                            <i class="fas fa-file-excel text-emerald-600 text-base w-5"></i> Ekspor ke Excel
                        </a>
                        <a href="{{ route('admin.laporan.export.pdf') }}?{{ http_build_query(request()->all()) }}" target="_blank" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-xs font-semibold text-slate-700 dark:text-emerald-200 hover:bg-slate-50 dark:hover:bg-emerald-900 transition-colors">
                            <i class="fas fa-file-pdf text-red-500 text-base w-5"></i> Ekspor ke PDF
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-emerald-900 p-6 rounded-3xl border border-emerald-50 dark:border-emerald-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-xs font-black uppercase tracking-wider text-slate-400 dark:text-emerald-400">Volume Surat Masuk</span>
                <span class="block text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $totalSuratMasuk ?? 0 }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 mt-2">
                    <i class="fas fa-arrow-down text-xs"></i> Dokumen Masuk Terdaftar
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950 flex items-center justify-center text-[#008f5d] text-lg">
                <i class="fas fa-envelope-open-text"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-emerald-900 p-6 rounded-3xl border border-emerald-50 dark:border-emerald-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-xs font-black uppercase tracking-wider text-slate-400 dark:text-emerald-400">Volume Surat Keluar</span>
                <span class="block text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $totalSuratKeluar ?? 0 }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 dark:text-blue-400 mt-2">
                    <i class="fas fa-arrow-up text-xs"></i> Dokumen Keluar Diterbitkan
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-emerald-950 flex items-center justify-center text-blue-500 text-lg">
                <i class="fas fa-paper-plane"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-emerald-900 p-6 rounded-3xl border border-emerald-50 dark:border-emerald-800 shadow-sm flex items-center justify-between">
            <div>
                <span class="block text-xs font-black uppercase tracking-wider text-slate-400 dark:text-emerald-400">Total Instruksi Disposisi</span>
                <span class="block text-3xl font-black text-slate-800 dark:text-white mt-1">{{ $totalDisposisi ?? 0 }}</span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-500 mt-2">
                    <i class="fas fa-copy text-xs"></i> Lembar Disposisi Pimpinan
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-emerald-950 flex items-center justify-center text-amber-500 text-lg">
                <i class="fas fa-paste"></i>
            </div>
        </div>
    </div>

    <!-- Grafik Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-emerald-900 p-6 rounded-3xl border border-emerald-50 dark:border-emerald-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-700 dark:text-white">Tren Sirkulasi Dokumen</h3>
                    <p class="text-xs text-slate-400 dark:text-emerald-400/70">Perbandingan intensitas data dokumen masuk dan keluar</p>
                </div>
            </div>
            <div class="relative w-full h-72">
                <canvas id="chartSirkulasi"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-emerald-900 p-6 rounded-3xl border border-emerald-50 dark:border-emerald-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider text-slate-700 dark:text-white">Daftar Kategori Surat</h3>
                    <p class="text-xs text-slate-400 dark:text-emerald-400/70">Proporsi master klasifikasi kategori saat ini</p>
                </div>
            </div>
            <div class="relative w-full h-72 flex items-center justify-center">
                @if($kategoriList->isEmpty())
                    <p class="text-xs text-slate-400 font-semibold">Belum ada data kategori surat.</p>
                @else
                    <canvas id="chartKategori"></canvas>
                @endif
            </div>
        </div>
    </div>

    <!-- Tabel Master Kategori & Surat Terfilter -->
    <div class="bg-white dark:bg-emerald-900 rounded-3xl border border-emerald-50 dark:border-emerald-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 dark:border-emerald-800/50 flex justify-between items-center">
            <h3 class="text-sm font-black uppercase tracking-wider text-slate-700 dark:text-white">Daftar Master Kategori dan Informasi Sistem</h3>
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-3 py-1 rounded-full">Periode Terpilih: {{ request('tahun') ?: 'Semua Tahun' }}</span>
        </div>
        <div class="overflow-x-auto">
            <table id="tabelLaporanSistem" class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 dark:bg-emerald-950 text-slate-500 dark:text-emerald-300 text-xs font-black uppercase tracking-wider border-b border-slate-100 dark:border-emerald-800/50">
                        <th class="p-4 pl-6">ID Kategori</th>
                        <th class="p-4">Nama Kategori Surat</th>
                        <th class="p-4">Kode / Rincian</th>
                        <th class="p-4 pr-6">Status Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-emerald-800/30 font-semibold text-slate-700 dark:text-slate-200">
                    @forelse($kategoriList as $kategori)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-emerald-950/20 transition-colors">
                            <td class="p-4 pl-6 font-mono text-xs text-slate-400">#00{{ $kategori->id_kategori ?? $kategori->id }}</td>
                            <td class="p-4 text-slate-800 dark:text-white">{{ $kategori->nama_kategori }}</td>
                            <td class="p-4 font-normal text-slate-500 dark:text-emerald-300/70">{{ $kategori->keterangan ?? 'Klasifikasi Resmi AKSARA' }}</td>
                            <td class="p-4 pr-6"><span class="px-2.5 py-1 rounded-full text-[10px] bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300">Terintegrasi</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 dark:text-emerald-400/50 text-xs">Tidak ada data master kategori surat dalam database.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- CDN untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Fungsi Manajemen Dropdown Ekspor
    function toggleDropdownEkspor() {
        const menu = document.getElementById('menuDropdownEkspor');
        menu.classList.toggle('hidden');
    }

    window.addEventListener('click', function(e) {
        const container = document.getElementById('dropdownEksporContainer');
        const menu = document.getElementById('menuDropdownEkspor');
        if (container && !container.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // ==========================================
    // INISIALISASI GRAFIK CHART.JS DINAMIS
    // ==========================================
    document.addEventListener("DOMContentLoaded", function() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textGridColor = isDarkMode ? '#fff' : '#475569';

        const ctxSirkulasi = document.getElementById('chartSirkulasi').getContext('2d');
        new Chart(ctxSirkulasi, {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsBulan ?? []) !!},
                datasets: [
                    {
                        label: 'Surat Masuk',
                        data: {!! json_encode($dataSuratMasuk ?? []) !!},
                        borderColor: '#008f5d',
                        backgroundColor: 'rgba(0, 143, 93, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Surat Keluar',
                        data: {!! json_encode($dataSuratKeluar ?? []) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {  
                    legend: { labels: { color: textGridColor, font: { weight: 'bold' } } }  
                },
                scales: {
                    y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { color: '#94a3b8' } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                }
            }
        });

        @if(!$kategoriList->isEmpty())
            const ctxKategori = document.getElementById('chartKategori').getContext('2d');
            const kategoriLabels = {!! json_encode($kategoriList->pluck('nama_kategori')) !!};
            const dataCounts = {!! json_encode($dataKategoriCounts ?? []) !!};

            if (dataCounts.length === 0) {
                for (let i = 0; i < kategoriLabels.length; i++) {
                    dataCounts.push(0);
                }
            }

            new Chart(ctxKategori, {
                type: 'doughnut',
                data: {
                    labels: kategoriLabels,
                    datasets: [{
                        data: dataCounts,
                        backgroundColor: ['#008f5d', '#3b82f6', '#f59e0b', '#10b981', '#6366f1', '#ec4899', '#64748b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, padding: 10, color: textGridColor, font: { size: 11, weight: 'bold' } }
                        }
                    }
                }
            });
        @endif
    });
</script>
@endsection