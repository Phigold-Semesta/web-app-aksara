@extends('layouts.app')

@section('title', 'Dashboard Eksekutif - Aksara')

@section('content')
<div class="p-2 md:p-4 space-y-8 animate__animated animate__fadeIn">
    
    {{-- Header Banner --}}
    <div class="relative overflow-hidden bg-[#006b43] rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl border border-emerald-400/20">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="space-y-4 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-black tracking-tighter leading-none uppercase italic">
                    Dashboard<br>Eksekutif
                </h1>
                <p class="text-emerald-200 text-xs md:text-sm font-bold uppercase tracking-[0.2em]">Monitoring Strategis & Data Laporan LPSE Karawang</p>
            </div>
            
            {{-- Dropdown Export (Fixed: Click-Based Toggle with .contains) --}}
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-[2rem] w-full md:w-auto shadow-2xl">
                <div id="dropdownWrapper" class="relative">
                    <button onclick="toggleDropdown(event, 'menuDropdownEkspor')" class="bg-white text-[#006b43] px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl flex items-center gap-3">
                        <i class="fas fa-file-export"></i> Ekspor Data Laporan <i class="fas fa-chevron-down text-[8px]"></i>
                    </button>
                    {{-- Dropdown Menu --}}
                    <div id="menuDropdownEkspor" class="hidden absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-2xl py-3 z-50 animate__animated animate__fadeIn">
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

    {{-- Monitoring Surat Table --}}
    <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl border border-slate-50 dark:border-slate-800">
        <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-white mb-6">Monitoring Surat Terbaru</h3>
        <div class="overflow-x-auto">
            <table id="tabelSuratMonitoring" class="w-full text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <th class="px-6 py-4">Nomor Surat</th>
                        <th class="px-6 py-4">Instansi</th>
                        <th class="px-6 py-4">Perihal</th>
                        <th class="px-6 py-4 text-center">Tanggal</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach($surats as $s)
                    <tr class="bg-white dark:bg-slate-800/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                        <td class="p-6 rounded-l-[1.5rem] font-black text-emerald-600 uppercase">{{ $s->nomor_surat }}</td>
                        <td class="p-6 font-bold text-slate-800 dark:text-white uppercase">{{ $s->asal_instansi }}</td>
                        <td class="p-6 text-slate-500">{{ $s->perihal }}</td>
                        <td class="p-6 text-center text-slate-500">{{ \Carbon\Carbon::parse($s->tanggal_surat)->format('d M Y') }}</td>
                        <td class="p-6 text-right rounded-r-[1.5rem]">
                            <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase italic {{ $s->status == 'disposisi' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $s->status }}
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

@push('scripts')
<script>
    // Fungsi Toggle dengan logic yang lebih cerdas
    function toggleDropdown(event, id) {
        event.stopPropagation();
        var menu = document.getElementById(id);
        menu.classList.toggle('hidden');
    }

    // Logic Klik di luar area
    window.onclick = function(event) {
        var menu = document.getElementById("menuDropdownEkspor");
        var wrapper = document.getElementById("dropdownWrapper");
        
        // Tutup hanya jika klik dilakukan di luar wrapper (tombol & menu)
        if (!wrapper.contains(event.target)) {
            if (!menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        }
    }
</script>
@endpush